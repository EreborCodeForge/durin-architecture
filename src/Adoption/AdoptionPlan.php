<?php

declare(strict_types=1);

namespace EreborCodeForge\Durin\Architecture\Adoption;

use EreborCodeForge\Durin\Architecture\Model\ArchitectureState;
use EreborCodeForge\Durin\Core\Scaffold\ScaffoldPlan;

/**
 * Adoption establishes managed state; it must not rewrite application code
 * merely to resemble a preset.
 */
final readonly class AdoptionPlan
{
    /**
     * @param list<string> $keep
     * @param list<string> $create
     * @param list<string> $warnings
     * @param list<string> $conflicts
     */
    public function __construct(
        public ArchitectureState $detected,
        public ScaffoldPlan $manifestMutations,
        public array $keep = [],
        public array $create = [],
        public array $warnings = [],
        public array $conflicts = [],
        public string $risk = 'low',
    ) {}
}
