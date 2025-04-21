<?php 

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookmarkController extends Controller
{
    public function toggle(Request $request, Post $post)
    {
        $userId = session('LoggedUser');

        $bookmarked = $post->bookmarks()->where('user_id', $userId)->exists();

        if ($bookmarked) {
            $post->bookmarks()->where('user_id', $userId)->delete();
        } else {
            $post->bookmarks()->create(['user_id' => $userId]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'bookmarked' => !$bookmarked,
            ]);
        }

        return back(); // or redirect()->back()
    }

    public function bookmarkedPosts()
    {
        $user = User::find(session('LoggedUser'));

        $bookmarkedPosts = $user->bookmarks()
            ->with('post.user', 'post.bookmarks') // include all you need
            ->latest()
            ->paginate(6);

        return Inertia::render('Bookmarks', [
            'LoggedUser' => $user,
            'bookmarkedPosts' => $bookmarkedPosts
        ]);
    }
}
