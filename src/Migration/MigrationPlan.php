<?php

declare(strict_types=1);

namespace EreborCodeForge\Durin\Architecture\Migration;

use EreborCodeForge\Durin\Architecture\Model\ArchitectureState;
use EreborCodeForge\Durin\Architecture\Model\ArchitectureTarget;

/**
 * Explicit broader architectural transition plan.
 * Deletion/removal MUST never happen silently.
 */
final readonly class MigrationPlan
{
    /**
     * @param list<MigrationOperation> $operations
     * @param list<string> $keep
     * @param list<string> $create
     * @param list<string> $move
     * @param list<string> $modify
     * @param list<string> $remove
     * @param list<string> $conflicts
     * @param list<string> $warnings
     */
    public function __construct(
        public ArchitectureState $current,
        public ArchitectureTarget $target,
        public array $operations = [],
        public array $keep = [],
        public array $create = [],
        public array $move = [],
        public array $modify = [],
        public array $remove = [],
        public array $conflicts = [],
        public array $warnings = [],
        public string $risk = 'unknown',
    ) {}
}
