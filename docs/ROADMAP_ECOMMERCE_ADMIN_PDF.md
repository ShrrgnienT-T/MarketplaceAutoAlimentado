# Roadmap mão na massa — E-commerce + Admin com ingestão de catálogo PDF

Este roadmap é **prático, incremental e sem overengineering**, seguindo filosofia Laravel: começar simples, com boa base de domínio, e escalar por etapas.

## 0) Primeiro choque de realidade (antes de codar)

Se você quer automatizar cadastro via PDF, a pergunta certa não é “como extrair texto do PDF?”, e sim:

1. **Seu fornecedor envia PDF consistente?** (mesmo layout, mesma ordem de campos, mesmas unidades?)
2. **Qual tolerância de erro você aceita?** (1% de erro em preço já quebra margem)
3. **Quem é o dono da validação final?** (humano no loop ou publicação direta?)

> Se você ignorar isso, vai construir parser caro para dados ruins.

**Decisão recomendada para MVP:**
- Pipeline com **revisão humana obrigatória** antes de publicar produto.
- Suporte inicial a **1-2 formatos de PDF de fornecedor** (não “qualquer PDF”).
- Publicação em lote com checklist de qualidade.

---

## 1) Objetivo de produto (MVP realista)

Entregar 3 fluxos principais:

1. **Gestão manual de catálogo** (CRUD produto) no Admin (Volt Free).
2. **Loja (bootstrap-ecommerce)** com busca/filtro/carrinho/pedido pendente.
3. **Importação por PDF** com staging + revisão + publicação.

### Critérios de sucesso do MVP
- Cadastrar e publicar produto manualmente em < 2 min.
- Importar um PDF de fornecedor e gerar sugestões com pelo menos 80% de campos preenchidos automaticamente.
- Reprovar/publicar itens em lote sem quebrar catálogo existente.

---

## 2) Arquitetura enxuta (Laravel way)

## Módulos (sem microserviço, sem loucura)

- `Catalog` (categorias, marcas, produtos, variações, mídia)
- `Import` (upload PDF, extração, staging, revisão, publicação)
- `Orders` (carrinho, pedido, status)
- `Identity` (usuário, papéis, permissões)

### Padrão de código
- Controllers finos.
- Regras em `Actions` (`app/Actions/...`).
- Validação em `FormRequest`.
- Autorização com `Policies`.
- Jobs para processamentos pesados de PDF.

### Tabela de decisão técnica (MVP)
- Banco: MySQL
- Fila: database queue
- Cache: file
- OCR (só se necessário): Tesseract/serviço externo por feature flag

---

## 3) Modelo de dados mínimo (MVP)

## Catálogo
- `categories`
- `brands`
- `products`
- `product_variants` (opcional no início; se não houver variação, manter simples)
- `product_images`

Campos essenciais de produto:
- `sku` (único)
- `name`
- `slug`
- `description`
- `price`
- `cost` (se você quer margem)
- `stock`
- `status` (`draft`, `active`, `archived`)
- `source` (`manual`, `pdf_import`)

## Importação
- `import_batches` (arquivo, fornecedor, status, métricas)
- `import_items` (dados extraídos + dados normalizados + erros)
- `supplier_templates` (regras por fornecedor: regex, posições, colunas esperadas)

Estados de batch:
- `uploaded` → `processing` → `review` → `published` / `failed`

---

## 4) Roadmap por fases (8-12 semanas)

## Fase 1 — Fundação (Semana 1-2)

### Entregas
- Autenticação + RBAC (admin/seller).
- Layout Admin com Volt Free e componentes base.
- Layout Storefront com bootstrap-ecommerce.
- Migrations de catálogo inicial.

### Definition of Done
- Admin protegido por permissão.
- Página de listagem de produtos com paginação e busca simples.
- Componentes Blade reutilizáveis (tabela, form, alert).

---

## Fase 2 — Catálogo manual sólido (Semana 3-4)

### Entregas
- CRUD completo de produto/categoria/marca.
- Upload de imagem com validação.
- Regras de negócio: SKU único, preço > 0, estoque >= 0.

### Definition of Done
- Seller consegue criar/editar/publicar produto manualmente.
- Validação e autorização centralizadas.
- Testes feature para fluxos críticos de CRUD.

---

## Fase 3 — Loja + pedido MVP (Semana 5-6)

### Entregas
- Vitrine com busca, filtro por categoria e página de produto.
- Carrinho em sessão.
- Checkout simples gerando `order` com status `pending`.

### Definition of Done
- Usuário cria pedido de ponta a ponta.
- Admin enxerga pedidos e atualiza status básico.

---

## Fase 4 — Ingestão PDF com staging (Semana 7-9)

### Entregas
- Upload de PDF por fornecedor.
- Job assíncrono de extração/parsing.
- Persistência em `import_items` (não publica direto em `products`).
- Tela de revisão com:
  - campos obrigatórios faltantes
  - conflitos de SKU
  - variações de preço suspeitas (ex.: +300%)

### Definition of Done
- Importação nunca sobrescreve produção sem revisão.
- Usuário consegue aprovar/rejeitar itens individualmente e em lote.

### Estratégia anti-fragilidade
- Versão de parser por fornecedor (`template_version`).
- Logs de parsing por item.
- Reprocessamento de batch sem duplicar produto (idempotência por SKU+fornecedor).

---

## Fase 5 — Publicação, observabilidade e hardening (Semana 10-12)

### Entregas
- Publicação em lote `import_items -> products` via Action transacional.
- Auditoria: quem alterou preço, estoque e descrição.
- Dashboard operacional:
  - taxa de sucesso por importação
  - % de itens com erro
  - tempo médio de revisão

### Definition of Done
- Fluxo confiável para operação diária.
- Métricas mínimas para identificar gargalos de catálogo.

---

## 5) Backlog priorizado (ordem real)

1. Auth + RBAC
2. CRUD de catálogo manual
3. Storefront + pedido pending
4. Upload PDF + batch processing
5. Normalização e validação de itens importados
6. Tela de revisão humana
7. Publicação em lote com idempotência
8. Observabilidade e auditoria
9. Otimizações (cache, fila Redis, OCR melhor)

> Se você inverter essa ordem e começar por OCR/IA, vai atrasar receita e feedback real.

---

## 6) Decisões difíceis que você precisa tomar agora

1. **Single-tenant agora ou multi-tenant já no início?**
   - Recomendação: single-tenant com colunas preparadas para `seller_id` e policies bem feitas.
2. **Variação de produto no MVP?**
   - Se seu catálogo já nasce com tamanho/cor, inclua `product_variants` cedo.
3. **Preço automático do PDF publica sozinho?**
   - Recomendação: não no MVP. Exigir aprovação humana.
4. **Confiar em OCR para PDF escaneado?**
   - Só depois de medir taxa de erro por fornecedor.

---

## 7) Esqueleto técnico (mão na massa)

### Rotas sugeridas
- `routes/admin.php`
  - `/products`
  - `/imports`
  - `/imports/{batch}`
  - `/imports/{batch}/publish`

### Actions sugeridas
- `CreateProductAction`
- `UpdateProductAction`
- `ProcessPdfImportBatchAction`
- `NormalizeImportItemAction`
- `PublishImportBatchAction`

### Jobs sugeridos
- `ExtractPdfBatchJob`
- `NormalizeImportItemsJob`

### Policies
- `ProductPolicy`
- `ImportBatchPolicy`

---

## 8) Testes essenciais (sem desculpa)

### Feature tests
- CRUD produto (incluindo autorização).
- Upload de PDF cria `import_batch`.
- Publicação de batch cria/atualiza produtos corretamente.

### Unit tests
- Normalização de SKU.
- Parser de preço (R$ 1.299,90 -> 1299.90).
- Regras de conflito (SKU duplicado, preço anômalo).

### Teste de contrato de parser
- Dado um PDF fixture do fornecedor X, saída esperada em JSON.
- Isso evita regressão silenciosa quando mexer no parser.

---

## 9) Riscos reais (e mitigação)

1. **PDF inconsistente entre fornecedores**
   - Mitigar com templates por fornecedor e fallback manual.
2. **Erros de preço/estoque em massa**
   - Mitigar com staging + aprovação + auditoria.
3. **Tempo alto de processamento**
   - Mitigar com fila e processamento incremental por páginas.
4. **Acoplamento forte entre parser e domínio**
   - Mitigar separando `raw_extraction` de `normalized_data`.

---

## 10) Métricas de produto/operação

- Tempo médio para publicar catálogo de um PDF.
- % de itens importados sem intervenção.
- % de itens rejeitados por erro de dado.
- Erro de preço detectado pós-publicação.
- SLA do processamento de batch.

Se você não medir isso, “automação” vira só sensação.

---

## 11) Próximo passo imediato (7 dias)

**Sprint 0 pragmática:**
1. Modelar tabelas `products`, `import_batches`, `import_items`.
2. Criar telas admin mínimas (`/products` e `/imports`).
3. Implementar upload de PDF + criação de batch.
4. Implementar Job fake (sem parser complexo) que popula itens simulados.
5. Construir tela de revisão e publicação manual.

> Isso te força a validar fluxo de negócio antes de investir em parsing avançado.


## 12) Quebra atômica por épicos e módulos

Para execução detalhada task-a-task (micro tasks), consulte `docs/ROADMAP_ATOMICO_EPICOS_MODULOS.md`.
