<?php

namespace organizationManagement\sqlInfrastructure\persistence;

use Illuminate\Support\Str;
use App\Models\Employee as EloquentEmployee;
use organizationManagement\domain\model\employee\Employee;
use organizationManagement\domain\model\employee\IEmployeeRepository;
use organizationManagement\domain\model\employee\EmployeeId;
use organizationManagement\domain\model\employee\EmployeeName;

class EloquentEmployeeRepository implements IEmployeeRepository
{
    public function findById(EmployeeId $id): ?Employee
    {
        $eloquentEmployee = EloquentEmployee::find($id->value());
        if ($eloquentEmployee === null) {
            return null;
        }

        return $this->toDomain($eloquentEmployee);
    }

    public function save(Employee $employee): void
    {
        $eloquentEmployee = $this->toEloquent($employee);
        $eloquentEmployee->save();
    }

    public function delete(Employee $employee): void
    {
        $eloquentEmployee = $this->toEloquent($employee);
        $eloquentEmployee->deelte();
    }

    public function nextIdentity(): EmployeeId
    {
        return new EmployeeId((string) Str::uuid());
    }

    private function toDomain(EloquentEmployee $eloquentEmployee): Employee
    {
        return new Employee(
            new EmployeeId($eloquentEmployee->id),
            new EmployeeName($eloquentEmployee->name),
            $eloquentEmployee->retired
        );
    }

    private function toEloquent(Employee $employee): EloquentEmployee
    {
        $eloquentEmployee = new EloquentEmployee();
        $eloquentEmployee->id = $employee->id()->value();
        $eloquentEmployee->name = $employee->name()->value();
        $eloquentEmployee->retired = $employee->isRetired();
        return $eloquentEmployee;
    }
}