<?php

namespace App\Http\Controllers\organization;

use organizationManagement\application\organization\DetailedOrganizationInfo;

class DetailResponse
{
    public function get(DetailedOrganizationInfo $detailedOrganizationInfo): array
    {
        return [
            'organizationId' => $detailedOrganizationInfo->id(),
            'organizationName' => $detailedOrganizationInfo->name(),
            'organizationType' => $detailedOrganizationInfo->type(),
            'organizationStatus' => $detailedOrganizationInfo->status(),
            'employeeList' => $this->employeeList($detailedOrganizationInfo->employeeDataList())
        ];
    }

    /**
     * @param EmployeeData[]
     * @return array
     */
    private function employeeList(array $employeeDataList): array
    {
        $employeeList = [];
        foreach ($employeeDataList as $employeeData) {
            $employeeList[] = [
                'employeeId' => $employeeData->id(),
                'employeeName' => $employeeData->name(),
                'isRetired' => $employeeData->isRetired()
            ];
        }

        return $employeeList;
    }
}