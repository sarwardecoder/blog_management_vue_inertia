<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Store a new comment or reply
    public function store(Request $request, $postId)
    {
        $request->validate([
            'body' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id'
        ]);
        $comment = Comment::create([
            'body' => $request->body,
            'user_id' => session('LoggedUser'),
            'post_id' => $postId,
            'parent_id' => $request->parent_id
        ]);
        return response()->json([
            'success' => true,
            'comment' => $comment->load('user', 'replies')
        ]);
    }

    // Delete a comment
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== session('LoggedUser')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }

    // Optional: get all comments with nested replies for a post
    public function getComments($postId)
    {
        $comments = Comment::with(['user', 'replies.user'])
            ->where('post_id', $postId)
            ->whereNull('parent_id')
            ->latest()
            ->get();

        return response()->json($comments);
    }
}
