# Módulo 0.1 — Execução prática (log real)

## O que foi tentado de verdade

1. `composer create-project laravel/laravel /tmp/maa_laravel --no-interaction`
2. `git clone --depth 1 https://github.com/laravel/laravel.git /tmp/laravel-skel`

## Resultado

Ambas as abordagens falharam por bloqueio de rede/proxy para repositórios externos (CONNECT tunnel 403).

## O que foi entregue mesmo assim (prático e executável)

- Script de bootstrap real para quando rede estiver liberada:
  - `scripts/bootstrap_laravel_app.sh`
- Script de verificação prática do módulo 0.1:
  - `scripts/check_modulo_0_1_pratico.sh`

## Como executar quando liberar acesso externo

1. `./scripts/bootstrap_laravel_app.sh`
2. `cp .env.example .env`
3. `php artisan key:generate`
4. `php artisan migrate --seed`
5. `npm run build` (ou `npm run dev`)
6. `php artisan serve`

## Confronto necessário (sem conforto)

Sem resolver acesso a pacotes externos, insistir em “subir Laravel agora” gera teatro de produtividade.
A ordem certa é: liberar rede de build → bootstrap app → validar cada check do módulo 0.1 com evidência.

## Evidência de bloqueio no ambiente atual

Execução em `./scripts/bootstrap_laravel_app.sh` parou no passo `composer create-project` com erro:

`curl error 56 ... CONNECT tunnel failed, response 403`.
