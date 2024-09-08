<?php

namespace organizationManagement\test\common\helper\organization;

use organizationManagement\sqlInfrastructure\persistence\EloquentOrganizationRepository;
use organizationManagement\domain\model\organization\Organization;
use organizationManagement\domain\model\organization\OrganizationId;
use organizationManagement\domain\model\organization\OrganizationName;
use organizationManagement\domain\model\organization\OrganizationStatus;
use organizationManagement\domain\model\organization\OrganizationType;

class OrganizationTestDataCreator
{
    private EloquentOrganizationRepository $organizationRepository;

    public function __construct(EloquentOrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    public function create(
        organizationId $id,
        array $employeeIdList,
        OrganizationName $name = new OrganizationName('test user'),
        OrganizationType $type = OrganizationType::DEPARTMENT,
        OrganizationStatus $status = OrganizationStatus::SURVIVES,
    ): Organization
    {
        $organizationFactory = new TestOrganizationFactory();
        $organization = $organizationFactory->create(
            $id,
            $employeeIdList,
            $name,
            $type,
            $status
        );

        $this->organizationRepository->save($organization);

        return $organization;
    }
}