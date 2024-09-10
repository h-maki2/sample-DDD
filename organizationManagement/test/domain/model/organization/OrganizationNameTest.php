<?php

use PHPUnit\Framework\TestCase;

use organizationManagement\domain\model\organization\OrganizationName;

class OrganizationNameTest extends TestCase
{
    /**
     * @dataProvider provideAbnormalData
     */
    public function test異常な値が入力されたときに例外が発生する($abnormalData)
    {
        $this->expectException(InvalidArgumentException::class);
        new OrganizationName($abnormalData);
    }

    /**
     * @dataProvider provideNormalData
     */
    public function test正常にインスタンスを生成できる($normalData)
    {
        $name = new OrganizationName($normalData);
        $this->assertInstanceOf(OrganizationName::class, $name);
    }

    public function provideNormalData()
    {
        return [
            ['1'],
            [str_pad('', 49, 'a')],
            ['test　user'],
            ['test user']
        ];
    }

    public function provideAbnormalData()
    {
        return [
            [''],
            [' '],
            ['　'],
            [str_pad('', 50, '1')]
        ];
    }
}