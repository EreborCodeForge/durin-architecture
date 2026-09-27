<?php

declare(strict_types=1);

namespace EreborCodeForge\Durin\Architecture\Drift;

final readonly class DriftReport
{
    /**
     * @param list<DriftFinding> $findings
     */
    public function __construct(
        public array $findings = [],
    ) {}

    public function isClean(): bool
    {
        return $this->findings === [];
    }
}
