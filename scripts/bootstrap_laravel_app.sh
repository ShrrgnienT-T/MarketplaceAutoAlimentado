#!/usr/bin/env bash
set -euo pipefail

# Bootstrap real do app Laravel neste repositório.
# Uso: ./scripts/bootstrap_laravel_app.sh

REPO_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
TMP_DIR="/tmp/marketplace-laravel-skeleton"

cd "$REPO_DIR"

if [[ -f artisan ]]; then
  echo "Laravel já detectado (arquivo artisan presente). Nada a fazer."
  exit 0
fi

command -v composer >/dev/null || { echo "composer não encontrado"; exit 1; }
command -v rsync >/dev/null || { echo "rsync não encontrado"; exit 1; }

rm -rf "$TMP_DIR"

echo "[1/4] Baixando skeleton Laravel via Composer..."
composer create-project laravel/laravel "$TMP_DIR" --no-interaction

echo "[2/4] Copiando skeleton para o repositório atual..."
rsync -a --exclude '.git' "$TMP_DIR"/ "$REPO_DIR"/

echo "[3/4] Instalando dependências PHP..."
composer install --no-interaction

echo "[4/4] Instalando dependências JS..."
npm install

echo "Bootstrap Laravel finalizado com sucesso."
