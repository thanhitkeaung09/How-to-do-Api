<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Admin;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Services\FileStorage\FileStorageService;
use Carbon\Traits\Date;
use Database\Factories\UserFactory;
use http\Env\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Response;
use function Pest\Laravel\get;

class AdminService
{
    public function __construct(
        private FileStorageService $fileStorageService,
        private AuthService $authService,
        protected string $message = "Admin Can not be deleted",
    ) {
    }

    public function register($request)
    {
        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->profile_image = $this->fileStorageService->upload(\config('filesystems.folders.profiles'), $request->profile_image);
        $admin->save();
        return $admin;
    }

    public function login($request)
    {
        $admin = Admin::query()->where('email', $request->email)->first();
        if (!$admin) {
            throw new \Exception("Admin Empty");
        }
        if (Hash::check($request->password, $admin->password)) {
            $admin->token = $admin->createToken($admin->email)->plainTextToken;
            return $admin;
        }
        throw new \Exception("Login Fail");
    }

    public function getProfile($request)
    {
        //        dd(\auth()->id());
        $user = auth()->user();
        $user->token = $request->header('token');
        return $user;
    }

    public function logout()
    {
        $this->authService->logout(Auth::user());
        return true;
    }

    public function update_username($request)
    {
        return Auth::user()->update(["name" => $request->name]);
    }

    public function update_userimage($request)
    {
        $this->fileStorageService->delete(Auth::user()->getRawOriginal('profile_image'));

        $path = $this->fileStorageService->upload(\config('filesystems.folders.profiles'), $request->profile_image);
        Auth::user()->update(['profile_image' => $path]);
        return Auth::user();
    }

    public function create_newuser($request)
    {
        $newAdmin = Admin::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "profile_image" => $this->fileStorageService->upload(\config('filesystems.folders.profiles'), $request->profile_image),
            "category_id" => $request->category_id
        ]);
        $newAdmin->assignRole($request->role_id);
        return $newAdmin;
    }

    public function update_password($request)
    {
        return Auth::user()->update(['password' => Hash::make($request->password)]);
    }

    public function view_alladmins()
    {
        return Admin::query()->paginate(5);
    }

    public function view_all()
    {
        return Admin::all();
    }

    public function role_user($request)
    {
        $role = $request->role;
        return Role::with('users')->where('name', $role)->paginate(5);
    }

    public function all_roles()
    {
        return Role::all();
    }

    public function delete_users($type)
    {
        $has_role = Admin::query()->where("id", $type)->first()->hasRole("admin");
        if ($has_role) {
            return $this->message;
        }
        $author = Admin::find($type);
        $author->delete();
        return true;
    }

    public function all_users()
    {
        $users = User::with(['categories'])->withCount(['postReads', 'following_lists', 'likes'])->get();
        return $users;
    }

    public function recent_users()
    {
        return User::query()->with('categories')->withCount(['postReads', 'following_lists', 'likes'])->latest()->paginate(5);
    }

    public function single_user($type)
    {
        $count = Like::query()->where("likeable_type", Post::class)->where("user_id", $type)->count();
        $user = User::with('categories')->withCount(['postReads', 'following_lists'])->find($type);
        $user->likes_count = $count;
        return $user;
    }

    public function recent_all_users($request)
    {
        $date = $request->date;
        return User::query()->with('categories')->withCount(['postReads', 'following_lists', 'likes'])->latest()->when($date, function ($query, $date) {
            $query->whereDate('created_at', $date);
        })->get();
    }

    public function today_user()
    {
        //        return Carbon::now()->toDateString();
        return User::query()->whereDate("created_at", today())->count();
    }

    public function report_total_users()
    {
        return User::count();
    }

    public function group_by_date()
    {
        return User::select('created_at')->selectRaw('count(*)')->groupBy('created_at')->get();
    }

    public function user_search_by_date($request)
    {
        $search_date = $request->date;
        return User::query()->whereDate('created_at', $search_date)->get();
    }

    public function last_week_user()
    {
        $startWeek = Carbon::now()->startOfWeek();
        $endWeek = Carbon::now()->endOfWeek();
        $userCount = User::whereBetween('created_at', [$startWeek, $endWeek])->get();
        foreach ($userCount as $item) {
            $dayName = substr(Carbon::parse($item->created_at)->format('l'), 0, 3);
            $item->dayName = $dayName;
            unset($item->name, $item->email, $item->phone, $item->social_id, $item->social_type, $item->profile_image, $item->language, $item->device_token, $item->created_at, $item->updated_at, $item->deleted_at);
        }

        $newArr = [];
        foreach ($userCount as $value) {
            array_push($newArr, $value->dayName);
        }
        $list = array_count_values($newArr);
        $day = [];
        foreach ($list as $key => $value) {
            array_push($day, $key);
        }
        return [
            'date' => $list,
            'total_user' => $userCount->count()
        ];
    }

    public function last_week_post()
    {
        $startWeek = Carbon::now()->startOfWeek();
        $endWeek = Carbon::now()->endOfWeek();
        $userCount = Post::whereBetween('created_at', [$startWeek, $endWeek])->get();
        foreach ($userCount as $item) {
            $dayName = substr(Carbon::parse($item->created_at)->format('l'), 0, 3);
            $item->dayName = $dayName;
            unset($item->name, $item->email, $item->phone, $item->social_id, $item->social_type, $item->profile_image, $item->language, $item->device_token, $item->updated_at, $item->deleted_at);
        }
        $newArr = [];
        foreach ($userCount as $value) {
            array_push($newArr, $value->dayName);
        }
        $list = array_count_values($newArr);
        $day = [];
        foreach ($list as $key => $value) {
            array_push($day, $key);
        }
        return [
            'date' => $list,
            'total_post' => $userCount->count()
        ];
    }

    public function percentage_user()
    {
        $startWeek = Carbon::now()->startOfWeek();
        $endWeek = Carbon::now()->endOfWeek();
        $userLastWeek = User::whereBetween('created_at', [$startWeek, $endWeek])->count();
        $percentOfLastWeek = ($userLastWeek / 100) * 100;

        $startWeekBefore = Carbon::now()->startOfWeek()->subWeeks(1);
        $endWeekBefore = Carbon::now()->endOfWeek()->subWeeks(1);
        $totalUserBeforeLastWeek = User::whereBetween('created_at', [$startWeekBefore, $endWeekBefore])->count();
        $percentOfBeforeLastWeek = ($totalUserBeforeLastWeek / 100) * 100;

        $averageUser = $percentOfLastWeek - $percentOfBeforeLastWeek;
        if ($averageUser > 0) {
            return $averageUser . ' % up to from the last week';
        }
        return abs($averageUser) . ' % down to from the last week';
    }

    public function percentage_post()
    {
        $startWeek = Carbon::now()->startOfWeek();
        $endWeek = Carbon::now()->endOfWeek();
        $postLastWeek = Post::whereBetween('created_at', [$startWeek, $endWeek])->count();
        $percentOfLastWeek = ($postLastWeek / 100) * 100;

        $startWeekBefore = Carbon::now()->startOfWeek()->subWeeks(1);
        $endWeekBefore = Carbon::now()->endOfWeek()->subWeeks(1);
        $totalPostBeforeLastWeek = Post::whereBetween('created_at', [$startWeekBefore, $endWeekBefore])->count();
        $percentOfBeforeLastWeek = ($totalPostBeforeLastWeek / 100) * 100;

        $averagePost = $percentOfLastWeek - $percentOfBeforeLastWeek;
        if ($averagePost > 0) {
            return $averagePost . ' % up to from the last week';
        }
        return abs($averagePost) . ' % down to from the last week';
    }
}
