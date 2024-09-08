<?php

namespace organizationManagement\domain\model\common;

abstract class AUnitOfWork
{
    abstract protected function beginTransaction(): void;
    abstract protected function commit(): void;
    abstract protected function rollback(): void;
    
    public function performTransaction(callable $transactionalFunction)
    {
        try {
            $this->beginTransaction();
            $transactionalFunction();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
}