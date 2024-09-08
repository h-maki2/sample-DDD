<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\AppServiceProvider;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use organizationManagement\application\organization\OrganizationApplicationService;

class OrganizationController extends Controller
{
    private OrganizationApplicationService $applicationService;

    public function __construct(OrganizationApplicationService $organizationApplicationService)
    {
        $this->applicationService = $organizationApplicationService;
    }

    public function detailedOrganizationInfo(Request $requst): Response
    {
        try {
            $detailedOrganizationInfo = $this->applicationService->detailedOrganizationInfo($requst->get('id'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->view('errors.500', [], 500);
        }

        return response()->view('organization.detail', ['detailedOrganizationInfo' => $detailedOrganizationInfo]);
    }
}
