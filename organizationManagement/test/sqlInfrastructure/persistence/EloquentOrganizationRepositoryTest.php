<?php

use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\TestCase;
use organizationManagement\sqlInfrastructure\persistence\EloquentOrganizationRepository;

class EloquentOrganizationRepositoryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        DB::beginTransaction();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        DB::rollBack();
    }

    public function test_インサートしたデータがfindByidメソッドで取得できる()
    {
    }
}