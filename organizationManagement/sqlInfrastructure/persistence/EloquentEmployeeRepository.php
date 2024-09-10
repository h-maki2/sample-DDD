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
        $eloquentEmployee = $this->eloquentEmployeeFrom($id);
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
        return Employee::reconstruct(
            new EmployeeId($eloquentEmployee->id),
            new EmployeeName($eloquentEmployee->name),
            $eloquentEmployee->retired
        );
    }

    private function toEloquent(Employee $employee): EloquentEmployee
    {
        $eloquentEmployee = $this->eloquentEmployeeFrom($employee->id());

        if ($eloquentEmployee === null) {
            $eloquentEmployee = new EloquentEmployee();
            $eloquentEmployee->id = $employee->id()->value();
        }
        
        $eloquentEmployee->name = $employee->name()->value();
        $eloquentEmployee->retired = $employee->isRetired();
        return $eloquentEmployee;
    }

    private function eloquentEmployeeFrom(EmployeeId $id): ?EloquentEmployee
    {
        return EloquentEmployee::find($id->value());
    }
}