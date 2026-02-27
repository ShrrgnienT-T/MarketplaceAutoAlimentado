#!/usr/bin/env bash
set -euo pipefail

required_vars=(
  APP_LOCALE
  APP_TIMEZONE
  APP_CURRENCY
  DB_CONNECTION
  QUEUE_CONNECTION
  CACHE_STORE
  VITE_DEV_SERVER_URL
)

if [[ ! -f .env.example ]]; then
  echo "ERRO: .env.example não encontrado"
  exit 1
fi

for var in "${required_vars[@]}"; do
  if ! rg -q "^${var}=" .env.example; then
    echo "ERRO: variável ${var} ausente no .env.example"
    exit 1
  fi
done

echo "OK: baseline de ambiente do módulo 0.1 presente em .env.example"
