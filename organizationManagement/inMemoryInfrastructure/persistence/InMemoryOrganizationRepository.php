<?php

namespace organizationManagement\inMemoryInfrastructure\persistence;

use Illuminate\Support\Str;
use organizationManagement\domain\model\employee\EmployeeId;
use organizationManagement\domain\model\organization\IOrganizationRepository;
use organizationManagement\domain\model\organization\Organization;
use organizationManagement\domain\model\organization\OrganizationId;
use organizationManagement\domain\model\organization\OrganizationName;
use organizationManagement\domain\model\organization\OrganizationStatus;
use organizationManagement\domain\model\organization\OrganizationType;

class InMemoryOrganizationRepository implements IOrganizationRepository
{
    private array $organization_table_data;
    private array $organization_employee_table_data;

    public function __construct()
    {
        $this->organization_table_data = $this->organization_table_data();
        $this->organization_employee_table_data = $this->organization_employee_table_data();
    }

    public function findById(OrganizationId $id): ?Organization
    {
        $targetData = $this->organization_table_data[$id->value()];
        $employeeIdList = $this->employeeIdListFrom($id);

        return Organization::reconstruct(
            $id,
            OrganizationType::from($targetData['type']),
            OrganizationStatus::from($targetData['status']),
            new OrganizationName($targetData['name']),
            $employeeIdList
        );
    }

    public function save(Organization $organization): void
    {
        
    }

    public function delete(Organization $organization): void
    {

    }

    public function nextIdentity(): OrganizationId
    {
        return new OrganizationId((string) Str::uuid());
    }

    private function organization_table_data(): array
    {
        // key = organization_id
        return [
            '1' => ['name' => 'test1', 'type' => '1', 'status' => '0'],
            '2' => ['name' => 'test2', 'type' => '0', 'status' => '1'],
        ];
    }

    private function organization_employee_table_data(): array
    {
        return [
            ['1' => '1'], // key = organization_id, value = employee_id
            ['1' => '2'],
            ['1' => '3'],
            ['2' => '4'],
            ['2' => '5'],
        ];
    }

    /**
     * employeeIdのリストを取得
     * @return EmployeeId[]
     */
    private function employeeIdListFrom(OrganizationId $id): array
    {
        $filteredData = array_filter($this->organization_employee_table_data, function ($item) use ($id) {
            return isset($item[$id->value()]);
        });

        return array_map(function ($item) {
            return new EmployeeId(array_values($item)[0]); // employeeIDを取得
        }, $filteredData);
    }
}

