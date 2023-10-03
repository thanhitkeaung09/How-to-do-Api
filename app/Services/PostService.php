<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Events\ShareEvent;
use App\Http\Resources\SavePostResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

class PostService
{
    public function postSaveOrNot($request, $type)
    {
        $check = $this->checkExits($type, "post_saves", "post_id");
        if ($check) {
            $post = Post::find($type)->usersSave()->detach(\auth()->id());
            return [
                "post_id" => $type,
                "is_save" => false
            ];
        } else {
            $post = Post::find($type)->usersSave()->attach(\auth()->id());
            return [
                "post_id" => $type,
                "is_save" => true
            ];
        }
    }

    public function checkExits($value, $table, $table_id)
    {
        return DB::table($table)
            ->where('user_id', \auth()->id())
            ->where($table_id, $value)
            ->exists();
    }

    public function postList($request)
    {
        $type = $request->type;
        $author_id = $request->author_id;
        if ($type === "trending") {
            return $this->trending_posts();
        } elseif ($type === "following") {
            return $this->following_posts();
        } elseif ($type === "read_article") {
            return $this->read_articles();
        } elseif ($type === "save") {
            return $this->save_posts();
        } elseif ($request->category_id) {
            return $this->categories($request->category_id);
        } elseif ($request->author_id) {
            return $this->author_posts($request->author_id);
        } else {
            return "";
        }
    }

    public function trending_posts()
    {
        $selectedCategoryIds = auth()->user()->categories()->pluck('categories.id')->toArray();

        $posts = Post::with(['admin', 'category', 'usersSave', 'likes'])->withCount(['postRead', 'comments', 'likes', 'usersShare'])->whereHas('category', function ($query) use ($selectedCategoryIds) {
            $query->whereIn('categories.id', $selectedCategoryIds);
        })->latest()->paginate(10);
        foreach ($posts as $post) {
            $post->is_save = $post->usersSave->where("id", \auth()->id())->count() > 0;
            $post->is_like = $post->likes->where("user_id", \auth()->id())->count() > 0;
            $post->type = "trending";
            unset($post->usersSave, $post->likes, $post->body_text_image);
        }
        return $posts;
    }

    public function following_posts()
    {
        $admins = Auth::user()->admins->map(fn ($admin) => $admin->id)->toArray();
        $posts = Post::with(['admin', 'category', 'usersSave', 'likes'])->withCount(['postRead', 'comments', 'likes', 'usersShare'])
            ->whereIn("admin_id", $admins)->latest()
            ->paginate(10);
        foreach ($posts as $post) {
            $post->is_save = $post->usersSave->where("id", \auth()->id())->count() > 0;
            $post->is_like = $post->likes->where("user_id", \auth()->id())->count() > 0;
            $post->short_words = Str::limit($post->body_text_image, 100);
            $post->type = "following";
            unset($post->usersSave, $post->likes, $post->body_text_image);
        }
        return $posts;
    }

    public function read_articles()
    {
        $posts = Auth::user()->postReads()->with(['admin', 'category', 'usersSave', 'likes'])->withCount(['comments', 'likes', 'usersShare'])->paginate(10)->through(function ($c) {
            $c->is_save = $c->usersSave->where("id", \auth()->id())->count() > 0;
            $c->is_like = $c->likes->where("user_id", \auth()->id())->count() > 0;
            $c->short_words = Str::limit($c->body_text_image, 100);
            $c->type = "read_article";
            unset($c->usersSave, $c->likes, $c->body_text_image);
            return $c;
        });
        return $posts;
    }

    public function like_articles()
    {
        $likeable_posts = DB::table('likes')->where('user_id', \auth()->id())->where('likeable_type', Post::class)->latest()->get();
        $collection = collect();
        foreach ($likeable_posts as $post) {
            $posts = Post::query()
                ->where('id', $post->likeable_id)->paginate(10);
            $collection->push($posts->getCollection());
        }
        return $collection->flatten(1)->transform(function ($c) {
            unset($c->usersSave, $c->admin_id, $c->user_id, $c->category_id, $c->body_text_image, $c->short_words);
            return $c;
        });
    }

    public function save_posts()
    {
        // dd("helo");
        $post_ids = Auth::user()->posts->map(fn ($post) => $post->id)->toArray();
        $collection = collect();

        $posts = Post::with(['admin', 'category', 'usersSave'])->withCount(['postRead', 'comments', 'likes', 'usersShare'])
            ->whereIn('id', $post_ids)->latest('id')->paginate(10);

        foreach ($posts as $post) {
            $post->is_save = $post->usersSave->where("id", \auth()->id())->count() > 0;
            $post->is_like = $post->likes->where("user_id", \auth()->id())->count() > 0;
            $post->short_words = Str::limit($post->body_text_image, 100);
            $post->type = "save";
            unset($post->usersSave, $post->likes, $post->body_text_image);
        }
        return $posts;
    }

    public function categories($category_id)
    {
        Category::find($category_id)->usersCategory()->attach(\auth()->id());
        return $this->removeKeys("category_id", $category_id);
    }

    public function author_posts($author_id)
    {
        return $this->removeKeys("admin_id", $author_id);
    }

    public function removeKeys($column_id, $id)
    {
        $posts = Post::with(['admin', 'category', 'usersSave'])->withCount(['postRead', 'comments', 'likes', 'usersShare'])->where($column_id, $id)->latest()->paginate(10);
        foreach ($posts as $post) {
            $post->short_words = $post->short_words;
            $post->is_save = $post->usersSave->where("id", \auth()->id())->count() > 0;
            $post->is_like = $post->likes->where("user_id", \auth()->id())->count() > 0;
            unset($post->usersSave, $post->likes, $post->body_text_image);
        }
        return $posts;
    }


    public function shareArticle(Request $request, $type)
    {
        $check = $this->checkExits($type, "post_shares", "post_id");
        $this->shareOrNot($type, Post::class);
        $share_count = DB::table("post_shares")->where("post_id", $type)->count();


        event(new ShareEvent(["id" => intval($type), "share_count" => $share_count, "type" => "post_share"]));


        return true;
    }

    public function shareOrNot($value, $model)
    {
        $model::find($value)->usersShare()->attach(\auth()->id());
    }

    public function shareList()
    {
        $posts = Post::withCount("usersShare")->get();
        return $posts;
    }

    public function postDetail($type)
    {
        $post = Post::with(['admin', 'category', 'usersSave', 'likes'])->withCount(['postRead', 'likes', 'usersShare'])->find($type);
        if ($post->usersSave->where("id", \auth()->id())->count()) {
            $post->is_save = true;
        } else {
            $post->is_save = false;
        }
        unset($post->usersSave, $post->commenst);
        $post->is_like = false;

        foreach ($post->likes as $like) {
            if ($like->user_id === \auth()->id()) {
                $post->is_like = true;
            }
        }

        unset($post->likes);
        return $post;
    }

    public function postDetailComment($type)
    {
        $comments_count = Comment::with(['likes', 'users'])->withCount('likes')->where('post_id', $type)->get();

        $comments = Comment::with(['likes', 'users'])->withCount('likes')->where('post_id', $type)->paginate(10);
        foreach ($comments as $comment) {
            $comment->count = $comment->likes_count;
            $comment->is_like = $comment->likes->where("user_id", \auth()->id())->count() > 0;
            unset($comment->likes_count);
            unset($comment->likes);
        }
        return [
            "comment_total" => $comments_count->count(),
            "post_id" => intval($type),
            "comments" => $comments->getCollection()
        ];
    }

    public function user_author_follow()
    {
        $admins = Auth::user()->admins->map(fn ($admin) => $admin->id)->toArray();
        $posts = Post::query()
            ->whereIn("admin_id", $admins)
            ->paginate(10);
        foreach ($posts as $post) {
            $post->short_words = Str::limit($post->body_text_image, 100);
            unset($post->usersSave, $post->admin, $post->user_id, $post->category_id, $post->body_text_image, $post->short_words);
        }
        return $posts->getCollection();
    }
}
