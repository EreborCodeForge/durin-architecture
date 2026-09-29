# ereborcodeforge/durin-architecture

## Purpose

Architecture detection, drift and migration planning contracts for Durin projects.

## Cross-repo workloads integration

The **canonical** transversal spec for Durin workloads (Core, presets, Forge, app, installer, Mithril, Eregion) is maintained here:

- [`docs/specs/durin-workloads-integration-master-spec.md`](docs/specs/durin-workloads-integration-master-spec.md)

Per-repository implementation specs remain in each package’s `docs/` tree (see *Spec index* in the master document).

## What this package owns

- Architecture state / target models
- Detection, adoption, drift, evolution and migration **contracts and result DTOs**
- Structured plan shapes (`keep` / `create` / `move` / `modify` / `remove` / conflicts / warnings / risk)

## What this package does not own

- Concrete detection heuristics or transition graphs (not yet extracted from Durin Forge)
- CLI / terminal rendering
- Filesystem mutation execution (see `durin-core`)
- Concrete preset definitions (see `durin-presets`)
- MithrilPHP / Eregion / doctor / status / graph UX
- Jev / LLM dependencies

## Installation

```bash
composer require ereborcodeforge/durin-architecture
```

Local path development:

```json
{
  "repositories": [
    { "type": "path", "url": "../durin-core", "options": { "symlink": true } },
    { "type": "path", "url": "../durin-presets", "options": { "symlink": true } },
    { "type": "path", "url": "../durin-architecture", "options": { "symlink": true } }
  ]
}
```

## PHP requirement

PHP `^8.5`

## Basic usage

```php
use EreborCodeForge\Durin\Architecture\Model\ArchitectureState;
use EreborCodeForge\Durin\Architecture\Model\ArchitectureTarget;
use EreborCodeForge\Durin\Architecture\Drift\DriftFinding;
use EreborCodeForge\Durin\Architecture\Drift\DriftReport;
use EreborCodeForge\Durin\Architecture\Drift\Severity;

$state = new ArchitectureState(preset: 'minimal');
$target = new ArchitectureTarget(preset: 'service');

// Planners/detectors implement package interfaces; Forge renders results.
$report = new DriftReport([
    new DriftFinding('layering', 'src/Http', 'unexpected dependency', Severity::Warning),
]);
```

## Dependency direction

```text
durin-architecture
  -> durin-core
  -> durin-presets
```

Must not depend on Durin Forge.

## Supported API

Contracts: `ArchitectureDetector`, `AdoptionPlanner`, `DriftDetector`, `EvolutionPlanner`, `MigrationPlanner`.

DTOs: `ArchitectureState`, `ArchitectureTarget`, `DetectionResult`, `AdoptionPlan`, `DriftReport`, `DriftFinding`, `EvolutionPlan`, `MigrationPlan`, `MigrationOperation`.

## Versioning status

Initial contract surface: **0.1.0**. Implementations will land when Forge architecture tooling is extracted.

## Relationship to Durin Forge

Forge owns CLI orchestration and presentation. This package owns structured architecture understanding and planning results once concrete planners are provided.
