<?php

namespace organizationManagement\application\organization;

use organizationManagement\domain\model\common\AUnitOfWork;
use organizationManagement\domain\model\organization\OrganizationId;
use organizationManagement\domain\model\organization\IOrganizationRepository;
use organizationManagement\domain\model\organization\Organization;
use organizationManagement\domain\model\organization\OrganizationName;
use organizationManagement\domain\model\employee\EmployeeId;

class OrganizationApplicationService
{
    private IOrganizationRepository $organizationRepository;
    private IOrganizationQueryService $organizationQueryService;
    private AUnitOfWork $unitOfWork;

    public function __construct(
        IOrganizationRepository $organizationRepository,
        IOrganizationQueryService $organizationQueryService,
        AUnitOfWork $unitOfWork
    )
    {
        $this->organizationRepository = $organizationRepository;
        $this->organizationQueryService = $organizationQueryService;
        $this->unitOfWork = $unitOfWork;
    }

    /**
     * 組織の詳細情報を閲覧する
     */
    public function detailedOrganizationInfo(string $organizationIdString): DetailedOrganizationInfo
    {
        $detailedOrganizationInfo = $this->organizationQueryService->detailedOrganizationInfo(new OrganizationId($organizationIdString));
        if ($detailedOrganizationInfo === null) {
            throw new \InvalidArgumentException('組織の詳細情報が存在しません。organizationId: ' . $organizationIdString);
        }

        return $detailedOrganizationInfo;
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
        if ($organization === null) {
            throw new \InvalidArgumentException('組織が存在しません。');
        }

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
        if ($organization === null) {
            throw new \InvalidArgumentException('組織が存在しません。');
        }

        $organization->changeToAbolition();

        $this->unitOfWork->performTransaction(function () use ($organization) {
            $this->organizationRepository->save($organization);
        });
    }
}