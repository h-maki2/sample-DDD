<?php

namespace infrastructure\persistence;

use organizationManagement\application\organization\IOrganizationQueryService;
use organizationManagement\application\organization\DetailedOrganizationInfo;
use App\Models\Organization as EloquentOrganization;
use organizationManagement\application\common\EmployeeData\EmployeeData;
use organizationManagement\domain\model\employee\Employee;
use organizationManagement\domain\model\organization\OrganizationId;
use organizationManagement\domain\model\organization\OrganizationName;
use organizationManagement\domain\model\organization\OrganizationType;
use organizationManagement\domain\model\organization\OrganizationStatus;

class EloquentOrganizationQueryService implements IOrganizationQueryService
{
    public function detailedOrganizationInfo(OrganizationId $id): ?DetailedOrganizationInfo
    {
        $organization = EloquentOrganization::find($id->value());
        if (empty($organization)) {
            return null;
        }

        $employeeList = [];
        foreach ($organization->employees as $employee) {
            $employeeList[] = new EmployeeData(
                $employee->name(),
                (bool) $employee->retired()
            );
        }

        return new DetailedOrganizationInfo(
            new OrganizationName($organization->name()),
            new OrganizationType($organization->type()),
            new OrganizationStatus($organization->status()),
            $employeeList
        );
    }
}