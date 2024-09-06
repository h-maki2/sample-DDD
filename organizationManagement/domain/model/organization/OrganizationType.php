<?php

namespace organizationManagement\domain\model\organization;

/**
 * 組織種別
 */
enum OrganizationType: string
{
    case DEPARTMENT = '1'; // 部
    case SECTION = '2'; // 課

    const DISPLAY_NAME = [
        self::DEPARTMENT => '部',
        self::SECTION => '課'
    ];

    public function isDepartment(): bool
    {
        return $this->value === self::DEPARTMENT;
    }

    public function isSection(): bool
    {
        return $this->value === self::SECTION;
    }

    public function displayValue(): string
    {
        return self::DISPLAY_NAME[$this->value];  
    }
}
