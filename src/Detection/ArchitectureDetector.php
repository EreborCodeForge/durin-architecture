<?php

declare(strict_types=1);

namespace EreborCodeForge\Durin\Architecture\Detection;

use EreborCodeForge\Durin\Architecture\Model\ArchitectureState;
use EreborCodeForge\Durin\Core\Project\Project;

interface ArchitectureDetector
{
    public function detect(Project $project): DetectionResult;
}
