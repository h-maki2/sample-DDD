<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use organizationManagement\domain\model\employee\EmployeeId;
use Tests\TestCase;
use organizationManagement\sqlInfrastructure\persistence\EloquentOrganizationRepository;
use organizationManagement\test\common\helper\organization\OrganizationTestDataCreator;

class EloquentOrganizationRepositoryTest extends TestCase
{
    private EloquentOrganizationRepository $organizationRepository;

    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->organizationRepository = new EloquentOrganizationRepository();
    }

    public function test_インサートしたデータがfindByidメソッドで取得できる()
    {
        // given organizationsテーブルにデータをインサートしておく
        $organizationId = $this->organizationRepository->nextIdentity();
        $employeeIdList = [new EmployeeId('1')];

        $organizationCreator = new OrganizationTestDataCreator($this->organizationRepository);
        $organizationCreator->create(
            $organizationId,
            $employeeIdList
        );

        // when
        $organization = $this->organizationRepository->findById($organizationId);

        // then
        $this->assertEquals($organizationId->value(), $organization->id()->value());
    }
}