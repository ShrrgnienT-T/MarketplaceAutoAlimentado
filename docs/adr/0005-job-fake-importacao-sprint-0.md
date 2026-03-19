# ADR-0005: Job fake de importação no Sprint 0

- **Status:** Accepted
- **Date:** 2026-03-18
- **Deciders:** Time de engenharia + produto
- **Technical Story:** validar fluxo de negócio antes do parser final

## Context
Construir parser/OCR completo cedo demais aumenta risco de retrabalho sem validar fluxo operacional de revisão/publicação.

## Decision
Usar job fake para popular `import_items` no Sprint 0, priorizando validação de processo de negócio.

## Alternatives considered
1. Implementar parser final antes de validar fluxo.
2. Postergar totalmente importação.
3. Simular extração com job fake e validar operação ponta a ponta.

## Consequences
### Positivas
- Feedback rápido sobre UX operacional.
- Permite ajustar domínio antes de acoplar parser real.

### Negativas / Trade-offs
- Não mede precisão real de parsing.
- Pode gerar falsa sensação de “importação pronta” se não houver disciplina de roadmap.

## Review trigger
Substituir por parser real quando:
- fluxo de revisão/publicação estiver estável,
- fixtures de fornecedor estiverem definidos para teste de contrato.
