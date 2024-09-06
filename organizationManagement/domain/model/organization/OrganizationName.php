<?php

namespace organizationManagement\domain\model\organization;

use InvalidArgumentException;

class OrganizationName
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty($name)) {
            throw new InvalidArgumentException('組織名が空です。');
        }

        if (mb_strlen($value, 'UTF-8') >= 50) {
            throw new InvalidArgumentException('組織名が50文字を超えています。');
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}