<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use organizationManagement\domain\model\employee\EmployeeName;
use organizationManagement\domain\model\organization\OrganizationName;
use organizationManagement\domain\model\organization\OrganizationStatus;
use organizationManagement\domain\model\organization\OrganizationType;
use Tests\TestCase;
use organizationManagement\sqlInfrastructure\persistence\EloquentOrganizationRepository;
use organizationManagement\sqlInfrastructure\persistence\EloquentEmployeeRepository;
use organizationManagement\test\common\helper\employee\EmployeeTestDataCreator;
use organizationManagement\test\common\helper\organization\OrganizationTestDataCreator;

class OrganizationControllerTest extends TestCase
{
    private EloquentOrganizationRepository $organizationRepository;
    private EloquentEmployeeRepository $employeeRepository;
    private OrganizationTestDataCreator $organizationCreator;
    private EmployeeTestDataCreator $employeeTestData;

    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->organizationRepository = new EloquentOrganizationRepository();
        $this->employeeRepository = new EloquentEmployeeRepository();
        $this->organizationCreator = new OrganizationTestDataCreator(
            $this->organizationRepository
        );
        $this->employeeTestData = new EmployeeTestDataCreator($this->employeeRepository);
    }

    public function test_組織情報の詳細が取得できる()
    {
        // given: テストデータの準備
        $employeeId = $this->employeeRepository->nextIdentity();
        $employeeName = new EmployeeName('test user');
        $isRetired = false;
        $this->employeeTestData->create(
            $employeeId,
            $employeeName,
            $isRetired
        );

        $organizationId = $this->organizationRepository->nextIdentity();
        $organizationName = new OrganizationName('test organization');
        $organizationType = OrganizationType::DEPARTMENT;
        $organizationStatus = OrganizationStatus::SURVIVES;
        $this->organizationCreator->create(
            $organizationId,
            [$employeeId],
            $organizationName,
            $organizationType,
            $organizationStatus
        );

        // when
        $response = $this->json('GET', '/detail', ['id' => $organizationId->value()]);

        // then
        $response->assertStatus(200);
    }
}