<?php

use organizationManagement\domain\model\common\exception\IllegalStateException;
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

        // then: 組織の状態が「部」で、組織の状態が「存続」であることを確認
        $this->assertEquals(OrganizationType::DEPARTMENT->value, $organization->type()->value);
        $this->assertEquals(OrganizationStatus::SURVIVES->value, $organization->status()->value);

        // 以下の値はそのまま設定される
        $this->assertEquals($name->value(), $organization->name()->value());
        $this->assertEquals($id->value(), $organization->id()->value());
    }

    public function test_組織の状態が存続の場合に、組織に従業員を所属できる()
    {
        // given
        $statusIsSurvives = OrganizationStatus::SURVIVES; // 組織の状態は存続
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

    public function test_組織が廃止されている場合は、従業員を所属できない()
    {
        // given
        $statusIsAbolition = OrganizationStatus::ABOLITION; // 組織の状態は廃止
        $organization = Organization::reconstruct(
            new OrganizationId('1'),
            OrganizationType::DEPARTMENT,
            $statusIsAbolition,
            new OrganizationName('営業'),
            []
        );

        // when

        // then: 従業員を所属させる
        $assignId = new EmployeeId('assign id');
        $this->expectException(IllegalStateException::class);
        $this->expectExceptionMessage('employeeId: ' . $assignId->value() . 'の所属に失敗しました。');

        $organization->assign($assignId);
    }

   public function test_101人以上従業員が所属している場合に、組織の種別が「課」に更新される()
   {
        // given
        $id = new OrganizationId('1');
        $name = new OrganizationName('営業');
        $organization = Organization::create(
            $id,
            $name
        );

        // 100人分の従業員を所属させる
        for ($i = 1; $i <= 100; $i++) {
            $assignId = new EmployeeId($i);
            $organization->assign($assignId);
        }

        // when: 101人目の従業員を所属させる
        $assignId = new EmployeeId('101');
        $organization->assign($assignId);

        // then: 組織の種別が「課」に更新されていることを確認
        $this->assertEquals(OrganizationType::SECTION->value, $organization->type()->value);
   }

   public function test_所属している従業員数が0人の場合に、組織の状態を「廃止」にできる()
   {
        // given
        $organization = Organization::reconstruct(
            new OrganizationId('1'),
            OrganizationType::DEPARTMENT,
            OrganizationStatus::SURVIVES, // 組織に状態は存続
            new OrganizationName('営業'),
            [] // 従業員数は0人
        );

        // when
        $organization->changeToAbolition();

        // then: 組織の状態は「廃止」であることを確認
        $this->assertEquals(OrganizationStatus::ABOLITION->value, $organization->status()->value);   
    }

    public function test_所属している従業員数が1人以上の場合に、組織の状態を「廃止」に変更すると例外が発生する()
    {
        // given
        $id = new OrganizationId('1');
        $organization = Organization::reconstruct(
            $id,
            OrganizationType::DEPARTMENT,
            OrganizationStatus::SURVIVES, // 組織に状態は存続
            new OrganizationName('営業'),
            [new EmployeeId('1')] // 従業員数は0人
        );

        // then
        $this->expectException(IllegalStateException::class);
        $this->expectExceptionMessage('organizationID: ' . $id->value() . 'の廃止処理に失敗しました。');
        $organization->changeToAbolition();
    }

    public function test_reconstructに値を渡すと、渡した値でインスタンスが作成される()
    {
        // given

        // when
        $oarganizationId = new OrganizationId('1');
        $type = OrganizationType::DEPARTMENT;
        $status = OrganizationStatus::SURVIVES;
        $name = new OrganizationName('営業');
        $employeeId = new EmployeeId('1');

        $organization = Organization::reconstruct(
            $oarganizationId,
            $type,
            $status,
            $name,
            [$employeeId]
        );

        // then
        $this->assertEquals($oarganizationId->value(), $organization->id()->value());
        $this->assertEquals($type->value, $organization->type()->value);
        $this->assertEquals($status->value, $organization->status()->value);
        $this->assertEquals($name->value(), $organization->name()->value());
        $this->assertEquals($employeeId->value(), $organization->employeeIdList()[0]->value());
    }
}