<?php

declare(strict_types=1);

namespace EreborCodeForge\Durin\Architecture\Drift;

use EreborCodeForge\Durin\Architecture\Model\ArchitectureState;
use EreborCodeForge\Durin\Architecture\Model\ArchitectureTarget;
use EreborCodeForge\Durin\Core\Project\Project;

interface DriftDetector
{
    public function detect(Project $project, ArchitectureState $state, ?ArchitectureTarget $expected = null): DriftReport;
}
