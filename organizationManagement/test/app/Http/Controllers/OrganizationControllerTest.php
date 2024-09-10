<?php

use App\Http\Controllers\organization\DetailResponse;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use organizationManagement\application\common\EmployeeData;
use organizationManagement\application\organization\DetailedOrganizationInfo;
use organizationManagement\domain\model\employee\EmployeeId;
use organizationManagement\domain\model\employee\EmployeeName;
use organizationManagement\domain\model\organization\OrganizationId;
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
        $employee = $this->employeeTestData->create(
            $this->employeeRepository->nextIdentity()
        );

        $organizationId = $this->organizationRepository->nextIdentity();
        $organization = $this->organizationCreator->create(
            $this->organizationRepository->nextIdentity(),
            [$employee->id()]
        );

        // when
        $response = $this->json('GET', '/detail', ['id' => $organizationId->value()]);

        // then
        $detailedOrganizationInfo = new DetailedOrganizationInfo(
            $organization->Id(),
            $organization->name(),
            $organization->type(),
            $organization->status(),
            [
                new EmployeeData(
                    $employee->id()->value(),
                    $employee->name()->value(),
                    $employee->isRetired()
                )
            ]
        );
        $response->assertStatus(200)->assertJson($this->expectResult($detailedOrganizationInfo));
    }

    private function expectResult(DetailedOrganizationInfo $detailedOrganizationInfo)
    {
        $detailResponse = new DetailResponse();
        return $detailResponse->get($detailedOrganizationInfo);
    }
}