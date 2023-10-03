<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CreateCategoryController;
use App\Http\Controllers\Admin\CreateCommentController;
use App\Http\Controllers\Admin\CreatePostController;
use App\Http\Controllers\Admin\NewAdminController;
use App\Http\Controllers\Api\FallbackRouteController;
use App\Http\Controllers\Api\ImageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\AdminCreateAdController;
use App\Http\Controllers\AppVersionController;

//Route::get("/admin", [AdminController::class, 'index'])->name('admin:get:posts');
Route::middleware('auth:admin')->group(function () {
    //Logout
    Route::delete('/logout', [AdminAuthController::class, 'logout'])->name('admin:logout:delete');

    //Super Admin and Admin Access

    //Profile Detail
    Route::get('/admin_profile_detail', [AdminAuthController::class, 'getProfile'])->name('admin:get:detail');
    //Update Admin Name
    Route::patch('/user_name', [AdminAuthController::class, 'update_username'])->name('admin:patch:username');
    //Update Admin image
    Route::post('/user_image', [AdminAuthController::class, 'update_userimage'])->name('admin:post:userimage');
    //Update Admin Password
    Route::patch('/user_password', [AdminAuthController::class, 'update_password'])->name('admin:patch:userpassword');

    //Super Admin Access

    //Super Admin Create Admin
    Route::post('/new_user', [NewAdminController::class, 'create_newuser'])->name('admin:post:newuser')->middleware('can:admin-create-author');
    //Super Admin View all Admins
    Route::get('/all_admins', [NewAdminController::class, 'view_alladmins'])->name('admin:get:all_admins')->middleware('can:admin-read-author');
    //Super Admin View all admins and authors not pagination
    Route::get('/all_admins&authors', [NewAdminController::class, 'view_all'])->name('admin:get:all')->middleware('can:admin-read-author');
    //Super Admin Search by role
    Route::get('/search_role', [NewAdminController::class, 'role_user'])->name('get:search_role');
    //Super Admin View Roles
    Route::get('/all_roles', [NewAdminController::class, 'all_roles'])->name('get:all_roles');
    //Super Admin Delete Users
    Route::delete('/delete_user/{user_id}', [NewAdminController::class, 'delete_users'])->name('admin:delete:admin')->middleware('can:admin-delete-author');
    //Super Admin Create Category
    Route::post('/new_category', [CreateCategoryController::class, 'create_category'])->name('admin:create:category')->middleware('can:admin-create-category');
    //Super Admin View Category
    Route::get('/all_category', [CreateCategoryController::class, 'view_allcategory'])->name('admin:view:category');
    //Super Admin Delete Category
    Route::delete('/delete_category/{category_id}', [CreateCategoryController::class, 'delete_category'])->name('admin:delete:category')->middleware('can:admin-delete-category');
    //Super Admin Update Category
    Route::post('update_category/{category_id}', [CreateCategoryController::class, 'update_category'])->name('admin:update:category')->middleware('can:admin-update-category');
    //Super Admin Search Category
    Route::get('search_category', [CreateCategoryController::class, 'search_category'])->name('admin:search:category');
    //Super Admin Get 5 recent user lists
    Route::get("recent_users", [NewAdminController::class, 'recent_users'])->name("admin:get:recent_users");
    //Get Sing User from recent user list
    Route::get("recent_user/{user_id}", [NewAdminController::class, 'single_user'])->name("admin:get:single:user");
    //Super Admin Get all recent user lists
    Route::get('recent_all_users', [NewAdminController::class, 'recent_all_users'])->name("admin:get:recent_all_users");
    //Post Create By Super Admin & Author
    Route::post('/create_posts', [CreatePostController::class, 'create_post'])->name('admin:create:post');
    //Post View all By Super Admin & Author
    Route::get('/view_all_posts', [CreatePostController::class, 'view_post'])->name('admin:view:post');
    //Post Delete By Super Admin & Author
    Route::delete('/delete_posts/{post_id}', [CreatePostController::class, 'delete_post'])->name('admin:delete:post');
    //Post Update By Super Admin & Author
    Route::post('/update_posts/{post_id}', [CreatePostController::class, 'update_post'])->name('admin:update:post');
    //Post Total Count by Super Admin & Author
    Route::get('total_posts', [CreatePostController::class, 'total_posts'])->name('admin:total:post');
    //Readers Count by Super Admin & Author
    Route::get('total_readers', [CreatePostController::class, 'total_readers'])->name('admin:total:reader');
    //New User Count By Super Admin & Author
    Route::get('today_new_users', [NewAdminController::class, 'today_user'])->name('admin:get:recent_user');
    //Total User Count by Super Admin & Author
    Route::get('report/total_users', [NewAdminController::class, 'report_total_users'])->name('total:admin:get');
    //User Group by Super Admin & Author
    Route::get("user_group_by_date", [NewAdminController::class, 'group_by_date'])->name('group:admin:get');
    //User Search by date
    Route::get('user_search_by_date', [NewAdminController::class, 'user_search_by_date'])->name('user:admin:get');
    //Comment View By Super Admin & Author
    Route::get('/view_all_comments/{post_id}', [CreateCommentController::class, 'show_comments'])->name('admin:view:comments');
    //Comment Delete By Super Admin & Author
    Route::delete('/delete_comment/{comment_id}', [CreateCommentController::class, 'delete_comment'])->name('admin:delete:comment');
    //Comment Update By Super Admin & Author
    Route::post('update_comment/{comment_id}', [CreateCommentController::class, 'update_comment'])->name('admin:update:comment');
    //User List
    Route::get("/all_users", [NewAdminController::class, 'all_users'])->name('admin:get:users');
    //Last Week Total Users
    Route::get("/last_week/total_users", [NewAdminController::class, 'last_week_user'])->name('admin:get:last_week_user');
    //Last Week Total Posts
    Route::get('/last_week/total_posts', [NewAdminController::class, 'last_week_post'])->name('admin:get:last_week_post');
    //Percentage User The last week and the last week before
    Route::get('/percentage/last_week/users', [NewAdminController::class, 'percentage_user'])->name('admin:get:percentage_user');
    //Percentage Post The last week and the last week before
    Route::get('/percentage/last_week/total/posts', [NewAdminController::class, 'percentage_post'])->name('admin:get:percentage_post');
    //Post Like Lists
    Route::get('/post_like_lists/{post_id}', [CreatePostController::class, 'like_lists'])->name('admin:post:like:list');
    //Admin Post Read Lists
    Route::get('/post_read_lists/{post_id}', [CreatePostController::class, 'read_lists'])->name('admin:post:read:list');

    //Admin Update App Version
    Route::post("/app_version", [AppVersionController::class, 'update'])->name("admin:post:app_version");

    //Admin App Version
    Route::get("/app_version", [AppVersionController::class, 'get'])->name("admin:app_version:get");

    //Super Admin Only get Custom Ads
    Route::get(
        uri: '/ads',
        action: [AdminCreateAdController::class, 'get_ads']
    )->name('admin:get:ads');

    //Super Admin Only get single Custom Ads
    Route::get(
        uri: '/ads/{ads_id}',
        action: [AdminCreateAdController::class, 'single_ads']
    )->name('admin:get:single:ads');

    //Super Admin Only post Custom Ads
    Route::post(
        uri: '/ads',
        action: [AdminCreateAdController::class, 'create_ads']
    )->name('admin:create:ads');

    //Super Admin Only delete Custom Ads
    Route::post(
        uri: '/ads/delete/{ads_id}',
        action: [AdminCreateAdController::class, 'delete_ads']
    )->name('admin:delete:ads');

    //Super Admin Update Custom Ad
    Route::post(
        uri: '/ads/{ads_id}',
        action: [AdminCreateAdController::class, 'update_ads']
    )->name('admin:delete:ads');


    // Admin Access


    //Example Route
    Route::get('/pass-admin', [AdminController::class, 'index'])->name('admin:get:posts')->middleware('can:admin-create-posts');
    Route::post('/pass-admin', [AdminController::class, 'store'])->name('admin:post:posts')->middleware('can:author-create-posts');
});

//Admin Register
Route::post('auth/register', [AdminAuthController::class, 'register']);
//Super Admin and Admin Login
Route::post('/auth/login', [AdminAuthController::class, 'login']);
//View Image
Route::get('/images/{path}', [ImageController::class, 'show'])->name('images:show')->where('path', '.+');

Route::fallback(FallbackRouteController::class);
