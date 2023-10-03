<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\UserData;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResponseResource;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\Admin;
use App\Models\User;
use App\Services\FileStorage\FileStorageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthService
{
    public function __construct(
        private UserService $userService,
        private FileStorageService $fileStorageService,
    ) {
    }

    public function login(UserData $userData)
    {
        $exists = DB::table('category_user')->where('user_id', $userData->email)->exists();
        $user = $this->userService->findBySocialType(
            id: $userData->socialId,
        );

        if (is_null($user)) {
            $user = $this->userService->create($userData);
            return $user;
        } else {
            $this->fileStorageService->update(
                $user->getRawOriginal('profile_image'),
                $userData->profile
            );
        }
        $this->generateToken($user, $userData->socialId);
        return $user;
    }

    public function user_follower_list()
    {
        return DB::table('admin_users')->where('user_id', \auth()->id())->get();
        return "user follower list";
    }

    public function logout(User|Admin $user)
    {
        $this->revokeTokens($user);
    }

    public function generateToken(User|Admin $model, string $unique,): User|Admin
    {
        return tap($model, function ($model) use ($unique) {
            $model->token = $model->createToken($unique)->plainTextToken;
        });
    }

    public function revokeTokens(User|Admin $user): void
    {
        $user->tokens()->delete();
    }

    public function user_delete()
    {
        //need to delete rest
        Auth::user()->categories()->delete();
        auth()->user()->delete();
        return new ApiSuccessResponse('Account Deleted Successfully');
    }
}
