<?php

namespace organizationManagement\domain\model\organization;

use organizationManagement\domain\model\employee\EmployeeId;
use organizationManagement\domain\model\common\exception\IllegalStateException;

class Organization
{
    private OrganizationId $id;
    private OrganizationType $type;
    private OrganizationStatus $status;
    private OrganizationName $name;
    private array $employeeIdList;

    private function __construct(
        OrganizationId $id,
        OrganizationType $type,
        OrganizationStatus $status,
        OrganizationName $name,
        array $employeeIdList
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->status = $status;
        $this->name = $name;
        $this->employeeIdList = $employeeIdList;
    }

    /**
     * @param OrganizationId $id
     * @param OrganizationName $name
     */
    public static function create(
        OrganizationId $id,
        OrganizationName $name
    ): self {
        return new self(
            $id,
            OrganizationType::DEPARTMENT,
            OrganizationStatus::SURVIVES,
            $name,
            []
        );
    }

    /**
     * @param OrganizationId $id
     * @param OrganizationType $type
     * @param OrganizationStatus $status
     * @param OrganizationName $name
     * @param EmployeeId[] $employeeIdList
     */
    public static function reconstruct(
        OrganizationId $id,
        OrganizationType $type,
        OrganizationStatus $status,
        OrganizationName $name,
        array $employeeIdList
    ): self {
        return new self(
            $id,
            $type,
            $status,
            $name,
            $employeeIdList
        );
    }

    public function id(): OrganizationId
    {
        return $this->id;
    }

    public function type(): OrganizationType
    {
        return $this->type;
    }

    public function status(): OrganizationStatus
    {
        return $this->status;
    }

    public function name(): OrganizationName
    {
        return $this->name;
    }

    /**
     * @return EmployeeId[]
     */
    public function employeeIdList(): array
    {
        return $this->employeeIdList;
    }

    /**
     * 従業員を所属させる
     * @param EmployeeId $employeeId
     * @throws CanNotBelongEexception 
     */
    public function assign(EmployeeId $employeeId): void
    {
        if ($this->status->isAbolition()) {
            // 組織が廃止されている場合は所属できない
            throw new IllegalStateException('employeeId: ' . $employeeId->value() . 'の所属に失敗しました。');
        }

        if ($this->alreadyAssigned($employeeId)) {
            // すでに所属済みの従業員だった場合
            throw new IllegalStateException('employeeId: ' . $employeeId->value() . 'の従業員は既に所属済みです。');
        }

        $this->employeeIdList[] = $employeeId;

        $this->updateSection();
    }

    /**
     * 組織の状態を「廃止」に変更
     * @throws CanNotChangeToAbolitionException
     */
    public function changeToAbolition(): void
    {
        if (!$this->canChangeToAbolition()) {
            throw new IllegalStateException('organizationID: ' . $this->id()->value() . 'の廃止処理に失敗しました。');
        }

        $this->status = OrganizationStatus::ABOLITION;
    }

    /**
     * 組織の種別を「課」に更新
     */
    private function updateSection(): void
    {
        if ($this->canUpdateSection()) {
            $this->type = OrganizationType::SECTION;
        }
    }

    /**
     * 組織の種別を「課」に更新できるかを判定
     */
    private function canUpdateSection(): bool
    {
        return $this->hasMoreThan101Employees() && !$this->type->isSection();
    }

    /**
     * 組織を廃止できるかを判定
     */
    private function canChangeToAbolition(): bool
    {
        return $this->countEmployee() === 0;
    }

    private function countEmployee(): int
    {
        return count($this->employeeIdList);
    }

    /**
     * 所属している従業員が101人以上の場合はtrueを返す
     */
    private function hasMoreThan101Employees(): bool
    {
        return $this->countEmployee() >= 101;
    }

    private function alreadyAssigned(EmployeeId $otherEmployeeId): bool
    {
        foreach ($this->employeeIdList as $employeeId) {
            if ($employeeId->equals($otherEmployeeId)) {
                return true;
            }
        }

        return false;
    }
}
