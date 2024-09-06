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

        try {
            $organization->assign(new EmployeeId($employeeIdString));
        } catch (BusinessErrorException $e) {

        }

        $this->unitOfWork->performTransaction(function () use ($organization) {
            $this->organizationRepository->save($organization);
        });
    }

    public function organizationChangeToAbolition(string $organizationIdString): void
    {
        $organization = $this->organizationRepository->findById(new OrganizationId($organizationIdString));

        try {
            $organization->changeToAbolition();
        } catch (BusinessErrorException $e) {

        }

        $this->unitOfWork->performTransaction(function () use ($organization) {
            $this->organizationRepository->save($organization);
        });
    }
}