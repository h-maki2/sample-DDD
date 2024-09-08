<?php

namespace organizationManagement\domain\model\organization;

interface IOrganizationRepository
{
    public function findById(OrganizationId $id): ?Organization;

    public function save(Organization $organization): void;

    public function delete(OrganizationId $id): void;

    public function nextIdentity(): OrganizationId;
}
