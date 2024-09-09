<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use organizationManagement\application\organization\OrganizationApplicationService;
use Illuminate\Http\JsonResponse;

class OrganizationController extends Controller
{
    private OrganizationApplicationService $applicationService;

    public function __construct(OrganizationApplicationService $organizationApplicationService)
    {
        $this->applicationService = $organizationApplicationService;
    }

    /**
     * 組織情報の詳細
     */
    public function detail(Request $request): JsonResponse
    {
        try {
            $detailedOrganizationInfo = $this->applicationService->detailedOrganizationInfo($request->get('id', ''));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }

        return response()->json($detailedOrganizationInfo, 200);
    }

    /**
     * 新たな組織を作成する
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $id = $this->applicationService->createOrganization($request->post('name', ''));
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }

        return response()->json( ['organizationId' => $id], 200);
    }

    /**
     * 従業員を組織に所属させる
     */
    public function assign(Request $request): JsonResponse
    {
        $organizationId = $request->post('organizationId', '');
        $employeeId = $request->post('employeeId', '');

        try {
            $this->applicationService->assignEmployeesToTheOrganization($organizationId, $employeeId);
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'internal server error'], 500);
        }

        return response()->json(['organizationId' => $organizationId], 200);
    }

    /**
     * 組織を廃止する
     */
    public function abolition(Request $request)
    {
        $id = $request->post('id', '');

        try {
            $this->applicationService->organizationChangeToAbolition($id);
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'internal server error'], 500);
        }

        return response()->json(['message' => 'success'], 200);
    }
}
