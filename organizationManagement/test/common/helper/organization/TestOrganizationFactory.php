<?php

namespace organizationManagement\test\common\helper\organization;

use organizationManagement\domain\model\organization\Organization;
use organizationManagement\domain\model\organization\OrganizationId;
use organizationManagement\domain\model\organization\OrganizationName;
use organizationManagement\domain\model\organization\OrganizationStatus;
use organizationManagement\domain\model\organization\OrganizationType;

class TestOrganizationFactory
{
    public function create(
        OrganizationId $id,
        array $employeeIdList,
        OrganizationName $name = new OrganizationName('test user'),
        OrganizationType $type = OrganizationType::DEPARTMENT,
        OrganizationStatus $status = OrganizationStatus::SURVIVES,
    ): Organization 
    {
        return Organization::reconstruct(
            $id,
            $type,
            $status,
            $name,
            $employeeIdList
        );
    }
}