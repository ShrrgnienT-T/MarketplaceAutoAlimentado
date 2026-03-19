# ADR-0006: Autenticação por sessão com middleware `auth` para área admin

- **Status:** Accepted
- **Date:** 2026-03-18
- **Deciders:** Time de engenharia
- **Technical Story:** controlar acesso real às rotas administrativas

## Context
Policies sem autenticação real deixam lacuna operacional: usuários anônimos ainda tentam acessar rotas sensíveis e a experiência de controle de acesso fica incompleta.

## Decision
Adotar autenticação por sessão no guard `web`, com rotas de login/logout e proteção de todas as rotas `/admin` via middleware `auth`.

## Alternatives considered
1. API token/JWT desde o início.
2. Login social antes do fluxo básico.
3. Sessão Laravel padrão para admin no MVP.

## Consequences
### Positivas
- Menor complexidade para iniciar com segurança básica.
- Integração direta com middleware e policies do Laravel.
- Fluxo claro de acesso/negação para usuários anônimos.

### Negativas / Trade-offs
- Menos adequado para APIs stateless públicas.
- Exige evolução futura para hardening (2FA, device management, etc.).

## Review trigger
Reavaliar quando:
- houver necessidade de API pública para apps terceiros,
- autenticação multifator entrar no escopo.
