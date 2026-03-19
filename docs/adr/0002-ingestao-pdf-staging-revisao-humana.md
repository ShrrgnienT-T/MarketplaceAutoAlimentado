# ADR-0002: Ingestão PDF com staging + revisão humana obrigatória

- **Status:** Accepted
- **Date:** 2026-03-18
- **Deciders:** Time de produto + engenharia
- **Technical Story:** evitar publicação automática de dados inconsistentes

## Context
PDF de fornecedor é inerentemente inconsistente. Publicação direta em `products` cria risco de preço/estoque incorreto e impacto financeiro imediato.

## Decision
Toda importação deve passar por `import_batches` e `import_items`, com revisão humana antes de publicar em `products`.

## Alternatives considered
1. Publicação automática sem revisão.
2. OCR + IA antes de validar fluxo operacional.
3. Staging com revisão humana no MVP.

## Consequences
### Positivas
- Reduz risco de erro em produção.
- Permite rastreabilidade e auditoria futura.
- Gera feedback de qualidade de dados por fornecedor.

### Negativas / Trade-offs
- Maior custo operacional no curto prazo.
- Menor velocidade de publicação inicial.

## Review trigger
Reavaliar quando:
- taxa de acerto automática > 98% por fornecedor por 3 meses consecutivos,
- taxa de erro pós-publicação for estatisticamente baixa.
