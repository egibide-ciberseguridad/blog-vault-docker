<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VaultCredentials
{
    private $cliente = null;
    private $headers = null;

    private function init()
    {
        if (is_null($this->cliente))
            $this->cliente = new Client([
                'base_uri' => config('vault.url') . '/v1/'
            ]);

        if (is_null($this->headers))
            $this->headers = [
                'X-Vault-Token' => config('vault.token'),
            ];
    }

    private function vault_read($query)
    {
        $result = null;

        try {
            $this->init();

            $response = $this->cliente->get($query, [
                'headers' => $this->headers
            ]);

            $result = json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            Log::error($e->getMessage());
        }

        return $result;
    }

    private function vault_write($query, $data)
    {
        $result = null;

        try {
            $this->init();

            $response = $this->cliente->post($query, [
                'headers' => $this->headers,
                'json' => $data
            ]);

            $result = json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            Log::error($e->getMessage());
        }

        return $result;
    }

    public function handle(Request $request, Closure $next)
    {
        $estado_concesion = Cache::get('estado_concesion');

        if (isset($estado_concesion)) {
            $lease_id = $estado_concesion['data']['id'];
        } else {
            $concesion ??= $this->vault_read('egibide/blog/database/creds/blog-short');

            if (!isset($concesion)) {
                abort(500, "Error de Vault");
            }

            $username = $concesion['data']['username'];
            $password = $concesion['data']['password'];

            Storage::disk('temp')->put('db_username', $username);
            Storage::disk('temp')->put('db_password', $password);

            $lease_id = $concesion['lease_id'];
            $estado_concesion = Cache::remember('estado_concesion', 60, function () use ($lease_id) {
                return $this->vault_write('sys/leases/lookup', [
                    "lease_id" => $lease_id,
                ]);
            });
        }

        $username = Storage::disk('temp')->get('db_username');
        $password = Storage::disk('temp')->get('db_password');
        Config::set('database.connections.mysql.username', $username);
        Config::set('database.connections.mysql.password', $password);

        $fecha_caducidad = Carbon::parse($estado_concesion['data']['expire_time']);

        $ttl = now()->diffInSeconds($fecha_caducidad, false);
        dump($ttl);

        if ($ttl < 170) {
            $this->vault_write('sys/leases/renew', [
                "lease_id" => $lease_id,
            ]);
            Cache::forget('estado_concesion');
            Cache::remember('estado_concesion', 60, function () use ($lease_id) {
                return $this->vault_write('sys/leases/lookup', [
                    "lease_id" => $lease_id,
                ]);
            });
        }

        return $next($request);
    }
}
