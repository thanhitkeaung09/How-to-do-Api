<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ShowCategoryResource;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\Admin;
use App\Models\Category;
use App\Models\User;

//use http\Env\Request;
use App\Services\FileStorage\FileStorageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    public function __construct(
        private FileStorageService $fileStorageService,
    ) {
    }

    //User Api
    public function allCategories()
    {
        $categories = Category::with("posts")->get();
        foreach ($categories as $category) {
            $category->is_selected = $category->users->where('id', \auth()->id())->count() > 0;
            $category->count = $category->posts->count();
            unset($category->posts);
            unset($category->users);
        }
        return $categories;
    }

    public function storeUserCategory($request)
    {
        $categories = $request->ids;
        $check = DB::table('category_user')->where("user_id", auth()->id())->exists();
        if ($check) {
            DB::table('category_user')->where("user_id", auth()->id())->delete();
            foreach ($categories as $category) {
                $cat = Category::find($category);
                $cat->users()->attach(auth()->id());
            }
            return true;
        } else {
            foreach ($categories as $category) {
                $cat = Category::find($category);
                $cat->users()->attach(auth()->id());
            }
        }
        // DB::table('category_user')->truncate();
        // foreach ($categories as $category) {
        //     $cat = Category::find($category);
        //     $check = DB::table('category_user')->where("user_id", auth()->id())->exists();
        //     if ($check) {
        //         return "shi";
        //         // DB::table('category_user')->truncate();
        //     } else {
        //         $cat->users()->attach(auth()->id());
        //     }
        // }
        return true;
    }

    public function showUserCategory()
    {
        return Auth::user()->load('categories');
    }

    //Super Admin Api
    public function create_category($request)
    {
        return Category::create([
            "name" => $request->name,
            "icon" => $this->fileStorageService->upload(\config('filesystems.folders.profiles'), $request->icon),
            "color" => $request->color
        ]);
    }

    public function view_allcategory()
    {
        return Category::withCount('posts')->get();
    }

    public function delete_category($type)
    {
        return Category::find($type)->delete();
    }

    public function update_category($type, $request)
    {
        $category = Category::find($type);
        $category->name = $request->name;
        $category->icon = $this->fileStorageService->upload(\config('filesystems.folders.profiles'), $request->icon);
        $category->color = $request->color;
        $category->update();
        return $category;
    }

    public function search_category($request)
    {
        return Category::withCount('posts')->where("name", "like", "%" . $request->keyword . "%")->get();
    }

    public function filter_admin()
    {
        return new ApiSuccessResponse(Category::with('admin')->get());
    }
}
