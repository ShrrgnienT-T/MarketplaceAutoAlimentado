# Roadmap atômico por épicos e módulos

Objetivo: transformar o roadmap do MVP em tarefas pequenas, rastreáveis e implementáveis sem overengineering.

## Princípios de execução
- Entregar valor de negócio antes de sofisticação técnica.
- Uma responsabilidade por PR.
- Toda tarefa crítica de domínio deve nascer com teste.
- Tudo que mexe em preço/estoque exige auditoria e revisão humana.

---

## Épico A — Fundação de plataforma

### Módulo Identity
- [ ] Instalar/auth bootstrap Laravel (Breeze/Jetstream, escolher 1 e congelar decisão).
- [ ] Criar roles base: `admin`, `seller`.
- [ ] Configurar middleware de autorização para rotas admin.
- [ ] Implementar políticas mínimas para produtos/importação.

### Módulo UI Base
- [ ] Criar layout `admin` com estrutura Volt.
- [ ] Criar layout `storefront` com bootstrap-ecommerce.
- [ ] Componentes Blade base: `alert`, `table`, `empty-state`, `form-field`.

### Módulo Infra MVP
- [ ] Configurar queue `database`.
- [ ] Configurar scheduler/worker local.
- [ ] Definir padrão de logs para importação.

**Critério de saída:** login + autorização funcionando e duas áreas (admin/storefront) navegáveis.

---

## Épico B — Catálogo manual confiável

### Módulo Catalog (dados)
- [ ] Migration `categories`.
- [ ] Migration `brands`.
- [ ] Migration `products` com constraints:
  - [ ] `sku` único.
  - [ ] `price` decimal positivo.
  - [ ] `stock` inteiro não negativo.
  - [ ] `status` enum (`draft`, `active`, `archived`).
  - [ ] `source` enum (`manual`, `pdf_import`).
- [ ] Migration `product_images`.

### Módulo Catalog (domínio)
- [ ] `CreateProductAction`.
- [ ] `UpdateProductAction`.
- [ ] `StoreProductRequest` + `UpdateProductRequest`.
- [ ] `ProductPolicy`.
- [ ] Controllers finos para CRUD.

### Módulo Catalog (UI)
- [ ] Listagem com paginação + busca por nome/SKU.
- [ ] Formulário create/edit com validação amigável.
- [ ] Upload de imagem com regras de tamanho/formato.

### Testes mínimos
- [ ] Feature: admin cria produto.
- [ ] Feature: seller sem permissão não publica produto de outro seller.
- [ ] Feature: valida SKU duplicado.

**Critério de saída:** operação consegue manter catálogo manual sem SQL manual e sem bypass de regra crítica.

---

## Épico C — Storefront + pedido pendente

### Módulo Storefront
- [ ] Página de vitrine com paginação.
- [ ] Busca textual por nome/SKU.
- [ ] Filtro por categoria/marca.
- [ ] Página de produto.

### Módulo Orders
- [ ] Migration `orders` e `order_items`.
- [ ] Carrinho em sessão.
- [ ] Checkout simples gerando `order.status = pending`.
- [ ] Admin atualiza status básico (`pending`, `approved`, `canceled`).

### Testes mínimos
- [ ] Feature: usuário adiciona ao carrinho e cria pedido.
- [ ] Feature: pedido aparece no admin.

**Critério de saída:** fluxo ponta a ponta de compra disponível para validação comercial.

---

## Épico D — Ingestão PDF com staging seguro

### Módulo Import (dados)
- [ ] Migration `supplier_templates`.
- [ ] Migration `import_batches`.
- [ ] Migration `import_items` com colunas:
  - [ ] `raw_extraction` (JSON)
  - [ ] `normalized_data` (JSON)
  - [ ] `errors` (JSON)
  - [ ] `status` (`pending`, `review`, `approved`, `rejected`, `published`)

### Módulo Import (fluxo)
- [ ] Tela upload PDF + fornecedor/template.
- [ ] `ProcessPdfImportBatchAction` cria batch e despacha job.
- [ ] `ExtractPdfBatchJob` extrai conteúdo bruto.
- [ ] `NormalizeImportItemsJob` aplica regras de normalização.
- [ ] `NormalizeImportItemAction` detecta conflitos e anomalies.

### Módulo Import (revisão)
- [ ] Tela de revisão do batch com filtros por erro.
- [ ] Aprovação/rejeição item a item.
- [ ] Ação em lote para aprovar/rejeitar.
- [ ] Sinalização de variação de preço anômala.

### Testes mínimos
- [ ] Feature: upload gera `import_batch` com status `uploaded`.
- [ ] Feature: processamento move batch para `review`.
- [ ] Unit: parser de preço BRL.
- [ ] Unit: normalização de SKU.
- [ ] Unit: regra de anomalia de preço.

**Critério de saída:** nenhum item importado toca `products` sem revisão explícita.

---

## Épico E — Publicação, auditoria e operação

### Módulo Publish
- [ ] `PublishImportBatchAction` transacional.
- [ ] Upsert idempotente por `supplier + sku`.
- [ ] Atualização seletiva de campos permitidos.
- [ ] Registro de resultado por item (publicado/falhou/motivo).

### Módulo Auditoria
- [ ] Tabela de auditoria de produto (preço, estoque, descrição).
- [ ] Registro de ator (usuário/job), antes/depois e origem da ação.

### Módulo Observabilidade
- [ ] Dashboard com:
  - [ ] taxa de sucesso por batch
  - [ ] % com erro
  - [ ] tempo médio de revisão
- [ ] Alertas básicos para batch `failed`.

### Testes mínimos
- [ ] Feature: publicar batch cria/atualiza produtos.
- [ ] Feature: reprocessar mesmo batch não duplica SKU.
- [ ] Feature: auditoria registra alteração de preço.

**Critério de saída:** operação diária confiável com visibilidade de gargalos.

---

## Decisões de arquitetura (gates obrigatórios)

Antes de abrir tarefas de OCR/IA, revisar:
- [ ] Taxa de acerto dos templates atuais por fornecedor.
- [ ] Custo de revisão humana por batch.
- [ ] Taxa de erro de preço pós-publicação.

Se os indicadores estiverem sob controle, avançar para otimização. Se não, corrigir base primeiro.

---

## Anti-overengineering checklist por PR

- [ ] Resolve dor atual mensurável?
- [ ] Mantém controller fino?
- [ ] Regra crítica está em Action/Request/Policy?
- [ ] Inclui teste de regressão para comportamento novo?
- [ ] Evita abstração prematura (interfaces genéricas sem segundo caso real)?

Se 2 ou mais respostas forem “não”, o PR deve ser replanejado.
