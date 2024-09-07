<?php

namespace organizationManagement\application\organization;

use organizationManagement\domain\model\organization\OrganizationId;

interface IOrganizationQueryService
{
    /**
     * 組織の詳細情報を取得する
     * @param OrganizationId $id
     * @return ?DetailedOrganizationInfo
     */
    public function detailedOrganizationInfo(OrganizationId $id): ?DetailedOrganizationInfo;
}