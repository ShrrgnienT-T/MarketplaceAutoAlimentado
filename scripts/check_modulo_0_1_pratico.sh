#!/usr/bin/env bash
set -euo pipefail

check_ok=0
check_warn=0

ok() { echo "✅ $1"; check_ok=$((check_ok+1)); }
warn() { echo "⚠️  $1"; check_warn=$((check_warn+1)); }

[[ -f .env.example ]] && ok ".env.example presente" || warn ".env.example ausente"
rg -q '^APP_TIMEZONE=America/Sao_Paulo' .env.example && ok "timezone BR definido" || warn "timezone BR não definido"
rg -q '^APP_LOCALE=pt_BR' .env.example && ok "locale pt_BR definido" || warn "locale pt_BR não definido"
rg -q '^APP_CURRENCY=BRL' .env.example && ok "moeda BRL definida" || warn "moeda BRL não definida"

if [[ -f artisan ]]; then
  ok "scaffold Laravel detectado (artisan)"
else
  warn "scaffold Laravel ausente (sem artisan)"
fi

if [[ -f package.json ]]; then
  ok "frontend base detectado (package.json)"
else
  warn "frontend base ausente (sem package.json/Vite)"
fi

if [[ -f artisan ]] && [[ -f vendor/autoload.php ]]; then
  php artisan --version >/dev/null
  ok "artisan executável"
else
  warn "não foi possível validar artisan (vendor/autoload ausente)"
fi

echo "---"
echo "Resumo: ${check_ok} checks OK, ${check_warn} warnings"
