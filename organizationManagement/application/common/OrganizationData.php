<?php

namespace organizationManagement\application\common;

use organizationManagement\domain\model\organization\OrganizationName;
use organizationManagement\domain\model\organization\OrganizationStatus;
use organizationManagement\domain\model\organization\OrganizationType;

class OrganizationData
{
    private string $name;
    private string $type;
    private string $status;

    public function __construct(
        OrganizationName $name,
        OrganizationType $type,
        OrganizationStatus $status
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
}