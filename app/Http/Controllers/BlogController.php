<?php

namespace App\Http\Controllers;

use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::query()
            ->latest('published_at')
            ->get();

        return view('blog.index', [
            'posts' => $posts,
        ]);
    }

    public function show(Post $post)
    {
        return view('blog.show', [
            'post' => $post,
        ]);
    }
}
