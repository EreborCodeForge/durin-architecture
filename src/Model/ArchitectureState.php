<?php

declare(strict_types=1);

namespace EreborCodeForge\Durin\Architecture\Model;

/**
 * Observed architecture state of a Durin-managed project.
 */
final readonly class ArchitectureState
{
    /**
     * @param list<string> $capabilities
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public ?string $preset = null,
        public ?string $applicationName = null,
        public array $capabilities = [],
        public array $metadata = [],
    ) {}
}
