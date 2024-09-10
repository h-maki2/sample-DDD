<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;;
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

    public function test_インサートしたデータがfindByIdメソッドで取得できる()
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

    public function test_指定のorganizationが削除される()
    {
        // given: テストデータの準備
        $testEmployeeId = $this->employeeRepository->nextIdentity();
        $this->employeeTestData->create($testEmployeeId);

        $testOrganizationId = $this->organizationRepository->nextIdentity();
        $this->organizationCreator->create($testOrganizationId, [$testEmployeeId]);

        // when
        $this->organizationRepository->delete($testOrganizationId);
        $organization = $this->organizationRepository->findById($testOrganizationId);

        // then: organizationが取得できないことを確認
        $this->assertEquals(null, $organization);
    }

    public function test_従業員を新たに所属させた場合に、変更内容が反映されている()
    {
        // given: テストデータの準備
        $testEmployeeId = $this->employeeRepository->nextIdentity();
        $this->employeeTestData->create($testEmployeeId);

        $testOrganizationId = $this->organizationRepository->nextIdentity();
        $employeeIdList = [$testEmployeeId];
        $this->organizationCreator->create($testOrganizationId, $employeeIdList);

        $organization = $this->organizationRepository->findById($testOrganizationId);

        // employeeを新たに作成する
        $assignedEmployeeId = $this->employeeRepository->nextIdentity();
        $this->employeeTestData->create($assignedEmployeeId);

        // when
        $organization->assign($assignedEmployeeId);
        $this->organizationRepository->save($organization);
        $chengedOrganization = $this->organizationRepository->findById($testOrganizationId);

        // then: 従業員が追加されていることを確認
        $expectResult = [$testEmployeeId, $assignedEmployeeId];
        $actualResult = $chengedOrganization->employeeIdList();
        $this->assertEquals(sort($expectResult), sort($actualResult));
    }
}