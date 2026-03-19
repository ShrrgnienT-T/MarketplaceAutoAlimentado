# Próximo passo (execução pragmática em 10 dias)

Objetivo: sair de “scaffold funcional” para “operação minimamente confiável” sem overengineering.

## Diagnóstico duro (onde está o risco hoje)

1. **Você já tem autenticação, mas não hardening suficiente.**
   - Falta proteção explícita de brute force por rota crítica de admin além do login padrão.
   - Falta trilha de auditoria para ações sensíveis (`approve`, `reject`, `publish`).
2. **Você já tem staging, mas ainda falta governança de publicação.**
   - Não há checklist de “pronto para publicar batch”.
   - Não há rastreio de “quem publicou o quê e quando” em nível de item/produto.
3. **Você tem testes de fluxo, mas falta teste de regressão de domínio.**
   - Sem teste de contrato para parser/template por fornecedor.

---

## Sprint recomendado (ordem obrigatória)

## Bloco A — Segurança operacional (Dias 1-3)

### Entregas
- Rate limit explícito em endpoints de auth sensíveis.
- Auditoria mínima de ações críticas:
  - aprovar item
  - rejeitar item
  - publicar batch
- Registro de ator (`user_id`), timestamp e before/after de campos críticos.

### DoD
- Toda ação crítica deixa rastro consultável.
- Fluxos continuam passando em testes feature.

---

## Bloco B — Qualidade de publicação (Dias 4-6)

### Entregas
- Regra de “batch publicável”:
  - sem itens `review` pendentes
  - sem erros bloqueantes
- Bloqueio de publish quando regra não for atendida.
- Feedback claro na UI de revisão.

### DoD
- Não existe publish “cego”.
- Teste feature cobrindo bloqueio e sucesso.

---

## Bloco C — Observabilidade mínima (Dias 7-8)

### Entregas
- Métricas no painel admin (simples):
  - total importado
  - % aprovado/rejeitado
  - tempo de revisão por batch

### DoD
- Operação consegue identificar gargalo sem SQL manual.

---

## Bloco D — Contrato de parser (Dias 9-10)

### Entregas
- Teste de contrato por fornecedor com fixture versionada.
- Estrutura `supplier_templates` conectada ao pipeline fake (pré-parser real).

### DoD
- Mudança no parser/template quebra teste quando regressão ocorre.

---

## O que **não** fazer agora (para não sabotar o projeto)

- Não abrir multi-tenant real ainda.
- Não investir em OCR avançado sem baseline de erro por fornecedor.
- Não migrar para microserviço antes de provar gargalo real.

---

## Decisão de liderança (que você precisa tomar hoje)

Escolha **uma** métrica de sucesso para a próxima sprint:

1. "Zero publicação sem auditoria"
2. "% de batchs publicados sem intervenção manual"
3. "Tempo médio da revisão por batch"

Sem essa escolha, você vai otimizar tudo e melhorar pouco.
