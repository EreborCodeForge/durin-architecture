<?php

declare(strict_types=1);

namespace EreborCodeForge\Durin\Architecture\Migration;

enum MigrationOperationType: string
{
    case Keep = 'keep';
    case Create = 'create';
    case Move = 'move';
    case Modify = 'modify';
    case Remove = 'remove';
}
