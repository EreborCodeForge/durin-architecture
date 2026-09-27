<?php

declare(strict_types=1);

namespace EreborCodeForge\Durin\Architecture\Evolution;

use EreborCodeForge\Durin\Architecture\Model\ArchitectureState;
use EreborCodeForge\Durin\Architecture\Model\ArchitectureTarget;
use EreborCodeForge\Durin\Core\Project\Project;

interface EvolutionPlanner
{
    public function plan(Project $project, ArchitectureState $current, ArchitectureTarget $target): EvolutionPlan;
}
