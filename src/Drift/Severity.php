<?php

declare(strict_types=1);

namespace EreborCodeForge\Durin\Architecture\Drift;

enum Severity: string
{
    case Info = 'info';
    case Warning = 'warning';
    case Error = 'error';
}
