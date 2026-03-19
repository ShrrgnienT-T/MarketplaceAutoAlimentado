# ADR-0007: Adoção do Laravel Breeze como baseline de autenticação

- **Status:** Accepted
- **Date:** 2026-03-18
- **Deciders:** Time de engenharia
- **Technical Story:** reduzir código auth custom e aumentar aderência ao padrão Laravel

## Context
Manter autenticação custom para login/logout cedo demais aumenta custo de manutenção e risco de erro em fluxos sensíveis (reset de senha, confirmação de e-mail, updates de perfil).

## Decision
Adotar Laravel Breeze (stack Blade) como baseline de autenticação e perfil, mantendo as regras de domínio (RBAC/Policies) do projeto.

## Alternatives considered
1. Continuar com auth custom minimalista.
2. Implementar autenticação completa manualmente.
3. Usar Breeze e focar esforço em domínio de importação/catalog.

## Consequences
### Positivas
- Menor superfície de bug em autenticação.
- Melhora de produtividade com fluxo já padronizado.
- Facilidade para evoluir hardening (reset, confirmação e perfil).

### Negativas / Trade-offs
- Entrada de arquivos scaffold extras.
- Necessidade de alinhar rotas e testes existentes ao novo baseline.

## Review trigger
Reavaliar quando:
- houver necessidade de autenticação com requisitos enterprise (SSO, SAML, OIDC avançado),
- front-end migrar para SPA/API-first.
