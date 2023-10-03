<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\UserData;
use App\Http\Requests\Api\UpdateUserImageRequest;
use App\Http\Resources\UserResource;
use App\Models\Admin;
use App\Models\Post;
use App\Models\User;
use App\Services\FileStorage\FileStorageService;
use App\Enums\Language;
use App\Events\LikeEvent;
use App\Events\PlayGroundEvent;
use App\Events\ReadEvent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class UserService
{
    public function __construct(
        private FileStorageService $fileStorageService
    ) {
    }

    public function findBySocialType(string $id): ?User
    {
        return User::query()
            ->where('social_id', $id)
            ->first();
    }

    public function create(UserData $userData)
    {
        $email = User::query()->where("email", $userData->email)->exists();
        if ($email) {
            throw new Exception('Email already Exists', 300);
        }
        $path = $this->fileStorageService->put(
            config('filesystems.folders.icons'),
            $userData->profile,
        );
        return User::create([
            ...$userData->toArray(),
            'profile_image' => $path,
        ]);
    }

    public function getProfile(Request $request)
    {
        $exists = DB::table('category_user')->where('user_id', auth()->id())->first();
        $user = auth()->user();
        $user->token = $request->header('token');
        $following = $this->userFollowerListShow();
        $user->following = $following['following'];
        $user->read_article = $following['read_article'];
        $user->like_article = $following['like_article'];
        if ($exists) {
            $user->is_favourite = true;
        } else {
            $user->is_favourite = false;
        }
        return $user;
    }

    public function updateUserName(User $user, string $newName): bool
    {
        return $user->update(['name' => $newName]);
    }

    public function updateUserImage(User $user, UploadedFile $newImage)
    {
        $this->fileStorageService->delete($user->getRawOriginal('profile_image'));

        $path = $this->fileStorageService->upload(\config('filesystems.folders.profiles'), $newImage);
        $user->update(['profile_image' => $path]);
        return true;
    }

    public function updateLanguage(User $user, Language $language)
    {
        return $user->update(['language' => $language->value]);
    }

    public function userFollowerListStore(Request $request, $type)
    {
        $check = $this->checkExits($type, "admin_users", "admin_id");
        if ($check) {
            Admin::find($type)->users()->detach(\auth()->id());
            return false;
        }
        $this->addReadOrNot($type, Admin::class);
        return true;
    }

    public function userFollowerListShow()
    {
        $following = DB::table('admin_users')->where('user_id', \auth()->id())->count();
        $read_article = $this->readList();
        $like_list = $this->likeList();
        return [
            "following" => $following,
            "read_article" => $read_article,
            "like_article" => $like_list
        ];
    }

    public function readArticle(Request $request, $type)
    {
        $check = $this->checkExits($type, "post_reads", "post_id");
        $post = Post::find($type);
        $read_count = DB::table("post_reads")->where("post_id", $type)->count();
        event(new ReadEvent(["id" => intval($type), "read_count" => $read_count, "type" => "post_read"]));

        if ($check) {
            return true;
        }
        $this->addReadOrNot($type, Post::class);
        return true;
    }

    public function checkExits($value, $table, $table_id)
    {
        return DB::table($table)
            ->where('user_id', \auth()->id())
            ->where($table_id, $value)
            ->exists();
    }

    public function addReadOrNot($value, $model)
    {
        $model::find($value)->users()->attach(\auth()->id());
    }

    public function readList()
    {
        return DB::table('post_reads')->where('user_id', \auth()->id())->count();
    }

    public function likeList()
    {
        return DB::table('likes')->where("user_id", \auth()->id())->where("likeable_type", Post::class)->count();
    }

    public function likeArticle($request, $type)
    {
        $check = DB::table("likes")
            ->where('user_id', \auth()->id())
            ->where('likeable_id', $type)->where("likeable_type", Post::class)
            ->exists();

        $post = Post::find($type);
        if ($check) {
            $post->likes()->where('user_id', auth()->id())->delete();
            $status = false;
        } else {
            $post->likes()->create(["user_id" => \auth()->id()]);
            $status = true;
        }
        //need to fix pusher account

        // event(new LikeEvent(["id" => $post->id, "likes_count" => $post->likes()->count(), "type" => "post_like"]));
        return $status;
    }

    public function get_author($type)
    {
        $admin = Admin::with('category', 'users')->find($type);
        $users = $admin->users;
        if (count($users)) {
            foreach ($users as $user) {
                $admin->is_follow = $user->id === \auth()->id();
            }
            unset($admin->users);
            return $admin;
        } else {
            $admin->is_follow = false;
            unset($admin->users);
            return $admin;
        }
    }

    public function update_token($request)
    {
        $token = PersonalAccessToken::findToken($request->token);
        // return $token;
        $userInfo = $token->tokenable;
        if ($userInfo) {
            $userInfo->device_token = $request->fcmtoken;
            $userInfo->update();
            return true;
        } else {
            return false;
        }
    }
}
