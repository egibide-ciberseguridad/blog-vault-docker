<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VaultCredentials
{
    public function handle(Request $request, Closure $next)
    {
        $nueva_concesion = true;

        $id_concesion = Cache::get('id_concesion');

        if (isset($id_concesion)) {
            $estado_concesion = $this->leer_estado($id_concesion);
            if (isset($estado_concesion)) {
                $ttl = $this->tiempo_restante($estado_concesion['data']['expire_time']);
                if ($ttl < 60) {
                    $this->renovar_concesion($id_concesion);
                }
                $nueva_concesion = false;
            } else {
                $this->revocar_concesion($id_concesion);
                Cache::forget('id_concesion');
            }
        }

        if ($nueva_concesion) {
            $concesion = $this->vault_read(config('vault.db_credential'));

            if (!isset($concesion)) {
                throw new Exception("Error de Vault: no se han podido obtener credenciales");
            }

            $this->guardar_configuracion($concesion['data']['username'], 'db_username');
            $this->guardar_configuracion($concesion['data']['password'], 'db_password');

            $id_concesion = $concesion['lease_id'];
            Cache::put('id_concesion', $id_concesion);
        }

        $this->cargar_configuracion('database.connections.mysql.username', 'db_username');
        $this->cargar_configuracion('database.connections.mysql.password', 'db_password');

        return $next($request);
    }

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

    private function renovar_concesion(mixed $lease_id): void
    {
        $this->vault_write('sys/leases/renew', [
            "lease_id" => $lease_id,
        ]);
    }

    private function revocar_concesion(mixed $lease_id): void
    {
        $this->vault_write('sys/leases/revoke', [
            "lease_id" => $lease_id,
        ]);
    }

    private function leer_estado(mixed $lease_id): mixed
    {
        $estado_concesion = $this->vault_write('sys/leases/lookup', [
            "lease_id" => $lease_id,
        ]);
        return $estado_concesion;
    }

    private function tiempo_restante($expire_time): int|float
    {
        $fecha_caducidad = Carbon::parse($expire_time);

        $ttl = now()->diffInSeconds($fecha_caducidad, false);
        return $ttl;
    }

    private function cargar_configuracion($config_key, $temp_file): void
    {
        $username = Storage::disk('temp')->get($temp_file);
        Config::set($config_key, $username);
    }

    private function guardar_configuracion($config_value, $temp_file): void
    {
        Storage::disk('temp')->put($temp_file, $config_value);
    }
}
