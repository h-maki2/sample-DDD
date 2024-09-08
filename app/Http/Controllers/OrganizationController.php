<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\AppServiceProvider;
use organizationManagement\application\organization\OrganizationApplicationService;

class OrganizationController extends Controller
{
    private OrganizationApplicationService $applicationService;

    public function __construct(OrganizationApplicationService $organizationApplicationService)
    {
        $this->applicationService = $organizationApplicationService;
    }

    public function detailedOrganizationInfo(Request $requst)
    {
        $detailedOrganizationInfo = $this->applicationService->detailedOrganizationInfo($requst->get('id'));
    }
}
