<?php

namespace App\Http\Controllers;

use App\Enums\BlogStatus;
use App\Models\Blog;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $blogs = Blog::query()->where('status', BlogStatus::PUBLISHED)->latest()->get();

        return view('home', [
            'blogs' => $blogs
        ]);
    }
}
