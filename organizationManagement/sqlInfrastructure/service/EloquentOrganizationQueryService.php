<?php

namespace organizationManagement\sqlInfrastructure\service;

use organizationManagement\application\organization\IOrganizationQueryService;
use organizationManagement\application\organization\DetailedOrganizationInfo;
use organizationManagement\application\common\EmployeeData;
use organizationManagement\domain\model\organization\OrganizationId;
use organizationManagement\domain\model\organization\OrganizationName;
use organizationManagement\domain\model\organization\OrganizationType;
use organizationManagement\domain\model\organization\OrganizationStatus;
use App\Models\Organization as EloquentOrganization;

class EloquentOrganizationQueryService implements IOrganizationQueryService
{
    public function detailedOrganizationInfo(OrganizationId $id): ?DetailedOrganizationInfo
    {
        $organization = EloquentOrganization::with('employees')->find($id->value());
        if (empty($organization)) {
            return null;
        }

        $employeeList = [];
        foreach ($organization->employees as $employee) {
            $employeeList[] = new EmployeeData(
                $employee->id,
                $employee->name,
                (bool) $employee->retired
            );
        }

        return new DetailedOrganizationInfo(
            new OrganizationId($organization->id),
            new OrganizationName($organization->name),
            OrganizationType::from($organization->type),
            OrganizationStatus::from($organization->status),
            $employeeList
        );
    }
}