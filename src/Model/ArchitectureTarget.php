<?php

declare(strict_types=1);

namespace EreborCodeForge\Durin\Architecture\Model;

/**
 * Desired architecture target for adoption, evolution or migration.
 */
final readonly class ArchitectureTarget
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public string $preset,
        public ?string $applicationType = null,
        public array $metadata = [],
    ) {}
}
