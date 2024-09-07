<?php

namespace organizationManagement\application\organization;

use organizationManagement\domain\model\organization\OrganizationName;
use organizationManagement\domain\model\organization\OrganizationStatus;
use organizationManagement\domain\model\organization\OrganizationType;

class DetailedOrganizationInfo
{
    private string $name;
    private string $type;
    private string $status;
    private array $employeeList;

    /**
     * @param OrganizationName $name
     * @param OrganizationType $type
     * @param OrganizationStatus $status
     * @param Employee[] $employeeList
     */
    public function __construct(
        OrganizationName $name,
        OrganizationType $type,
        OrganizationStatus $status,
        array $employeeList
    )
    {
        $this->name = $name->value();
        $this->type = $type->displayValue();
        $this->status = $status->displayValue();
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

    public function employeeList(): array
    {
        return $this->employeeList;
    }
}