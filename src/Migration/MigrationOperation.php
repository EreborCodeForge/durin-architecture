<?php

declare(strict_types=1);

namespace EreborCodeForge\Durin\Architecture\Migration;

final readonly class MigrationOperation
{
    public function __construct(
        public MigrationOperationType $type,
        public string $path,
        public ?string $targetPath = null,
        public ?string $detail = null,
    ) {}
}
