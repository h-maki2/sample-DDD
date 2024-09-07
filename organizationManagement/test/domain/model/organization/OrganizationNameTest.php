<?php

use PHPUnit\Framework\TestCase;

use organizationManagement\domain\model\organization\OrganizationName;

class OrganizationNameTest extends TestCase
{
    public function test_50文字以上の値を渡した場合に例外が発生する()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('組織名が50文字を超えています。');
        new OrganizationName(str_pad('', 50, '1'));
    }

    public function test_値が空文字列の場合に例外が発生する()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('組織名が空です。');
        new OrganizationName('');
    }

    public function test_空白のみの文字列の場合に例外が発生する()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('組織名が空です。');
        new OrganizationName(' ');
        new OrganizationName('　');
    }

    public function test_50文字未満の値を渡した場合、正常にインスタンスが生成される()
    {
        $name = new OrganizationName(str_pad('', 49, '1'));
        $this->assertInstanceOf(OrganizationName::class, $name);
    }
}