<?php

function read_docker_secret(string $name): string
{
    return trim(@file_get_contents('/run/secrets/' . $name));
}

function docker_secret(string $name): Closure
{
    return function () use ($name) {
        return read_docker_secret($name);
    };
}

return [
    'enabled' => env('VAULT_ENABLED', false),
    'url' => env('VAULT_ADDR', 'http://vault-server:8200'),
    'token' => env('VAULT_TOKEN', docker_secret('vault_token')),
    'db_credential' => env('VAULT_DB_CREDENTIAL', 'database/creds/blog'),
];
