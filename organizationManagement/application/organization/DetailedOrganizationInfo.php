<?php

namespace organizationManagement\application\organization;

use organizationManagement\application\common\EmployeeData;
use organizationManagement\domain\model\organization\OrganizationId;
use organizationManagement\domain\model\organization\OrganizationName;
use organizationManagement\domain\model\organization\OrganizationStatus;
use organizationManagement\domain\model\organization\OrganizationType;

class DetailedOrganizationInfo
{
    private string $id;
    private string $name;
    private string $type;
    private string $status;
    private array $employeeList;

    /**
     * @param OrganizationName $name
     * @param OrganizationType $type
     * @param OrganizationStatus $status
     * @param EmployeeData[] $employeeList
     */
    public function __construct(
        OrganizationId $id,
        OrganizationName $name,
        OrganizationType $type,
        OrganizationStatus $status,
        array $employeeList
    )
    {
        $this->id = $id->value();
        $this->name = $name->value();
        $this->type = $type->displayValue();
        $this->status = $status->displayValue();
        $this->employeeList = $employeeList;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function status(): string
    {
        return $this->status;
    }

    /**
     * @return EmployeeData[]
     */
    public function employeeDataList(): array
    {
        return $this->employeeList;
    }
}