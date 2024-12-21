#!/bin/bash

if [ -z "$VAULT_TOKEN" ]; then
    read -s -p "Token para Vault: " TOKEN
    export VAULT_TOKEN=$TOKEN
fi

docker compose rm -fs app
docker compose up -d --remove-orphans
