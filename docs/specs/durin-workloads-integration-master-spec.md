# Durin Ecosystem — Workloads Integration Master Spec

**Status:** **in progress** — integração transversal validada em partes; gaps de **distribuição de pacotes** e **E2E hardening** ainda abertos.  
**Fonte canônica:** `EreborCodeForge/durin-architecture` → `docs/specs/durin-workloads-integration-master-spec.md`  
**Última revisão de fases:** 2026-09-29

# Mission

Coordenar a evolução de workloads entre os repositórios do ecossistema Durin. Esta spec é **transversal**; cada repositório mantém sua spec de implementação local (ver índice abaixo).

```text
durin-architecture   ← governa visão transversal (este documento)
durin-core
durin-presets
durins-forge
durin-app
durin-installer
mithrilphp
eregion
```

`durin-architecture` hospeda a master spec porque centraliza contratos de arquitetura e planejamento sem ser dono de runtime, preset catalog ou CLI.

# Spec index (implementação por repo)

| Repositório | Documento local |
|-------------|-----------------|
| `durin-core` | `docs/durin-core-runtime-model-spec.md` |
| `durin-presets` | `docs/runtime-requirements-spec.md` |
| `durins-forge` | `docs/runtime-resolution-spec.md`, `docs/workloads-integration.md` (referência) |
| `mithrilphp` | `docs/job-runtime-spec.md` |
| `eregion` | `docs/workloads-spec.md` |

# Responsibility Map

```text
durin-presets
→ declara intenção/capabilities

durins-forge
→ resolve execução + supervisor

durin-core
→ modela manifest/runtime de forma neutra

durin-app
→ bootstrap neutro

durin-installer
→ orquestra criação

mithrilphp
→ executa HTTP/jobs persistentemente

eregion
→ supervisiona e escala workloads

broker
→ mantém backlog e semântica de entrega
```

# Target Flow — HTTP

```text
minimal/service
  ↓
durin-presets
requires persistent-http
  ↓
Forge
execution=mithril-http
supervisor=eregion
  ↓
Eregion HTTP workload
  ↓
Mithril persistent HTTP Worker
```

# Target Flow — Worker Standalone

```text
worker
  ↓
durin-presets
requires job-loop + messaging
  ↓
Forge
execution=mithril-job
supervisor=null
  ↓
php vendor/bin/job-worker
  ↓
JobTransport
  ↓
broker
```

# Target Flow — Worker Supervisionado

```text
worker
  ↓
Forge
execution=mithril-job
supervisor=eregion
  ↓
Eregion consumer workload
  ↓
N x php vendor/bin/job-worker
  ↓
JobTransport
  ↓
broker
```

# Subjobs

Correto:

```text
Worker A
  ↓
JobDispatcher
  ↓
broker
  ↓
Workload B
  ↓
N workers
```

Incorreto:

```text
Worker A
  ↓
spawn Worker B
```

A escala pertence ao Eregion/pool, não ao job handler.

# Implementation Order

## Phase 1 — Runtime Neutrality

| Pacote | Status |
|--------|--------|
| durin-core | **DONE** |
| durin-presets | **DONE** |
| durins-forge | **DONE** |
| durin-app | **DONE** |
| durin-installer | **DONE** |

Resultado esperado:

```text
worker → Mithril JobWorker sem Eregion obrigatório
minimal/service → HTTP com Eregion (via resolução Forge, não defaults no Core)
```

Detalhes: `durin-core/docs/durin-core-runtime-model-spec.md`, `durin-presets/docs/runtime-requirements-spec.md`, `durins-forge/docs/runtime-resolution-spec.md`.

## Phase 2 — Mithril job runtime — **implemented** / hardening residual

Core do job worker persistente publicado (`mithrilphp` v3.0.0):

```text
idle != stop
graceful drain
max_jobs recycle
metrics observer
JobDispatcher
```

**Residual:** observabilidade operacional, documentação de borda, e alinhamento contínuo com consumidores Forge/presets conforme releases.

Spec local: `mithrilphp/docs/job-runtime-spec.md`.

## Phase 3 — Eregion workloads — **DONE**

Capacidades baseline entregues no Eregion (v0.4.0+):

```text
WorkloadSpec
WorkloadTemplate
ResolvedWorkloadSpec
WorkloadRegistry
Reconciler
multiple WorkerPool
http mode
consumer mode
```

Spec local: `eregion/docs/workloads-spec.md`.

## Phase 4 — Dynamic scaling — **PARTIAL**

Presente no Eregion em baseline; integração e validação Durin ponta a ponta **não** fechadas para todos os cenários de produção:

```text
min/max
min=0
backlog strategy
resource clamp
scale-up cooldown
scale-down idle delay
graceful drain
```

Gaps típicos: matriz completa de políticas vs. presets Forge, doctor/UX, e smoke E2E de escala sob carga real.

## Phase 5 — Forge + Eregion consumer integration — **implemented** / E2E hardening pending

Implementado em `durins-forge` (v0.4.0+): resolução consumer-first, configuração de workloads, doctor alinhado, `durin run` por `execution`/`supervisor`.

**Pending:** hardening E2E contínuo (distribuição Packagist/path repos, matriz de launchers, smoke em CI multi-OS), referência `scripts/workloads-integration-smoke.sh` no monorepo de desenvolvimento.

# Versioning

Não reescrever releases 0.1.x existentes.

Cada repo deve fazer release compatível com SemVer conforme o tamanho da quebra pública.

Mudanças de manifest/contratos públicos devem ser tratadas como migration explícita e possuir leitura de formato legado quando viável.

# Cross-Repository Rules

- presets não instalam runtime;
- Core não conhece IDs de preset para decidir runtime;
- installer não escolhe runtime;
- Forge não implementa scheduler;
- Eregion não implementa domínio/broker obrigatório;
- Mithril não exige Eregion;
- Job handlers não criam processos;
- broker continua sendo fonte de backlog;
- Eregion controla capacidade;
- Mithril controla unidade de execução.

# End-to-End Example — MQTT

```text
durin new telemetry --preset=worker
  ↓
worker preset
  ↓
mithril-job
  ↓
opcional Eregion supervisor
  ↓
workload telemetry
workers min=1 max=8
  ↓
MqttJobTransport
  ↓
Mosquitto shared subscription
```

# End-to-End Example — Fan-out

```text
video.uploaded
  ↓
orchestrator worker
  ↓
JobDispatcher
  ├── transcode.360
  ├── transcode.720
  ├── transcode.1080
  └── thumbnail
         ↓
       broker
         ↓
Eregion escala pools especializados
```

# Ecosystem Acceptance Criteria

- `minimal` usa HTTP persistente supervisionado por Eregion (quando Forge resolve supervisor).
- `service` usa HTTP persistente supervisionado por Eregion.
- `worker` roda standalone apenas com Mithril.
- `worker` pode opcionalmente ser supervisionado por Eregion.
- Eregion suporta múltiplos workloads simultaneamente.
- Consumer workload pode escalar entre min/max (Phase 4 — validação parcial).
- `min=0` não mantém processo desnecessário (Phase 4 — validação parcial).
- subjobs escalam via broker.
- nenhum broker é implementado obrigatoriamente em Eregion.
- Core não conhece `minimal`, `service` ou `worker` para resolução de runtime.
- Installer não possui fallback `eregion`.

# Definition of Done (master)

A master spec está **concluída** quando Durin consegue ir de HTTP simples a consumers persistentes escaláveis **sem** misturar framework, runtime, supervisor e broker, **e** quando distribuição + smoke E2E estiverem verdes de forma repetível em CI/consumo Packagist.

**Estado atual (2026-09-29):** Phase 1 e Phase 3 **DONE**; Phase 2 e 5 **implementadas** com hardening residual; Phase 4 **PARTIAL**; visão global **não** marcada como DONE até fechar gaps de distribuição/E2E.

**Evidência parcial:** smokes locais (`scripts/workloads-integration-smoke.sh`) cobrem standalone, supervised, scale, crash/restart, drain, idle, HTTP e matriz de launchers — não substituem hardening de distribuição pendente.
