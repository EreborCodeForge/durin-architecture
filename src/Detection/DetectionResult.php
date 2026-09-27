<?php

declare(strict_types=1);

namespace EreborCodeForge\Durin\Architecture\Detection;

use EreborCodeForge\Durin\Architecture\Model\ArchitectureState;

final readonly class DetectionResult
{
    /**
     * @param list<string> $warnings
     * @param array<string, mixed> $evidence
     */
    public function __construct(
        public ArchitectureState $state,
        public array $warnings = [],
        public array $evidence = [],
    ) {}
}
