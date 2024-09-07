<?php

use PHPUnit\Framework\TestCase;
use organizationManagement\domain\model\organization\OrganizationStatus;

class OrganizationStatusTest extends TestCase
{
    public function test_組織の状態が廃止の場合()
    {
        // when: 組織の状態が廃止の時
        $status = OrganizationStatus::ABOLITION;

        // then
        $this->assertTrue($status->isAbolition());
        $this->assertFalse($status->isSurvives());
        $this->assertEquals('廃止', $status->displayValue());
    }

    public function test_組織の状態が存続の場合()
    {
        // when: 組織の状態が存続の場合
        $status = OrganizationStatus::SURVIVES;

        // then
        $this->assertFalse($status->isAbolition());
        $this->assertTrue($status->isSurvives());
        $this->assertEquals('存続', $status->displayValue());
    }
}