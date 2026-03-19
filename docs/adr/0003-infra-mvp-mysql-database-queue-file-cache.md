# ADR-0003: Infra MVP com MySQL + database queue + file cache

- **Status:** Accepted
- **Date:** 2026-03-18
- **Deciders:** Time de engenharia
- **Technical Story:** reduzir custo de operação e setup no MVP

## Context
No estágio atual, otimização prematura de infraestrutura atrasa entrega de valor.

## Decision
Usar MySQL, fila em banco (`database`) e cache em arquivo (`file`) para o MVP.

## Alternatives considered
1. Redis + workers distribuídos desde o início.
2. Serviços gerenciados complexos no dia 1.
3. Infra mínima e pragmática para MVP.

## Consequences
### Positivas
- Setup simples e barato.
- Menos moving parts para operar.

### Negativas / Trade-offs
- Throughput menor para filas em alto volume.
- Latência de cache pior vs Redis.

## Review trigger
Migrar para Redis quando:
- backlog de fila for recorrente,
- SLA de processamento de batch for violado.
