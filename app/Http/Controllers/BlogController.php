<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->get();

        seo(new SEOData(
            title: 'Blog',
            description: 'News and updates from the Swiss Laravel Association.',
        ));

        return view('blog.index', [
            'posts' => $posts,
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Blog'),
        ]);
    }

    public function show(Post $post): View
    {
        seo($post);

        return view('blog.show', [
            'post' => $post,
            'breadcrumbs' => Breadcrumbs::make()
                ->add('Blog', route('blog.index'))
                ->add($post->title),
        ]);
    }
}
