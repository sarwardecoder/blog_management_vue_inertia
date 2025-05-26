<?php
namespace App\Http\Controllers;
use App\Models\Like;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LikeController extends Controller{
    public function postLike($postId){
        $userId = session('LoggedUser');
        $like = Like::where('user_id', $userId)->where('post_id', $postId)->exists();
        dd($like);
        if ($like) {
            $like->delete(); // Unlike
        } else {
            Like::create(['user_id' => $userId, 'post_id' => $postId]); // Like
        }
        // $likeCount = Like::where('post_id', $postId)->count();
        $likeCount=true;
        return Inertia::render('/User/UserDashboard', [
            'like' => $like,
            'likeCount' => $likeCount
            
        ]);        
    }
    public static function likeApi($postId){

    }

}

