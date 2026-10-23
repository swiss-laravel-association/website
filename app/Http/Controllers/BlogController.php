<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Laravel\Head\Enums\OgType;
use Laravel\Head\Facades\Head;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->get();

        Head::title('Blog')
            ->description('News and updates from the Swiss Laravel Association.');

        return view('blog.index', [
            'posts' => $posts,
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Blog'),
        ]);
    }

    public function show(Post $post): View
    {
        Head::title($post->title)
            ->description($post->excerpt)
            ->og(type: OgType::Article);

        if ($post->published_at !== null) {
            Head::meta('article:published_time', $post->published_at->toIso8601String());
        }

        return view('blog.show', [
            'post' => $post,
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Blog', route('blog.index'))
                ->add($post->title),
        ]);
    }
}
