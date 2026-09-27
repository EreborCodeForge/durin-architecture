<?php

declare(strict_types=1);

namespace EreborCodeForge\Durin\Architecture\Drift;

final readonly class DriftFinding
{
    public function __construct(
        public string $rule,
        public string $location,
        public string $message,
        public Severity $severity,
    ) {}
}
