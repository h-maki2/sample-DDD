<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use organizationManagement\domain\model\employee\EmployeeId;
use organizationManagement\domain\model\employee\EmployeeName;
use organizationManagement\domain\model\organization\Organization;
use organizationManagement\domain\model\organization\OrganizationName;
use organizationManagement\domain\model\organization\OrganizationStatus;
use organizationManagement\domain\model\organization\OrganizationType;
use Tests\TestCase;
use organizationManagement\sqlInfrastructure\persistence\EloquentOrganizationRepository;
use organizationManagement\sqlInfrastructure\persistence\EloquentEmployeeRepository;
use organizationManagement\test\common\helper\employee\EmployeeTestDataCreator;
use organizationManagement\test\common\helper\organization\OrganizationTestDataCreator;

class EloquentOrganizationRepositoryTest extends TestCase
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

    public function test_インサートしたデータがfindByidメソッドで取得できる()
    {
        // given: テストデータの準備
        // employeeがすでに作成されている
        $testEmployeeId = $this->employeeRepository->nextIdentity();
        $this->employeeTestData->create($testEmployeeId);


        $testOrganizationId = $this->organizationRepository->nextIdentity();
        $testOrganizationName = new OrganizationName('test organiation');
        $testOrganizationType = OrganizationType::DEPARTMENT;
        $testOrganizationStatus = OrganizationStatus::SURVIVES;

        $organization = Organization::reconstruct(
            $testOrganizationId,
            $testOrganizationType,
            $testOrganizationStatus,
            $testOrganizationName,
            [$testEmployeeId]
        );

        // when
        $this->organizationRepository->save($organization);
        $organization = $this->organizationRepository->findById($testOrganizationId);

        // then
        $this->assertEquals($testOrganizationId->value(), $organization->id()->value());
        $this->assertEquals($testOrganizationName->value(), $organization->name()->value());
        $this->assertEquals($testOrganizationType->value, $organization->type()->value);
        $this->assertEquals($testOrganizationStatus->value, $organization->status()->value);
        $this->assertEquals($testEmployeeId->value(), $organization->employeeIdList()[0]->value());
    }
}