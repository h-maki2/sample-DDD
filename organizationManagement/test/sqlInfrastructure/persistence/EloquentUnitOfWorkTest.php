<?php

use Tests\TestCase;
use organizationManagement\sqlInfrastructure\persistence\EloquentEmployeeRepository;
use organizationManagement\sqlInfrastructure\persistence\EloquentUnitOfWork;
use organizationManagement\test\common\helper\employee\TestEmployeeFactory;

class EloquentUnitOfWorkTest extends TestCase
{
    private EloquentEmployeeRepository $employeeRepository;
    private EloquentUnitOfWork $unitOfWork;

    public function setUp(): void
    {
        parent::setUp();
        $this->employeeRepository = new EloquentEmployeeRepository();
        $this->unitOfWork = new EloquentUnitOfWork();
    }

    public function test_トランザクションが正常にコミットできる()
    {
        // given: テストデータの準備
        $testEmployeeFactory = new TestEmployeeFactory();
        $employeeId = $this->employeeRepository->nextIdentity();
        $employee = $testEmployeeFactory->create($employeeId);

        // when: トランザクションを実行し、結果をコミットする
        $this->unitOfWork->performTransaction(function () use ($employee) {
            $this->employeeRepository->save($employee);
        });
        $reconstructedEmployee = $this->employeeRepository->findById($employeeId);

        // then
        $this->assertEquals($employee->id()->value(), $reconstructedEmployee->id()->value());
        $this->assertEquals($employee->name()->value(), $reconstructedEmployee->name()->value());
        $this->assertEquals($employee->isRetired(), $reconstructedEmployee->isRetired());
    }

    public function test_トランザクションが正常にロールバックできる()
    {
        // given: テストデータの準備
        $testEmployeeFactory = new TestEmployeeFactory();
        $employeeId = $this->employeeRepository->nextIdentity();
        $employee = $testEmployeeFactory->create($employeeId);

        // when: トランザクションの中で例外を発生させる
        try {
            $this->unitOfWork->performTransaction(function () use ($employee) {
                $this->employeeRepository->save($employee);
                throw new Exception();
            });
        } catch (Exception $e) {   
        }
        $reconstructedEmployee = $this->employeeRepository->findById($employeeId);

        // then: 結果が取得できないことを確認
        $this->assertNull($reconstructedEmployee);
    }
}
