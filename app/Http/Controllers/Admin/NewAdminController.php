<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiSuccessResponse;
use App\Services\AdminService;
use Illuminate\Http\Request;
use Spatie\FlareClient\Api;

class NewAdminController extends Controller
{
    public function __construct(
        private AdminService $adminService
    )
    {
    }

    public function create_newuser(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminService->create_newuser($request)
        );
    }

    public function delete_users(string $type)
    {
        return new ApiSuccessResponse(
            $this->adminService->delete_users($type)
        );
    }

    public function view_alladmins(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminService->view_alladmins()
        );
    }

    public function view_all(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminService->view_all()
        );
    }

    public function role_user(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminService->role_user($request)
        );
    }

    public function all_roles(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminService->all_roles()
        );
    }

    public function admin_list(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminService->admin_list()
        );
    }

    public function all_users(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminService->all_users()
        );
    }

    public function recent_users(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminService->recent_users()
        );
    }

    public function single_user(string $type): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminService->single_user($type)
        );
    }

    public function recent_all_users(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminService->recent_all_users($request)
        );
    }

    public function today_user(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminService->today_user()
        );
    }

    public function report_total_users(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminService->report_total_users()
        );
    }

    public function group_by_date(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminService->group_by_date()
        );
    }

    public function user_search_by_date(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminService->user_search_by_date($request)
        );
    }

    public function last_week_user(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminService->last_week_user()
        );
    }

    public function last_week_post(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminService->last_week_post()
        );
    }

    public function percentage_user(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->adminService->percentage_user()
        );
    }

    public function percentage_post()
    {
        return new ApiSuccessResponse(
            $this->adminService->percentage_post()
        );
    }


}
