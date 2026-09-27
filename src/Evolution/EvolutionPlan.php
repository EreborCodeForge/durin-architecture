<?php

declare(strict_types=1);

namespace EreborCodeForge\Durin\Architecture\Evolution;

use EreborCodeForge\Durin\Architecture\Model\ArchitectureState;
use EreborCodeForge\Durin\Architecture\Model\ArchitectureTarget;

/**
 * Compatible architectural growth plan. Not every transition is valid.
 */
final readonly class EvolutionPlan
{
    /**
     * @param list<string> $requiredChanges
     * @param list<string> $preservedElements
     * @param list<string> $conflicts
     * @param list<string> $warnings
     */
    public function __construct(
        public ArchitectureState $current,
        public ArchitectureTarget $target,
        public bool $compatible,
        public array $requiredChanges = [],
        public array $preservedElements = [],
        public array $conflicts = [],
        public array $warnings = [],
        public string $risk = 'unknown',
    ) {}
}
