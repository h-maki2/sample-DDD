<?php

use PHPUnit\Framework\TestCase;
use organizationManagement\domain\model\organization\OrganizationType;

class OrganizationTypeTest extends TestCase
{
    public function test_組織種別が部である場合()
    {
        // when
        $type = OrganizationType::DEPARTMENT;

        // then
        $this->assertTrue($type->isDepartment());
        $this->assertFalse($type->isSection());
        $this->assertEquals('部', $type->displayValue());
    }

    public function test_組織種別が課である場合()
    {
        // when
        $type = OrganizationType::SECTION;

        // then
        $this->assertFalse($type->isDepartment());
        $this->assertTrue($type->isSection());
        $this->assertEquals('課', $type->displayValue());
    }
}
