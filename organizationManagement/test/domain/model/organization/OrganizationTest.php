<?php

use organizationManagement\domain\model\employee\EmployeeId;
use PHPUnit\Framework\TestCase;
use organizationManagement\domain\model\organization\Organization;
use organizationManagement\domain\model\organization\OrganizationId;
use organizationManagement\domain\model\organization\OrganizationName;
use organizationManagement\domain\model\organization\OrganizationStatus;
use organizationManagement\domain\model\organization\OrganizationType;

class OrganizationTest extends TestCase
{
    public function test_新しくOrganizationを生成すると、組織種別が部で、組織の状態が存続のインスタンスが生成される()
    {
        // given

        // when
        $id = new OrganizationId('1');
        $name = new OrganizationName('営業');
        $organization = Organization::create(
            $id,
            $name
        );

        // then
        $this->assertEquals(OrganizationType::DEPARTMENT->value, $organization->type()->value);
        $this->assertEquals(OrganizationStatus::SURVIVES->value, $organization->status()->value);

        // 以下の値はそのまま設定される
        $this->assertEquals($name->value(), $organization->name()->value());
        $this->assertEquals($id->value(), $organization->id()->value());
    }

    public function test_組織の状態が存続の場合に、組織に従業員を所属できる()
    {
        // given
        $statusIsSurvives = OrganizationStatus::SURVIVES;
        $organization = Organization::reconstruct(
            new OrganizationId('1'),
            OrganizationType::DEPARTMENT,
            $statusIsSurvives,
            new OrganizationName('営業'),
            []
        );

        // when: 従業員を所属させる
        $assignId = new EmployeeId('assign id');
        $organization->assign($assignId);

        // then
        $employeeId = $organization->employeeIdList()[0];
        $this->assertEquals($assignId->value(), $employeeId->value());
    }

}