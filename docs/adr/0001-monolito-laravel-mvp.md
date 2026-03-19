# ADR-0001: Monólito Laravel para MVP

- **Status:** Accepted
- **Date:** 2026-03-18
- **Deciders:** Time de engenharia
- **Technical Story:** entrega rápida do MVP de catálogo + importação

## Context
Precisamos reduzir time-to-market para validar operação de catálogo com importação PDF e revisão humana, sem dispersar esforço com infraestrutura distribuída cedo demais.

## Decision
Adotar arquitetura monolítica em Laravel para o MVP, com módulos internos (`Catalog`, `Import`, `Orders`, `Identity`) e fronteiras por Actions/Policies/Requests.

## Alternatives considered
1. Microserviços desde o início.
2. Backend em outro framework + frontend SPA complexa.
3. Monólito Laravel.

## Consequences
### Positivas
- Menor complexidade operacional no início.
- Ciclo de desenvolvimento e deploy mais rápido.
- Alinhamento com filosofia Laravel para produto em estágio inicial.

### Negativas / Trade-offs
- Escalabilidade e isolamento de falhas menores que microserviços.
- Exige disciplina modular para não virar “monólito espaguete”.

## Review trigger
Reavaliar quando houver:
- > 3 squads atuando em domínio distintos simultaneamente.
- gargalo claro de deploy/ownership por módulo.
