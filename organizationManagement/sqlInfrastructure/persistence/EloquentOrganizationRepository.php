<?php

namespace organizationManagement\sqlInfrastructure\persistence;

use Illuminate\Support\Str;
use App\Models\Organization as EloquentOrganization;
use organizationManagement\domain\model\employee\EmployeeId;
use organizationManagement\domain\model\organization\IOrganizationRepository;
use organizationManagement\domain\model\organization\Organization;
use organizationManagement\domain\model\organization\OrganizationId;
use organizationManagement\domain\model\organization\OrganizationName;
use organizationManagement\domain\model\organization\OrganizationType;
use organizationManagement\domain\model\organization\OrganizationStatus;

class EloquentOrganizationRepository implements IOrganizationRepository
{
    public function findById(OrganizationId $id): ?Organization
    {
        $eloquentOrganization = EloquentOrganization::with('employees')->find($id->value());
        if ($eloquentOrganization === null) {
            return null;
        }

        return $this->toDomain($eloquentOrganization);
    }

    public function save(Organization $organization): void
    {
        $eloquentOrganization = $this->toEloquent($organization);
        $eloquentOrganization->save();

        $emmployeeIdList = array_map(function ($employeeId) {
            return $employeeId->value();
        }, $organization->employeeIdList());

        $eloquentOrganization->employees()->sync($emmployeeIdList);
    }

    public function delete(Organization $organization): void
    {
        $eloquentOrganization = $this->toEloquent($organization);
        $eloquentOrganization->delete();
    }

    public function nextIdentity(): OrganizationId
    {
        return new OrganizationId((string) Str::uuid());
    }

    private function toDomain(EloquentOrganization $eloquentOrganization): Organization
    {
        return Organization::reconstruct(
            new OrganizationId($eloquentOrganization->id),
            OrganizationType::from($eloquentOrganization->type),
            OrganizationStatus::from($eloquentOrganization->status),
            new OrganizationName($eloquentOrganization->name),
            $eloquentOrganization->employees->map(function ($employee) {
                return new EmployeeId($employee->id);
            })->toArray()
        );
    }

    private function toEloquent(Organization $organization): EloquentOrganization
    {
        $eloquentOrganization = new EloquentOrganization();
        $eloquentOrganization->id = $organization->id()->value();
        $eloquentOrganization->type = $organization->type()->value;
        $eloquentOrganization->status = $organization->status()->value;
        $eloquentOrganization->name = $organization->name()->value();
        return $eloquentOrganization;
    }
}