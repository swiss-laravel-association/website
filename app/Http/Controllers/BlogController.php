<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->get();

        return view('blog.index', [
            'posts' => $posts,
            'breadcrumbs' => [
                ['label' => 'Home', 'url' => route('home'), 'icon' => 'home'],
                ['label' => 'Blog'],
            ],
        ]);
    }

    public function show(Post $post): View
    {
        return view('blog.show', [
            'post' => $post,
            'breadcrumbs' => [
                ['label' => 'Home', 'url' => route('home'), 'icon' => 'home'],
                ['label' => 'Blog', 'url' => route('blog.index')],
                ['label' => $post->title],
            ],
        ]);
    }
}
