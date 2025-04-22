<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;
use function Psy\debug;
use Illuminate\Http\Request;
use App\Http\Middleware\AuthCheck;
use Illuminate\Support\Facades\Auth;


class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::where('visibility', 'public')
            ->with('user:id,name')
            ->orderByDesc('id')
            ->paginate(3);
        // ->get();  
        // Manually fetch the user from session ID
        $userId = $request->session()->get('LoggedUser');


        $user = User::select('id', 'name')->find($userId);

        return Inertia::render('Dashboard', [
            'posts' => $posts,
            'LoggedUser' => $user
        ]);
        // $data="hello from controller";
// return Inertia::render('Dashboard');

    }

    public function publicPosts(Request $request)
    {
        $posts = Post::where('visibility', 'public')
            ->with('user:id,name')
            ->orderByDesc('id')
            ->paginate(2);
        // ->get();  
// Manually fetch the user from session ID
        $userId = $request->session()->get('LoggedUser');

        $user = User::select('id', 'name')->find($userId);
        return Inertia::render('Post/PublicPost', [
            'posts' => $posts,
            'LoggedUser' => $user
        ]);

        // return Inertia::render('PublicPosts');
    }

    public function create()
    {
        return Inertia::render('Post/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'visibility' => 'required|in:public,private',
            'tags' => 'nullable|string'
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->visibility = $request->visibility;

        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $imageName = time() . '.' . $img->getClientOriginalExtension();
            $img->move(public_path('uploads'), $imageName);
            $post->img = 'uploads/' . $imageName;
        }

        $post->user_id = session('LoggedUser');
        $post->save();

        if ($request->filled('tags')) {
            $tagIds = collect(explode(',', $request->tags))
                ->map(fn($name) => Tag::firstOrCreate(['name' => trim($name)])->id)
                ->toArray();
            $post->tags()->sync($tagIds);
        }

        return redirect()->route('user.dashboard')->with('success', 'Post Created Successfully');
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post || session('LoggedUser') !== $post->user_id) {
            return redirect()->route('user.dashboard')->with('error', 'Unauthorized or not found.');
        }

        $post->delete();

        return redirect()->route('user.dashboard')->with('success', 'Post deleted successfully.');
    }

    public function edit($id)
    {
        $post = Post::find($id);
        if (!$post || !session('LoggedUser')) {
            return redirect()->route('user.dashboard')->with('error', 'Unauthorized or not found.');
        } else {
            return Inertia::render('Post/Edit', [
                'post' => $post,

            ]);
        }

    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post || !session('LoggedUser')) {
            return redirect()->route('user.dashboard')->with('error', 'Unauthorized or not found.');
        } else {

            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'visibility' => 'required|in:public,private',
                'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ])
            ;

            $post->title = $request->title;
            $post->content = $request->content;
            $post->visibility = $request->visibility;

            if ($request->hasFile('img')) {
                if ($post->img && file_exists(public_path($post->img))) {
                    unlink(public_path($post->img));
                }

                $img = $request->file('img');
                $imageName = time() . '.' . $img->getClientOriginalExtension();
                $img->move(public_path('uploads'), $imageName);
                $post->img = 'uploads/' . $imageName;
            }

            $post->save();

            return redirect()->route('user.dashboard')->with('success', 'Post Updated Successfully');
        }
    }
}
