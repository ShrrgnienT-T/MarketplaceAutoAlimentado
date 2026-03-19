# ADR-0004: RBAC inicial com roles admin/seller e Policies

- **Status:** Accepted
- **Date:** 2026-03-18
- **Deciders:** Time de engenharia
- **Technical Story:** mitigar risco operacional em review/publish

## Context
Sem autorização explícita, qualquer usuário autenticado pode revisar/publicar dados críticos de catálogo.

## Decision
Introduzir role base (`admin`, `seller`) e aplicar `Policies` para ações sensíveis de importação e catálogo.

## Alternatives considered
1. Autorizações ad-hoc em controllers.
2. RBAC completo com permissões granulares desde já.
3. RBAC mínimo + Policies no domínio crítico.

## Consequences
### Positivas
- Segurança operacional imediata.
- Centralização de regras de autorização.

### Negativas / Trade-offs
- Granularidade ainda limitada (apenas role).
- Exige evolução futura para permissões por recurso/tenant.

## Review trigger
Reavaliar quando houver:
- multi-tenant ativo,
- necessidade de delegação granular por seller/equipe.
