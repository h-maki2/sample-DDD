<?php

namespace organizationManagement\application\organization;

use organizationManagement\application\common\OrganizationData;
use organizationManagement\domain\model\common\exception\BusinessErrorException;
use organizationManagement\domain\model\common\exception\DomainException;
use organizationManagement\domain\model\organization\OrganizationId;
use organizationManagement\domain\model\organization\IOrganizationRepository;
use organizationManagement\domain\model\organization\Organization;
use organizationManagement\domain\model\organization\OrganizationName;
use organizationManagement\domain\model\common\IUnitOfWork;
use organizationManagement\domain\model\employee\EmployeeId;
use organizationManagement\domain\model\organization\OrganizationStatus;
use organizationManagement\domain\model\organization\OrganizationType;
use organizationManagement\domain\model\common\exception\IllegalStateException;

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
     * 組織の詳細情報を閲覧する
     */
    public function detailedOrganizationInfo(string $organizationIdString)
    {
        $organization = $this->organizationRepository->findById(new OrganizationId($organizationIdString));

        if ($organization === null) {
            throw new IllegalStateException('組織が存在しません。organizationId: ' . $organizationIdString);
        }
    }

    /**
     * 組織を作成する
     */
    public function createOrganization(string $nameString): string
    {
        $organization = Organization::create(
            $this->organizationRepository->nextIdentity(),
            new OrganizationName($nameString)
        );

        $this->unitOfWork->performTransaction(function () use ($organization) {
            $this->organizationRepository->save($organization);
        });

        return $organization->id()->value();
    }

    /**
     * 組織に従業員を所属させる
     */
    public function assignEmployeesToTheOrganization(
        string $organizationIdString, 
        string $employeeIdString
    ): void
    {
        $organization = $this->organizationRepository->findById(new OrganizationId($organizationIdString));

        $organization->assign(new EmployeeId($employeeIdString));

        $this->unitOfWork->performTransaction(function () use ($organization) {
            $this->organizationRepository->save($organization);
        });
    }

    /**
     * 組織を廃止にする
     */
    public function organizationChangeToAbolition(string $organizationIdString): void
    {
        $organization = $this->organizationRepository->findById(new OrganizationId($organizationIdString));

        $organization->changeToAbolition();

        $this->unitOfWork->performTransaction(function () use ($organization) {
            $this->organizationRepository->save($organization);
        });
    }
}