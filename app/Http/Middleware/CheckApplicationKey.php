<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiErrorResponse;
use App\Models\ApplicationKey;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckApplicationKey
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $appId = $request->header('app-id');
        $appSecrete = $request->header('app-secrete');


        if (!($appId && $appSecrete)) {
            return $this->unauthorizedResponse();
        };

        $appKey = ApplicationKey::query()
            ->where('app_id', $appId)
            ->where('app_secrete', $appSecrete)
            ->first();

        if (!isset($appKey)) {
            return $this->unauthorizedResponse();
        }

        if($appKey->obsoleted){
            return $this->oudatedResponse;
        }
        return $next($request);


    }

    private function unauthorizedResponse(): ApiErrorResponse
    {
        return new ApiErrorResponse(
            message: __('messages.unauthorized'),
            status: Response::HTTP_UNAUTHORIZED,
        );
    }

    private function oudatedResponse(): ApiErrorResponse
    {
        return new ApiErrorResponse(
            message: __('message.outdated'),
            status: Response::HTTP_UPGRADE_REQUIRED
        );
    }
}
