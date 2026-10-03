<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::where('is_published', true)->latest('published_at')->paginate(6);
        $categories = BlogPost::distinct()->pluck('category');

        return view('blog.index', compact('posts', 'categories'));
    }

    public function show(string $slug)
    {
        $post = BlogPost::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $recentPosts = BlogPost::where('id', '!=', $post->id)->where('is_published', true)->latest('published_at')->take(3)->get();

        return view('blog.show', compact('post', 'recentPosts'));
    }
}
