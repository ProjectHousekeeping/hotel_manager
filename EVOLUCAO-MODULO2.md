# Módulo 2 — Operações do Hotel · Execução do Plano de Evolução

Implementação do Plano de Evolução Arquitetural (item *c*) que ataca o smell
**CLS-03 — Ausência de abstração sobre Filament**, introduzindo os padrões
**Repository (P1)**, **Service/Action (P2)** e **Port sobre Filament (P3)**, sem
migração e sem alterar a operação para o usuário.

> Estado da execução: **código, testes e ferramentaria entregues.** As métricas
> que dependem de execução (cobertura, mutation score, ciclomática, Deptrac)
> **devem ser coletadas em ambiente com PHP/Composer** — não havia PHP instalado
> na máquina onde a refatoração foi escrita. Comandos prontos abaixo.

---

## 1. O que foi implementado

### Camada de Domínio (sem Filament, sem banco) — `app/Domain/Operacoes/`
| Arquivo | Padrão | Papel |
|---|---|---|
| `Enums/SituacaoQuarto.php` | Máquina de estados | Regra de transição de situação do quarto (antes implícita no form) |
| `Exceptions/TransicaoInvalidaException.php` | — | Erro de transição não permitida |
| `Exceptions/ConsumoInvalidoException.php` | — | Erro de consumo inválido |
| `Repositories/QuartoRepositoryInterface.php` | **P1 — Repository** | Porta de acesso a dados de Quarto |
| `Repositories/ItemRepositoryInterface.php` | **P1 — Repository** | Porta de acesso a dados de Item |
| `Actions/AtualizarSituacaoQuarto.php` | **P2 — Service/Action** | Transição de situação validada e persistida |
| `Actions/RegistrarConsumoItem.php` | **P2 — Service/Action** | Abate de estoque + cálculo de valor |
| `Actions/ConsumoRegistrado.php` | Value Object | Resultado imutável do consumo |

### Infraestrutura — `app/Infrastructure/Operacoes/Persistence/`
- `EloquentQuartoRepository.php`, `EloquentItemRepository.php` — implementam as portas.
- Binding em `app/Providers/AppServiceProvider.php` (`register()`).

### UI (P3 — thin wrappers) — `app/Filament/Resources/`
- `QuartoResource.php` — nova ação **"Alterar situação"** que só coleta a nova
  situação (restrita às transições válidas) e delega a `AtualizarSituacaoQuarto`.
- `QuartoResource/RelationManagers/ItensRelationManager.php` — nova ação
  **"Registrar consumo"** que delega a `RegistrarConsumoItem`.
- O CRUD existente foi mantido (não-disrupção).

### Testes — `tests/Unit/Operacoes/` e `tests/Support/Fakes/`
- `SituacaoQuartoTest.php` — máquina de estados (transições válidas/inválidas, alcance de todos os estados).
- `AtualizarSituacaoQuartoTest.php` — Action de situação com repositório fake.
- `RegistrarConsumoItemTest.php` — Action de consumo com repositório fake.
- `FakeQuartoRepository.php`, `FakeItemRepository.php` — repositórios em memória (sem Filament, sem DB).

### Mapeamento com o plano em fases
- **Fase 1** (isolar queries atrás de repositórios) → interfaces + Eloquent repos + binding.
- **Fase 2** (extrair transição e consumo para Actions) → `AtualizarSituacaoQuarto`, `RegistrarConsumoItem`.
- **Fase 3** (Resources só invocam Actions) → ações Filament como wrappers finos.

---

## 2. Mapa de estados implementado (`SituacaoQuarto`)

| De ↓ \ Para → | disponivel | ocupado | limpeza | manutencao | pedido | finalizada |
|---|---|---|---|---|---|---|
| **disponivel** | — | ✅ | ✅ | ✅ | — | — |
| **ocupado** | — | — | ✅ | ✅ | ✅ | — |
| **pedido_encaminhado** | — | ✅ | ✅ | ✅ | — | — |
| **manutencao_em_andamento** | — | — | ✅ | — | — | ✅ |
| **limpeza_em_andamento** | — | — | — | ✅ | — | ✅ |
| **finalizada** | ✅ | — | — | — | — | — |

> Proposta sujeita a validação da equipe (conforme o Plano de Evolução).

---

## 3. Protocolo de medição

### 3.1 Pré-requisitos (uma vez)
```bash
composer install
composer require --dev qossmic/deptrac infection/infection   # regenera o composer.lock
# Cobertura/mutation exigem Xdebug (mode=coverage) ou PCOV habilitado.
```

### 3.2 Baseline T0 (antes da refatoração) e meta

| ID | Métrica | Ferramenta | Baseline T0 | Meta | Como medir |
|---|---|---|---|---|---|
| **E3** | Classes de domínio que importam `Filament\` | Deptrac / grep | 100% (não havia domínio; regra vivia no Filament) | **0** | `composer metrics:deptrac` ou `grep -rl "use Filament" app/Domain` |
| **E5** | Cobertura das Actions | PHPUnit coverage | ≈ 0% | **≥ 70%** | `composer metrics:coverage` |
| **E6** | Transições de estado testáveis sem Filament | Inventário de Actions | 0 | **100%** | testes em `tests/Unit/Operacoes` rodam sem bootar Filament |
| **E9** | Estados de quarto cobertos por teste de transição | Testes de máquina de estados | 0 | **todos** | `SituacaoQuartoTest` cobre os 6 estados |
| **F1** | Esforço real vs estimado (28–44h) | Issues/PRs | — | registrar | controle de horas no board |
| **F2** | Complexidade ciclomática antes/depois | PHPMetrics/PHPMD | medir em T0 | reduzir na UI | `phpmd app text codesize` / `phpmetrics --report-html=build/metrics app` |
| **F5** | Regressões de situação pós-merge | Bugs reabertos | 0 | 0 | acompanhamento pós-merge |
| **F6** | Violações de fronteira (domínio→Filament) | Deptrac (gate CI) | — | **0** | `composer metrics:deptrac` |
| **F7** | Mutation score das Actions | Infection | — | MSI ≥ 70 / covered ≥ 80 | `composer metrics:infection` |

### 3.3 Comandos rápidos
```bash
composer test:unit          # roda a suíte Unit (Actions + máquina de estados)
composer metrics:deptrac    # E3/F6 — gate de fronteira (sai != 0 se violar)
composer metrics:coverage   # E5 — falha se cobertura < 70%
composer metrics:infection  # F7 — mutation score
```

### 3.4 Quando coletar (do plano)
- **T0:** baseline de E3/E5/E6/E9 e F2 **antes** da refatoração.
- **Por PR:** gates **E3** e **F6** bloqueantes no CI.
- **Ao concluir:** recoletar E5, E6, E9, F1, F2, F5, F7.

---

## 4. Gate de conclusão (Definition of Done)

Evoluído quando **E3 = 0**, **E5 ≥ 70%**, **E9** cobre todas as transições de
situação de quarto e **F6 = 0 violações** — com a operação de quartos/itens
**inalterada para o usuário**.

Checklist:
- [ ] `composer metrics:deptrac` → 0 violações (E3/F6)
- [ ] `composer metrics:coverage` → ≥ 70% nas Actions (E5)
- [ ] `SituacaoQuartoTest` verde, cobrindo os 6 estados (E9)
- [ ] `composer metrics:infection` → MSI ≥ 70 (F7)
- [ ] Quartos/itens operam como antes na UI (não-disrupção)

---

## 5. Observações
- A refatoração **não** altera o schema nem a UX: as telas de quarto/item seguem
  iguais; foram **adicionadas** ações que apenas mudam *onde a regra reside*.
- Para o gate de fronteira ser 100% efetivo no futuro, recomenda-se migrar
  gradualmente o `EditQuarto`/form para também passar pela Action (hoje convivem
  o form livre e a ação validada).
- Bug pré-existente fora do escopo: a migration `create_items_table` cria a
  tabela `items` mas o `down()` remove `itens`. Não tocado aqui.
