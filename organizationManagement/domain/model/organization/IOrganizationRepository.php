<?php

namespace organizationManagement\domain\model\organization;

interface IOrganizationRepository
{
    public function findById(OrganizationId $id): ?Organization;

    public function save(Organization $organization): void;

    /**
     * 組織の種別を「廃止」に変更する
     */
    public function delete(Organization $organization): void;

    public function nextIdentity(): OrganizationId;
}
