<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Enums\UserRole;
use App\Enums\BlogStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::user()->role === UserRole::ADMIN) {
            $blogs = Blog::latest()->get();
        } else {
            $blogs = Auth::user()->blogs()->latest()->get();
        }

        return view('blogs.index', [
            'blogs' => $blogs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|max:1024',
        ]);

        $imagePath = $request->file('image')->store('blog_images', 'public');

        Blog::create($request->only('title', 'content') + [
            'user_id' => auth()->id(),
            'image_path' => $imagePath
        ]);

        return redirect()->route('blogs.index')->with('success', 'Blog post created and is pending approval.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        return view('blogs.show', [
            'blog' => $blog
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        if ($blog->status === BlogStatus::PENDING || $blog->status === BlogStatus::REJECTED) {
            return view('blogs.edit', [
                'blog' => $blog
            ]);
        }

        if ($blog->status === BlogStatus::PUBLISHED) {
            abort(403, 'Cannot edit a published blog post.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        if ($blog->status === BlogStatus::PENDING || $blog->status === BlogStatus::REJECTED) {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'nullable|image|max:1024',
            ]);

            $data = [
                'title'   => $validated['title'],
                'content' => $validated['content'],
            ];

            if ($request->hasFile('image')) {
                // Delete old image if present
                if (! empty($blog->image_path) && Storage::disk('public')->exists($blog->image_path)) {
                    Storage::disk('public')->delete($blog->image_path);
                }

                $data['image_path'] = $request->file('image')->store('blog_images', 'public');
            }

            $blog->update($data);

            return redirect()->route('blogs.index')->with('success', 'Blog Post Updated');
        }

        if ($blog->status === BlogStatus::PUBLISHED) {
            abort(403, 'Cannot edit a published blog post.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        if ($blog->status === BlogStatus::PENDING || $blog->status === BlogStatus::REJECTED) {

        }

        if ($blog->status === BlogStatus::PUBLISHED) {
            return redirect()->route('blogs.index')->with('error', 'Cannot delete a published blog post.');
        }
    }

    public function updateStatus(Blog $blog, Request $request)
    {
        $blog->update([
            'status' => $request->status
        ]);

        return [
            'message' => 'success'
        ];
    }
}
