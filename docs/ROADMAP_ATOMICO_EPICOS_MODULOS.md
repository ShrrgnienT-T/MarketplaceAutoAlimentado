# Roteiro atômico por épicos e módulos — go-live do zero

> Objetivo: transformar a visão do produto em execução diária, com tarefas pequenas, rastreáveis e prontas para Kanban/Sprint.

## Premissas duras (para evitar autoengano)

Antes de abrir task técnica, responda com **sim/não**:

1. Você aceita revisão humana obrigatória na importação PDF no MVP?
2. Você vai limitar MVP para 1-2 fornecedores com template fixo?
3. Você aceita checkout `pending` (sem gateway full) para validar operação primeiro?
4. Você vai adiar multi-tenant real para fase 2?

Se qualquer resposta for “não”, seu prazo dobra. O risco não é técnico; é escopo.

---

## Estratégia de execução (BigTech sem overengineering)

- **Granularidade:** cada micro task precisa caber em 2–6 horas.
- **DOD por task:** código + teste + migração segura + autorização + observabilidade mínima.
- **WIP limite:** máximo 1 épico in-progress por squad pequeno (1-3 devs).
- **Definition of Ready:** task só entra em sprint com critérios de aceite e risco mapeado.

---

## Épico 0 — Foundation & Delivery Engine

## Módulo 0.1 — Setup e baseline
- [x] Criar `.env.example` consistente com filas/cache locais.
- [x] Definir timezone, locale e moeda padrão (`pt_BR`, `BRL`).
- [ ] Configurar conexão MySQL local e pipeline de `migrate --seed`.
- [ ] Validar build frontend com Vite.
- [ ] Publicar assets base do Volt Free.
- [ ] Integrar estrutura inicial bootstrap-ecommerce na storefront.

Status detalhado e bloqueios atuais: `docs/MODULO_0_1_STATUS.md`.
Execução prática e comando de bootstrap: `docs/MODULO_0_1_EXECUCAO_PRATICA.md`.

## Módulo 0.2 — Qualidade e padrão
- [ ] Configurar Laravel Pint com regras mínimas.
- [ ] Criar workflow CI com `composer test` + lint.
- [ ] Adicionar `CONTRIBUTING.md` com padrão branch/PR/commit.
- [ ] Configurar Conventional Commits no time.
- [ ] Definir template de PR com checklist de segurança e rollback.

## Módulo 0.3 — Observabilidade mínima
- [ ] Definir padrão de logs estruturados por contexto (`batch_id`, `seller_id`).
- [ ] Configurar canal de log para importação.
- [ ] Criar endpoint interno de healthcheck (`/up` + DB ping opcional).

**Marco de saída do épico:** ambiente sobe do zero em uma máquina limpa em <30 min.

---

## Épico 1 — Identity, Auth e Governança de Acesso

## Módulo 1.1 — Autenticação
- [ ] Instalar/ajustar auth Laravel para `admin` e `seller`.
- [ ] Criar fluxo de login/logout no painel admin.
- [ ] Implementar reset de senha com token padrão Laravel.
- [ ] Configurar proteção contra brute-force (rate limit login).

## Módulo 1.2 — RBAC
- [ ] Definir matriz de permissões (admin/seller).
- [ ] Criar seeds de roles e permissions.
- [ ] Associar permissões a menus do admin.
- [ ] Criar middleware de permissão por área.

## Módulo 1.3 — Policies
- [ ] Criar `ProductPolicy` com escopo por seller.
- [ ] Criar `ImportBatchPolicy` com escopo por seller.
- [ ] Cobrir tentativas de acesso indevido com testes feature.

**Marco de saída do épico:** nenhum endpoint sensível funciona sem policy/middleware.

---

## Épico 2 — Núcleo de Catálogo (manual)

## Módulo 2.1 — Modelagem de dados
- [ ] Criar migration `categories`.
- [ ] Criar migration `brands`.
- [ ] Criar migration `products` com campos mínimos (sku, slug, status, source).
- [ ] Criar índices: `sku` único, `status`, `seller_id`.
- [ ] Criar migration `product_images`.
- [ ] (Condicional) Criar `product_variants` se variação for requisito já no MVP.

## Módulo 2.2 — CRUD Admin
- [ ] Tela de listagem com paginação e busca por nome/SKU.
- [ ] Tela de criação com validação via FormRequest.
- [ ] Tela de edição com bloqueio concorrente básico (updated_at guard).
- [ ] Soft delete/arquivamento de produto.
- [ ] Ação de ativar/desativar em lote.

## Módulo 2.3 — Mídia e SEO básico
- [ ] Upload de imagem com tamanho/formato limitados.
- [ ] Geração de slug com fallback manual.
- [ ] Definir imagem principal vs galeria.
- [ ] Placeholder visual para produto sem imagem.

## Módulo 2.4 — Regras de negócio críticas
- [ ] Validar `price > 0` e `stock >= 0`.
- [ ] Garantir SKU único por seller.
- [ ] Bloquear publicação de produto sem campos obrigatórios.
- [ ] Registrar auditoria de mudança de preço.

**Marco de saída do épico:** seller publica produto manualmente sem suporte técnico.

---

## Épico 3 — Storefront e Jornada de Compra MVP

## Módulo 3.1 — Catálogo público
- [ ] Home com vitrines mínimas (recentes/destaques).
- [ ] Página de categoria com paginação.
- [ ] Busca textual simples (nome + SKU opcional).
- [ ] Filtro por faixa de preço e categoria.

## Módulo 3.2 — PDP (product detail page)
- [ ] Exibir preço, estoque e descrição.
- [ ] Exibir galeria de imagens.
- [ ] Estado visual para indisponível/sem estoque.

## Módulo 3.3 — Carrinho e checkout pending
- [ ] Carrinho em sessão (adicionar/remover/atualizar quantidade).
- [ ] Validação de estoque no checkout.
- [ ] Criar pedido com status inicial `pending`.
- [ ] Tela de confirmação com número do pedido.

## Módulo 3.4 — Backoffice de pedidos
- [ ] Listagem de pedidos no admin por seller.
- [ ] Visualização detalhada de itens/endereço.
- [ ] Transição de status controlada por regra.

**Marco de saída do épico:** cliente fecha pedido fim-a-fim sem gateway de pagamento.

---

## Épico 4 — Importação de Catálogo via PDF (staging-first)

## Módulo 4.1 — Ingestão e batch
- [ ] Criar migration `import_batches`.
- [ ] Criar migration `import_items`.
- [ ] Criar migration `supplier_templates`.
- [ ] Upload de PDF com validação de tipo/tamanho.
- [ ] Persistir metadados do arquivo (hash, nome, fornecedor).
- [ ] Enfileirar processamento assíncrono por batch.

## Módulo 4.2 — Parsing inicial
- [ ] Implementar extractor base para PDF digital (texto embutido).
- [ ] Estruturar saída `raw_extraction` (json).
- [ ] Normalizar campos para `normalized_data` (json).
- [ ] Marcar `error_codes` por item (campos ausentes, preço inválido etc.).
- [ ] Salvar versão do template/parsers usada no batch.

## Módulo 4.3 — Normalização e validação
- [ ] Normalizar moeda BR (`R$ 1.299,90 -> 1299.90`).
- [ ] Normalizar SKU (trim, uppercase, remoção de ruído).
- [ ] Detectar conflito de SKU existente.
- [ ] Detectar anomalia de preço vs última versão.
- [ ] Classificar item: `ready`, `needs_review`, `rejected`.

## Módulo 4.4 — UI de revisão humana
- [ ] Lista de itens por status com filtros.
- [ ] Editor inline de campos críticos (nome, sku, preço, estoque).
- [ ] Ações em lote: aprovar, rejeitar, marcar para revisão.
- [ ] Painel de erros por item com explicação objetiva.

## Módulo 4.5 — Publicação controlada
- [ ] Action transacional para `publish approved`.
- [ ] Idempotência por `seller_id + supplier + sku`.
- [ ] Estratégia de upsert segura (não sobrescrever sem regra explícita).
- [ ] Log de alterações por item publicado.

**Marco de saída do épico:** PDF gera catálogo publicável sem tocar produção diretamente.

---

## Épico 5 — Operação, Confiabilidade e Segurança

## Módulo 5.1 — Resiliência
- [ ] Retries e backoff em jobs de importação.
- [ ] Dead-letter handling para batches falhos.
- [ ] Comando para reprocessar batch com rastreabilidade.

## Módulo 5.2 — Segurança de aplicação
- [ ] Sanitizar upload e bloquear extensões inválidas.
- [ ] Garantir autorização em todos endpoints admin/import.
- [ ] Revisar mass assignment em models sensíveis.
- [ ] Validar proteção CSRF e rate limiting rotas críticas.

## Módulo 5.3 — Auditoria e trilha
- [ ] Tabela/log para alterações de preço/estoque.
- [ ] Registrar ator (`user_id`) e origem (`manual`/`pdf_import`).
- [ ] Histórico por produto acessível no admin.

## Módulo 5.4 — Métricas operacionais
- [ ] Métrica de taxa de sucesso por batch.
- [ ] Métrica de tempo médio de revisão.
- [ ] Métrica de % itens auto-aprovados.
- [ ] Alerta para batch preso em `processing`.

**Marco de saída do épico:** operação confia no sistema sem planilha paralela.

---

## Épico 6 — Go-live e Pós-go-live

## Módulo 6.1 — Preparação de produção
- [ ] Configurar `.env` de produção (APP_ENV, APP_DEBUG=false, cache/queue).
- [ ] Rodar migrations com plano de rollback.
- [ ] Configurar storage público/privado para mídia e PDFs.
- [ ] Configurar scheduler/queue worker (supervisor/systemd).

## Módulo 6.2 — Runbook
- [ ] Criar runbook de incidente (import falhou, fila parada, erro de preço).
- [ ] Criar checklist diário de operação.
- [ ] Definir SLA de processamento de lote.

## Módulo 6.3 — Hipercare (primeiros 14 dias)
- [ ] War-room leve com revisão diária de métricas.
- [ ] Corrigir top 5 causas de rejeição de import item.
- [ ] Validar se volume real exige Redis/OCR melhor.

**Marco de saída do épico:** sistema estável, operação previsível e plano de evolução claro.

---

## Dependências entre épicos (ordem recomendada)

1. Épico 0 → 1 (sem baseline, autenticação vira retrabalho)
2. Épico 1 → 2 (sem RBAC, catálogo nasce inseguro)
3. Épico 2 → 3 (sem catálogo sólido, storefront quebra)
4. Épico 2 + 1 → 4 (import sem governança vira incidente)
5. Épico 4 → 5 (otimizar depois de funcionar)
6. Épico 5 → 6 (go-live sem runbook é roleta)

---

## Plano de sprints (12 semanas, referência)

- **Sprint 1-2:** Épico 0 + Épico 1
- **Sprint 3-4:** Épico 2
- **Sprint 5-6:** Épico 3
- **Sprint 7-9:** Épico 4
- **Sprint 10-11:** Épico 5
- **Sprint 12:** Épico 6 + hardening final

---

## Checklist final de go-live (bloqueadores)

- [ ] Nenhuma rota admin sem policy/middleware testado.
- [ ] Nenhuma publicação de import sem revisão humana no MVP.
- [ ] Testes feature críticos verdes (catalogo, pedido, import).
- [ ] Log/auditoria de preço habilitado.
- [ ] Backup e estratégia de rollback validados.
- [ ] Monitoramento de fila e erro de batch ativo.

Se qualquer item acima estiver pendente, você não está pronto para produção — está apenas esperançoso.
