<?php

namespace organizationManagement\domain\model\common;

interface IUnitOfWork
{
    public function beginTransaction(): void;
    public function commit(): void;
    public function rollback(): void;
    public function performTransaction(callable $transactionalFunction);
}