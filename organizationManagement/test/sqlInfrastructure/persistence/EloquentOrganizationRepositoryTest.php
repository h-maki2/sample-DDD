<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use organizationManagement\domain\model\employee\EmployeeId;
use Tests\TestCase;
use organizationManagement\sqlInfrastructure\persistence\EloquentOrganizationRepository;
use organizationManagement\sqlInfrastructure\persistence\EloquentEmployeeRepository;
use organizationManagement\test\common\helper\organization\OrganizationTestDataCreator;

class EloquentOrganizationRepositoryTest extends TestCase
{
    private EloquentOrganizationRepository $organizationRepository;
    private EloquentEmployeeRepository $employeeRepository;
    private OrganizationTestDataCreator $organizationCreator;

    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->organizationRepository = new EloquentOrganizationRepository();
        $this->employeeRepository = new EloquentEmployeeRepository();
        $this->organizationCreator = new OrganizationTestDataCreator(
            $this->organizationRepository,
            $this->employeeRepository
        );
    }

    public function test_インサートしたデータがfindByidメソッドで取得できる()
    {
        // given organizationsテーブルにデータをインサートしておく
        $organizationId = $this->organizationRepository->nextIdentity();
        $employeeId = $this->employeeRepository->nextIdentity();

        $this->organizationCreator->create(
            $organizationId,
            $employeeId
        );

        // when
        $organization = $this->organizationRepository->findById($organizationId);

        // then
        $this->assertEquals($organizationId->value(), $organization->id()->value());
    }
}