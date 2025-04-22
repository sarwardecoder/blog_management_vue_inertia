<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function login()
    {
        return Inertia::render('User/Login');
    }

    public function register()
    {
        return Inertia::render('User/Register');
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:users|max:255',
            'email' => 'required|string|unique:users|email',
            'password' => 'required|max:12|min:3|confirmed',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;

        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $imageName = time() . '.' . $img->getClientOriginalExtension();
            $imgPath = 'uploads/' . $imageName;
            $img->move(public_path('uploads'), $imageName);
            $user->img = $imgPath;
        }

        $user->save();

        return redirect()->route('user.login')->with('success', 'User Created Successfully, Please Login');
    }

    public function check(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|max:12|min:3',
        ]);

        $userInfo = User::where('email', $request->email)->first();
        // dd($userInfo);
        if (!$userInfo) {
            return back()->with('fail', "We don't recognize your email address");
        }

        if ($request->password == $userInfo->password) {
            $request->session()->put('LoggedUser', $userInfo->id);
            return redirect()->route('user.dashall');
        } else {
            return back()->with('fail', 'Password is incorrect');
        }
    }


    public function dashboard(Request $request)
    {
        $query = Post::with([
            'user:id,name',
            'likes',
            'bookmarks',
            'tags',
            'comments.replies.user',
            'comments.user'
        ])->where('visibility', 'public');
        if ($request->has('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('tags', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $LoggedUser = User::find(session('LoggedUser'));
        $posts = $query->orderBy('id', 'desc')->where('user_id', $LoggedUser->id)->simplePaginate(3);

        if ($LoggedUser) {
            return Inertia::render('User/UserDashboard', [

                'posts' => $posts,
                'LoggedUser' => $LoggedUser,
            ]);

        } else {
            return redirect('/user/login')->with('fail', 'Please Login First');
        }
    }

    public function dashboardAll(Request $request)
    {
        $query = Post::with([
            'user:id,name',
            'likes',
            'bookmarks',
            'tags',
            'comments.replies.user',
            'comments.user'
        ])->where('visibility', 'public');
        if ($request->has('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('tags', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $posts = $query->orderBy('id', 'desc')->simplePaginate(3);
        $LoggedUser = User::find(session('LoggedUser'));


        return Inertia::render('Dashall', [
            'posts' => $posts,
            'LoggedUser' => $LoggedUser,
        ]);
    }

    public function logout()
    {
        if (Session()->has('LoggedUser')) {
            Session()->pull('LoggedUser');
        }

        return redirect('/user/login');
    }
}
