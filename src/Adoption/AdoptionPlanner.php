<?php

declare(strict_types=1);

namespace EreborCodeForge\Durin\Architecture\Adoption;

use EreborCodeForge\Durin\Architecture\Model\ArchitectureState;
use EreborCodeForge\Durin\Core\Project\Project;

interface AdoptionPlanner
{
    public function plan(Project $project, ArchitectureState $detected): AdoptionPlan;
}
