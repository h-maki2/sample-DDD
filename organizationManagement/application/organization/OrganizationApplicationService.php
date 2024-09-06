<?php

namespace organizationManagement\application\organization;

use organizationManagement\domain\model\organization\OrganizationId;
use organizationManagement\domain\model\organization\IOrganizationRepository;
use organizationManagement\domain\model\organization\Organization;
use organizationManagement\domain\model\organization\OrganizationName;
use organizationManagement\domain\model\common\IUnitOfWork;

class OrganizationApplicationService
{
    private IOrganizationRepository $organizationRepository;
    private IUnitOfWork $unitOfWork;

    public function __construct(
        IOrganizationRepository $organizationRepository,
        IUnitOfWork $unitOfWork
    )
    {
        $this->organizationRepository = $organizationRepository;
        $this->unitOfWork = $unitOfWork;
    }

    /**
     * 組織を作成する
     */
    public function createOrganization(string $nameString)
    {
        $organization = Organization::create(
            $this->organizationRepository->nextIdentity(),
            new OrganizationName($nameString)
        );

        $this->unitOfWork->performTransaction(function () use ($organization) {
            $this->organizationRepository->save($organization);
        });
    }

    /**
     * 組織に従業員を所属させる
     */
    public function assignEmployeesToTheOrganization()
    {

    }
}