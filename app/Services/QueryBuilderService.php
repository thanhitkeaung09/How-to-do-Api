<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use function Pest\Laravel\get;

class QueryBuilderService
{
    public function search_keywords($request)
    {
        $keyword = $request->keyword;
        $words = explode(" ", $keyword);
        $results = Post::query()->with(['admin', 'category', 'usersSave'])->withCount(['postRead', 'comments', 'likes', 'usersShare'])
            ->where(function ($query) use ($words) {
                foreach ($words as $word) {
                    $query->orWhere('article_title', 'REGEXP', "\\b$word\\b");
                }
            })
            ->paginate(10)->getCollection()->transform(
                function ($c) {
                    if ($c->usersSave->count() > 0) {
                        $c->is_save = true;
                    } else {
                        $c->is_save = false;
                    }
                    unset($c->usersSave);
                    return $c;
                }
            );
        return $results;
    }

    public function filter_posts($request)
    {
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date)->addDays(1);

        $category_id = $request->category_id; // Replace with the desired category ID or set to null
        $admin_id = $request->author_id; // Replace with the desired admin ID or set to null

        $posts = Post::query()
            ->with(['admin', 'category', 'usersSave'])
            ->withCount(['postRead', 'comments', 'likes', 'usersShare'])
            ->whereHas('usersSave', function ($query) use ($start_date, $end_date) {
                $query->whereBetween('post_saves.created_at', [$start_date, $end_date]);
            })
            ->when($request->category_id, function ($query) use ($request) {
                $query->where("category_id", $request->category_id);
            })
            ->when($request->author_id, function ($query) use ($request) {
                $query->where("admin_id", $request->author_id);
            })->get();

        foreach ($posts as $post) {
            if ($post->usersSave->count() > 0) {
                $post->is_save = true;
            }
            $post->is_like = $post->likes->where("user_id", \auth()->id())->count() > 0;
            $post->type = "save";
            unset($post->usersSave, $post->likes, $post->body_text_image);
        }


        if (count($posts)) {
            return $posts;
        } else {
            return null;
        }

        // whereHas('usersSave', function ($query) use ($start_date, $end_date) {
        //     $query->whereBetween('post_saves.created_at', [$start_date, $end_date]);
        // })->

        // return $posts;
        // $posts = Post::query()
        //     ->with(['admin', 'category', 'usersSave'])
        //     ->withCount(['postRead', 'comments', 'likes', 'usersShare'])
        //     ->has('usersSave')->get();
        // return $posts;

        // whereHas(
        //     'usersSave',
        //     fn ($q) =>
        //     $q->when($request->start_date, function ($query) use ($request) {
        //         // $query->wherePivotBetween('created_at', [$request->start_date, $request->end_date]);
        //         $query->where('admin_id', 1);
        //     })
        // )

        // ->when($request->start_date, function ($query) use ($request) {
        //     $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        // })->when($request->category_id, function ($query) use ($request) {
        //     $query->where("category_id", $request->category_id);
        // })->when($request->admin_id, function ($query) use ($request) {
        //     $query->where("admin_id", $request->admin_id);
        // })
        // return Post::with(['admin', 'category', 'usersSave'])->withCount(['postRead', 'comments', 'likes', 'usersShare'])->whereBetween('created_at', [$request->start_date, $request->end_date])->where('category_id', $request->category_id)->where("admin_id", $request->author_id)->paginate(10)->getCollection()->transform(function ($c) {
        //     if ($c->usersSave->count() > 0) {
        //         $c->is_save = true;
        //     }
        //     unset($c->usersSave);
        //     unset($c->body_text_image);
        //     return $c;
        // });
    }

    public function popular_categories()
    {
        // return "popular categories";
        $counts = DB::table('category_users')->select('category_id', DB::raw('COUNT(*) as total'))->groupBy('category_id')->orderBy('total', 'DESC')->get()->take(10);
        $categories = [];
        foreach ($counts as $count) {
            $category = Category::query()->find($count->category_id);
            $category->is_selected = $category->users->where('id', auth()->id())->count() > 0;
            $category->count = $category->posts->count();
            unset($category->posts);
            unset($category->users);
            $categories[] = $category;
        }
        return $categories;
    }
}
