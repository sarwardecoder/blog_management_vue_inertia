<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;

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
$loggedUser= User::find(session('LoggedUser'));
$posts = Post::with(['user', 'comments.user', 'tags', 'likes', 'bookmarks'])->latest()->paginate(10);
return Inertia::render('UserDashboard', [
    'posts' => $posts,
    'loggedUser' => $loggedUser,
]);
    }

    public function editComment(Request $request, $commentId)
{
    // Validate input (e.g., body must be a non-empty string)
    $request->validate([
        'body' => 'required|string',
    ]);

    // Find the comment by ID
    $comment = Comment::find($commentId);

    if (!$comment) {
        return response()->json(['error' => 'Comment not found'], 404);
    }

    // Check if the logged user is the owner of the comment
    if ($comment->user_id !== session('LoggedUser')) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Update the comment body
    $comment->body = $request->input('body');
    $comment->save();

    // Return a success response with the updated comment
    return response()->json([
        'success' => 'Comment Updated',
        'comment' => $comment->load('user', 'replies') // You can include relationships like user and replies if needed
    ]);
}

    // Delete a comment
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== session('LoggedUser')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['success' => 'Comment deleted']);
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
