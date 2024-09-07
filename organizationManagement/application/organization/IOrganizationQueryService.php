<?php

namespace organizationManagement\application\organization;

use organizationManagement\domain\model\organization\OrganizationId;

interface IOrganizationQueryService
{
    public function detailedOrganizationInfo(OrganizationId $id): ?DetailedOrganizationInfo;
}