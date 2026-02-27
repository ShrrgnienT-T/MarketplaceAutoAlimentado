# Módulo 0.1 — Setup e baseline (status de execução)

## Decisão técnica antes de seguir

Você pediu para começar execução pelo módulo 0.1. O ponto cego aqui: **sem scaffold Laravel real**, itens como migrate/seed, Vite build real e integração de tema ficam só no papel.

## Status item a item

1. ✅ Criar `.env.example` consistente com filas/cache locais.
   - Entregue com defaults para MySQL, queue/cache database e locale/timezone/currency BR.
2. ✅ Definir timezone, locale e moeda padrão (`pt_BR`, `BRL`).
   - Entregue no `.env.example`.
3. ⛔ Configurar conexão MySQL local e pipeline de `migrate --seed`.
   - Bloqueado: não há projeto Laravel instalado no repositório (sem `artisan`, `composer.json` de app, migrations).
4. ⛔ Validar build frontend com Vite.
   - Bloqueado: não há `package.json`/Vite config do app.
5. ⛔ Publicar assets base do Volt Free.
   - Bloqueado: tema e estrutura Blade/Admin ainda não existem.
6. ⛔ Integrar estrutura inicial bootstrap-ecommerce na storefront.
   - Bloqueado: camada web/storefront ainda não foi scaffoldeada.

## Validação adicionada

- Script: `scripts/check_modulo_0_1.sh`
- Objetivo: impedir regressão no baseline de ambiente enquanto o scaffold Laravel não entra.

## Próxima ação recomendada (sem autoengano)

1. Inicializar o app Laravel no repositório (ou subir o código já existente).
2. Só depois marcar os itens 3-6 como done com evidência (comandos executados + arquivos reais).


## Execução prática adicionada

- Script de bootstrap real: `scripts/bootstrap_laravel_app.sh`
- Script de check prático do módulo: `scripts/check_modulo_0_1_pratico.sh`
- Log de tentativa/bloqueio: `docs/MODULO_0_1_EXECUCAO_PRATICA.md`
