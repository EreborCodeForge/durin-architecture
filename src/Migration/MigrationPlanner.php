<?php

declare(strict_types=1);

namespace EreborCodeForge\Durin\Architecture\Migration;

use EreborCodeForge\Durin\Architecture\Model\ArchitectureState;
use EreborCodeForge\Durin\Architecture\Model\ArchitectureTarget;
use EreborCodeForge\Durin\Core\Project\Project;

interface MigrationPlanner
{
    public function plan(Project $project, ArchitectureState $current, ArchitectureTarget $target): MigrationPlan;
}
