# ADR (Architecture Decision Records)

Este diretório mantém o histórico das decisões arquiteturais e de produto técnico do projeto.

## Como usar

1. Crie um novo ADR com numeração sequencial (`NNNN-titulo-curto.md`).
2. Nunca edite decisões antigas para "reescrever história". Se mudou de ideia, crie um novo ADR que **supersede** o anterior.
3. Toda decisão que impacte domínio, segurança, custo operacional ou escalabilidade deve virar ADR.

## Status possíveis
- `Proposed`
- `Accepted`
- `Deprecated`
- `Superseded by ADR-XXXX`

## Índice
- [ADR-0000 — Template](0000-template.md)
- [ADR-0001 — Monólito Laravel para MVP](0001-monolito-laravel-mvp.md)
- [ADR-0002 — Ingestão PDF com staging + revisão humana obrigatória](0002-ingestao-pdf-staging-revisao-humana.md)
- [ADR-0003 — Infra MVP: MySQL + database queue + file cache](0003-infra-mvp-mysql-database-queue-file-cache.md)
- [ADR-0004 — RBAC inicial com roles admin/seller e Policies](0004-rbac-admin-seller-policies.md)
- [ADR-0005 — Job fake de importação no Sprint 0](0005-job-fake-importacao-sprint-0.md)
- [ADR-0006 — Autenticação por sessão + middleware auth no admin](0006-autenticacao-sessao-middleware-auth.md)
- [ADR-0007 — Adoção do Laravel Breeze como baseline de auth](0007-adocao-laravel-breeze-auth-baseline.md)
- [ADR-0008 — Governança de publicação e auditoria de importação](0008-governanca-publicacao-auditoria-import.md)

## Critério de qualidade mínimo por ADR
- Contexto e problema explícitos.
- Alternativas consideradas (incluindo a que foi rejeitada).
- Consequências de curto e longo prazo (trade-offs reais).
- Plano de revisão (gatilho de reavaliação).
