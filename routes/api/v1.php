<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FallbackRouteController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\QueryBuilderController;
use App\Http\Controllers\Api\RateController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AppVersionController;
use App\Http\Controllers\CateogryWithAdminController;
use App\Http\Controllers\CustomAdController;
use App\Http\Controllers\EmailLoginController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\OTPConfirmController;
use App\Http\Controllers\OTPResendController;
use App\Http\Controllers\WebSocketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('check.application.key')->group(function () {
    //Social Login
    Route::post('auth/{type}/login', [AuthController::class, 'login'])->name('login');
});

/**
 *Manual Email Login
 */

Route::middleware('check.application.key')->prefix('email')->as(':email')->group(function () {
    /**
     * Email Register
     */
    Route::post('/register', [EmailLoginController::class, 'register'])->name('register');
    /**
     * OTP Confirm
     */
    Route::post('/confirm', [OTPConfirmController::class, 'confirm'])->name('otps:confirm');

    /**
     * OTP Confirm Forget Password
     */
    Route::post('/confirm/forget', [OTPConfirmController::class, 'otp_password_confirm'])->name('opts:confirm:forget:password');

    /**
     * OTP Resend
     */
    Route::post('/resend', [OTPResendController::class, 'resend'])->name('otps:resend');

    /**
     * Email Login
     */
    Route::post('/login', [EmailLoginController::class, 'login'])->name('login');

    /**
     * Forget Password
     */
    Route::post(
        uri: '/password/forget',
        action: ForgetPasswordController::class
    )->name('password:forget');
})->name('emails:login');

Route::middleware(['auth:sanctum', 'check.application.key'])->group(function () {
    //Account Delete
    Route::delete('auth/user/delete', [AuthController::class, 'delete'])->name('user:delete');
    //Logout
    Route::delete('auth/logout', [AuthController::class, 'logout'])->name('logout');
    //getAllCategory
    Route::get('/all-category', [CategoryController::class, 'getAllCategory'])->name('category:get:all_show');
    //ShowUserCategory
    Route::get('/category', [CategoryController::class, 'showUserCategory'])->name('category:get:show');
    //StoreUserCategory
    Route::post('/category', [CategoryController::class, 'storeUserCategory'])->name('category:post:store');
    //User Profile
    Route::get('/user/profile/detail', [UserController::class, 'profile'])->name('user:get:profile');
    //Update User Name
    Route::patch('/user/{user}/name', [UserController::class, 'updateUserName'])->name("user:update:name");
    //Update User Image
    Route::post('/user/image', [UserController::class, 'updateUserImage'])->name('user:update:image');
    //Update Language
    Route::patch('/user/{user}/language', [UserController::class, 'updateUserLanguage'])->name('user:update:language');
    //Follower Add
    Route::post('/user/following/{post_id}', [UserController::class, 'userFollowerListStore'])->name('user:post:follow');
    //Follower Show
    Route::get('/user', [UserController::class, 'userFollowerListShow'])->name('user:get:show');
    //User ReadOrNot
    Route::post('/user/read/article/{post_id}', [UserController::class, 'readArticle'])->name('user:post:read');
    //User ReadList
    Route::get('/user/read/article', [UserController::class, 'readList'])->name('user:get:read');
    //User Article LikeList
    Route::get('/user/like/list', [UserController::class, 'likeList'])->name('user:get:like');
    //User likeArticle
    Route::post('/user/like/list/{post_id}', [UserController::class, 'likeArticle'])->name('user:post:like');
    //Comment Add
    Route::post('/user/comments/list', [CommentController::class, 'commentStore'])->name('user:post:comment');
    //User Comment Like Or Not
    Route::post('/user/comments/like/{comment_id}', [CommentController::class, 'commentLikeOrNot'])->name('user:post:comment:like');
    //Post Save
    Route::post('/user/posts/save/{post_id}', [PostController::class, 'postSaveOrNot'])->name("user:post:post");
    //Post Lists
    Route::get('/user/posts', [PostController::class, 'postList'])->name("user:get:post");
    //Post Detail
    Route::get('/user/posts/detail/{post_id}', [PostController::class, 'postDetail'])->name("user:get:detail");
    //Post Detail Comments
    Route::get('user/posts/detail/comments/{post_id}', [PostController::class, 'postDetailComment'])->name('user:get:detail:comment');
    //Post Share
    Route::post('/user/posts/share/{post_id}', [PostController::class, 'postShare'])->name("user:share:post");
    //Post Share List
    Route::get('/user/posts/share', [PostController::class, 'shareList'])->name("user:share:get");
    //Like Posts
    Route::get('/user/posts/like_posts', [PostController::class, 'like_posts'])->name("user:like:get");

    //User Follow Author
    Route::get('/user/posts/user_author_follow', [PostController::class, 'user_author_follow'])->name('user:author:get');

    //User App Version
    Route::get("/app_version", [AppVersionController::class, 'get'])->name("user:ap_version:get");

    //Notification Controller

    //Notification List
    Route::get('/user/notification/list', [NotificationController::class, 'notiList'])->name('user:notification:get');

    //Author Lists
    Route::get('/author_lists', [AuthorController::class, 'author_list'])->name('user:authorlist:get');

    //Get Admins with Category Id
    Route::get(
        uri: 'admins/bycategory/{category_id}',
        action: [AuthorController::class, 'author_category']
    )->name('admins:category');

    //Query Builder Controller
    //Regular Search
    Route::post('/user/posts/search', [QueryBuilderController::class, 'search_keywords'])->name('user:searchkeyword:post');

    //Filter Search
    Route::get('/user/posts/filter', [QueryBuilderController::class, 'filter_posts'])->name('filter:post:get');

    //Popular Categories
    Route::get('/user/posts/popular-categories', [QueryBuilderController::class, 'popular_categories'])->name('post:get:popular-categories');

    //Post Filter Category with admin
    Route::get(uri: '/filter/categories', action: CateogryWithAdminController::class)->name('post:get:filter:category:admin');

    //get Author
    Route::get('author/{author_id}', [UserController::class, 'get_author'])->name('get:author');

    //Rating Store
    Route::post('user/rate', [RateController::class, 'ratestore'])->name('post:user:rate');

    //Update Token
    Route::patch("user/token/update", [UserController::class, 'update_token'])->name('user:update:token');

    //Generate Web Socket Url
    Route::get("/generate-websocket-url", [WebSocketController::class, 'generate_url'])->name("user:generate:url");

    //Random Customer Ad
    Route::get(
        uri: '/custom_ads',
        action: CustomAdController::class
    )->name('user:get:custom_ad');
});


Route::get('/images/{path}', [ImageController::class, 'show'])->name('image:show')->where('path', '.+');

Route::fallback(FallbackRouteController::class);
