<?php

namespace organizationManagement\test\common\helper\organization;

use organizationManagement\domain\model\employee\EmployeeId;
use organizationManagement\domain\model\employee\EmployeeName;
use organizationManagement\sqlInfrastructure\persistence\EloquentOrganizationRepository;
use organizationManagement\domain\model\organization\Organization;
use organizationManagement\domain\model\organization\OrganizationId;
use organizationManagement\domain\model\organization\OrganizationName;
use organizationManagement\domain\model\organization\OrganizationStatus;
use organizationManagement\domain\model\organization\OrganizationType;
use organizationManagement\sqlInfrastructure\persistence\EloquentEmployeeRepository;
use organizationManagement\test\common\helper\employee\EmployeeTestDataCreator;

class OrganizationTestDataCreator
{
    private EloquentOrganizationRepository $organizationRepository;
    
    public function __construct(
        EloquentOrganizationRepository $organizationRepository
    )
    {
        $this->organizationRepository = $organizationRepository;
    }

    public function create(
        organizationId $organizationId,
        array $employeeIdList,
        OrganizationName $organizatioName = new OrganizationName('test organization'),
        OrganizationType $type = OrganizationType::DEPARTMENT,
        OrganizationStatus $status = OrganizationStatus::SURVIVES,
    ): Organization
    {
        $organizationFactory = new TestOrganizationFactory();
        $organization = $organizationFactory->create(
            $organizationId,
            $employeeIdList,
            $organizatioName,
            $type,
            $status
        );

        $this->organizationRepository->save($organization);

        return $organization;
    }
}