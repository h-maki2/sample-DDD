<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use organizationManagement\sqlInfrastructure\persistence\EloquentEmployeeRepository;
use organizationManagement\sqlInfrastructure\service\EloquentOrganizationQueryService;
use organizationManagement\sqlInfrastructure\persistence\EloquentOrganizationRepository;
use organizationManagement\sqlInfrastructure\persistence\EloquentUnitOfWork;
use organizationManagement\application\organization\IOrganizationQueryService;
use organizationManagement\domain\model\employee\IEmployeeRepository;
use organizationManagement\domain\model\organization\IOrganizationRepository;
use organizationManagement\domain\model\common\AUnitOfWork;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IOrganizationRepository::class, EloquentOrganizationRepository::class);
        $this->app->bind(IOrganizationQueryService::class, EloquentOrganizationQueryService::class);
        $this->app->bind(AUnitOfWork::class, EloquentUnitOfWork::class);
        $this->app->bind(IEmployeeRepository::class, EloquentEmployeeRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
