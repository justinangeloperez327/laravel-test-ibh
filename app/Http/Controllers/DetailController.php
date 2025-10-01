<?php

namespace App\Http\Controllers;

use App\Enums\BlogStatus;
use App\Models\Blog;
use Illuminate\Http\Request;

class DetailController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Blog $blog)
    {
        if ($blog->status !== BlogStatus::PUBLISHED) {
            return redirect()->route('home');
        }

        return view('detail-page', ['blog' => $blog]);
    }
}
