<?php

namespace organizationManagement\domain\model\organization;

/**
 * 組織状態
 */
enum OrganizationStatus: string
{
    case ABOLITION = '0'; // 廃止
    case SURVIVES = '1'; // 存続

    public function isAbolition(): bool
    {
        return $this->value === self::ABOLITION->value;
    }

    public function isSurvives(): bool
    {
        return $this->value === self::SURVIVES->value;
    }

    public function displayValue(): string
    {
        return match($this) {
            self::ABOLITION => '廃止',
            self::SURVIVES => '存続'
        };
    }
}
