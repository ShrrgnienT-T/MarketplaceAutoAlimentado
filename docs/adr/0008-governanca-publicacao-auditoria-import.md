# ADR-0008: Governança de publicação com bloqueio de batch e trilha de auditoria

- **Status:** Accepted
- **Date:** 2026-03-19
- **Deciders:** Time de engenharia
- **Technical Story:** reduzir risco operacional em publicação de catálogo

## Context
Publicar batch com itens pendentes de revisão ou sem trilha de auditoria cria risco financeiro e dificulta investigação de incidentes.

## Decision
1. Bloquear publicação quando existir item `review` pendente ou item aprovado com erro bloqueante.
2. Registrar eventos de auditoria para `import_item.approved`, `import_item.rejected` e `import_batch.published`.

## Alternatives considered
1. Permitir publish parcial sem bloqueio rígido.
2. Auditoria apenas em logs de aplicação.
3. Regras de bloqueio + tabela de auditoria transacional.

## Consequences
### Positivas
- Evita publicação cega.
- Melhora rastreabilidade para suporte e compliance operacional.

### Negativas / Trade-offs
- Fluxo de publicação fica mais rígido.
- Demanda manutenção de eventos de auditoria ao evoluir domínio.

## Review trigger
Reavaliar quando:
- houver fluxo avançado de aprovação em lote com exceções controladas,
- auditoria for movida para mecanismo centralizado de observabilidade.
