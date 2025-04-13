<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->model = new Blog;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        \Log::info("index blog");
        
        $user = auth()->user();

        $query = $this->model->with('creator')->where(function ($q) use ($user) {
            $q->where('status', 'published')
            ->orWhere('created_by', $user->id);
        });

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        return response()->json([
            'message' => 'Blog list retrieved successfully.',
            'blogs' => $query->latest()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'status'  => 'in:published,hidden',
        ]);

        DB::beginTransaction();

        try {
            $blog = $this->model->create([
                'title'      => $request->title,
                'content'    => $request->content,
                'status'     => $request->status ?? 'hidden',
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Blog created successfully.',
                'data' => $blog,
            ], 201);

        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create blog.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): JsonResponse
    {
        $blog = $this->model->with("creator")->findOrFail($request->blog);

        return response()->json([
            'message' => 'Blog details retrieved.',
            'blog' => $blog,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog): JsonResponse
    {
        $request->validate([
            'title'   => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'status'  => 'in:published,hidden',
        ]);

        DB::beginTransaction();

        try {
            $res = $blog->update($request->only('title', 'content', 'status'));

            DB::commit();

            return response()->json([
                'message' => 'Blog updated successfully.',
                'data' => $blog,
            ]);

        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update blog.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog): JsonResponse
    {
        DB::beginTransaction();

        try {
            $blog->delete();

            DB::commit();

            return response()->json([
                'message' => 'Blog archived successfully.',
            ]);

        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to archive blog.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Changing status
     */
    public function changeStatus(Blog $blog): JsonResponse
    {
        DB::beginTransaction();

        try {
            $blog->status = $blog->status === 'published' ? 'hidden' : 'published';
            $blog->save();

            DB::commit();

            return response()->json([
                'message' => 'Blog status updated.',
                'data' => ['status' => $blog->status],
            ]);

        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to change status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}