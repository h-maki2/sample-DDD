<?php

namespace organizationManagement\sqlInfrastructure\persistence;

use Illuminate\Support\Facades\DB;
use organizationManagement\domain\model\common\AUnitOfWork;

class EloquentUnitOfWork extends AUnitOfWork
{
    protected function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    protected function commit(): void
    {
        DB::commit();
    }

    protected function rollback(): void
    {
        DB::rollBack();
    }
}