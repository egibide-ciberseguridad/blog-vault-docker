<?php

return [
    'url' => env('VAULT_ADDR', 'http://vault-server:8200'),
    'token' => env('VAULT_TOKEN', 'root'),
];
