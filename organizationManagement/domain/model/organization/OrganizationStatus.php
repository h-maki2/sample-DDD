<?php

namespace organizationManagement\domain\model\organization;

/**
 * 組織状態
 */
enum OrganizationStatus: string
{
    case ABOLITION = '0'; // 廃止
    case SURVIVES = '1'; // 存続

    const DISPLAY_NAME = [
        self::ABOLITION => '廃止',
        self::SURVIVES => '存続'
    ];

    public function isAbolition(): bool
    {
        return $this->value === self::ABOLITION;
    }

    public function isSurvives(): bool
    {
        return $this->value === self::SURVIVES;
    }

    public function displayValue(): string
    {
        return self::DISPLAY_NAME[$this->value];
    }
}
