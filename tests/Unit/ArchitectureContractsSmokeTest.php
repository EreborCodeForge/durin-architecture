<?php

declare(strict_types=1);

namespace EreborCodeForge\Durin\Architecture\Tests\Unit;

use EreborCodeForge\Durin\Architecture\Drift\DriftFinding;
use EreborCodeForge\Durin\Architecture\Drift\DriftReport;
use EreborCodeForge\Durin\Architecture\Drift\Severity;
use EreborCodeForge\Durin\Architecture\Migration\MigrationOperation;
use EreborCodeForge\Durin\Architecture\Migration\MigrationOperationType;
use EreborCodeForge\Durin\Architecture\Migration\MigrationPlan;
use EreborCodeForge\Durin\Architecture\Model\ArchitectureState;
use EreborCodeForge\Durin\Architecture\Model\ArchitectureTarget;
use EreborCodeForge\Durin\Core\Scaffold\ScaffoldPlan;
use EreborCodeForge\Durin\Presets\Registry\DefaultPresetRegistryFactory;
use PHPUnit\Framework\TestCase;

final class ArchitectureContractsSmokeTest extends TestCase
{
    public function test_state_and_target_dtos_instantiate(): void
    {
        $state = new ArchitectureState(preset: 'minimal', applicationName: 'demo');
        $target = new ArchitectureTarget(preset: 'service');

        $this->assertSame('minimal', $state->preset);
        $this->assertSame('service', $target->preset);
    }

    public function test_drift_report_and_finding_structure(): void
    {
        $finding = new DriftFinding(
            rule: 'layering',
            location: 'src/Http/Controller.php',
            message: 'Controller talks to infrastructure directly',
            severity: Severity::Warning,
        );
        $report = new DriftReport([$finding]);

        $this->assertFalse($report->isClean());
        $this->assertSame(Severity::Warning, $report->findings[0]->severity);
    }

    public function test_migration_plan_carries_explicit_operations(): void
    {
        $plan = new MigrationPlan(
            current: new ArchitectureState(preset: 'minimal'),
            target: new ArchitectureTarget(preset: 'service'),
            operations: [
                new MigrationOperation(MigrationOperationType::Create, 'src/Domain'),
                new MigrationOperation(MigrationOperationType::Keep, 'src/Application'),
            ],
            create: ['src/Domain'],
            keep: ['src/Application'],
            risk: 'medium',
        );

        $this->assertCount(2, $plan->operations);
        $this->assertSame(MigrationOperationType::Create, $plan->operations[0]->type);
        $this->assertSame('medium', $plan->risk);
    }

    public function test_presets_dependency_is_loadable(): void
    {
        $registry = (new DefaultPresetRegistryFactory())->create();
        $this->assertTrue($registry->has('minimal'));
        $this->assertInstanceOf(ScaffoldPlan::class, $registry->get('minimal')->scaffold(
            new \EreborCodeForge\Durin\Core\Contract\ProjectOptions('demo', 'minimal', '/tmp/demo')
        ));
    }
}
