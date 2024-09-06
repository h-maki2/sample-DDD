<?php

namespace organizationManagement\domain\model\organization;

use InvalidArgumentException;

class OrganizationId
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException('OrganizationIdが空です。');
        }
        
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
