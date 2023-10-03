<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateUserImageRequest;
use App\Http\Requests\Api\UpdateUserLanguageRequest;
use App\Http\Requests\Api\UpdateUserNameRequest;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\User;

use App\Services\UserService;
use App\Enums\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService,
    ) {
    }

    public function profile(User $user, Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse($this->userService->getProfile($request));
    }

    public function updateUserName(UpdateUserNameRequest $request, User $user): ApiSuccessResponse
    {
        $this->checkOwner($user);
        return new ApiSuccessResponse(
            data: $this->userService->updateUserName($user, $request->validated('name'))
        );
    }

    public function updateUserImage(UpdateUserImageRequest $request)
    {
        $user = User::find($request->user_id);
        $this->checkOwner($user);
        return new ApiSuccessResponse(
            data: $this->userService->updateUserImage(
                user: $user,
                newImage: $request->validated('profile_image'),
            )
        );
    }

    public function updateUserLanguage(UpdateUserLanguageRequest $request, User $user): ApiSuccessResponse
    {
        $this->checkOwner($user);
        return new ApiSuccessResponse(
            data: $this->userService->updateLanguage(
                user: $user,
                language: Language::from($request->validated('language'))
            )
        );
    }

    public function userFollowerListStore(Request $request, string $type): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->userService->userFollowerListStore($request, $type)
        );
    }

    public function userFollowerListShow(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->userService->userFollowerListShow()
        );
    }

    public function readArticle(Request $request, string $type): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->userService->readArticle($request, $type)
        );
    }

    public function readList()
    {
        //        return "post list";
        return new ApiSuccessResponse(
            $this->userService->readList()
        );
    }

    public function likeList(): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->userService->likeList()
        );
    }

    public function likeArticle(Request $request, string $type): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->userService->likeArticle($request, $type)
        );
    }

    public function checkOwner(User $user)
    {
        abort_unless(
            auth()->user()->is($user),
            Response::HTTP_FORBIDDEN,
            __('messages.without_permission')
        );
    }

    public function get_author($type): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->userService->get_author($type)
        );
    }

    public function update_token(Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            $this->userService->update_token($request)
        );
    }
}
