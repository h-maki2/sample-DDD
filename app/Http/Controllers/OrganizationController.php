<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\AppServiceProvider;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use organizationManagement\application\organization\OrganizationApplicationService;
use Illuminate\Database\QueryException;

class OrganizationController extends Controller
{
    private OrganizationApplicationService $applicationService;

    public function __construct(OrganizationApplicationService $organizationApplicationService)
    {
        $this->applicationService = $organizationApplicationService;
    }

    /**
     * 組織情報の詳細画面
     */
    public function detail(Request $request): Response
    {
        try {
            $detailedOrganizationInfo = $this->applicationService->detailedOrganizationInfo($request->get('id', ''));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->view('errors.500', [], 500);
        }

        return response()->view('organization.detail', ['detail' => $detailedOrganizationInfo]);
    }

    /**
     * 新たな組織を作成する
     */
    public function create(Request $request)
    {
        try {
            $id = $this->applicationService->createOrganization($request->post('name', ''));
        }
        catch (QueryException $e) {
            Log::error($e->getMessage());
            //ToDo なんかしらの処理を行う
        } 
        catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->view('errors.500', [], 500);
        }

        redirect()->route('organization.detail', ['id' => $id]);
    }

    /**
     * 従業員を組織に所属させる
     */
    public function assign(Request $request)
    {
        $organizationId = $request->post('organizationId', '');
        $employeeId = $request->post('employeeId', '');

        try {
            $this->applicationService->assignEmployeesToTheOrganization($organizationId, $employeeId);
        }
        catch (QueryException $e) {
            Log::error($e->getMessage());
            //ToDo なんかしらの処理を行う
        } 
        catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->view('errors.500', [], 500);
        }

        redirect()->route('organization.detail', ['id' => $organizationId]);
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
        catch (QueryException $e) {
            Log::error($e->getMessage());
            //ToDo なんかしらの処理を行う
        } 
        catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->view('errors.500', [], 500);
        }

        redirect()->route('organization.detail', ['id' => $id]);
    }
}
