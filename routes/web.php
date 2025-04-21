<?php
use App\Http\Middleware\AuthCheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\BookmarkController;

Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/post/public', [PostController::class, 'publicPosts'])->name('publicPosts');

Route::get('/user/register', [UserController::class, 'register'])->name('user.register');
Route::post('/user/save', [UserController::class, 'save'])->name('user.save');
Route::post('/user/check', [UserController::class, 'check'])->name('user.check');
Route::get('/user/login', [UserController::class, 'login'])->name('user.login');

Route::middleware(['AuthCheck'])->group(function () {
    Route::post('/user/dashboard', [UserController::class, 'logout'])->name('user.logout');
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/user/dashall', [UserController::class, 'dashboardAll'])->name('user.dashall');
    Route::get('/user/dashboard/post/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/user/dashboard/post/store', [PostController::class, 'store'])->name('post.store');

    Route::get('/bookmarks', [BookmarkController::class, 'bookmarkedPosts'])->name('user.bookmarks');
    Route::post('/bookmarks/toggle/{post}', [BookmarkController::class, 'toggle'])->name('bookmark.toggle');

    Route::post('/post/like-toggle', [LikeController::class, 'toggle'])->name('post.like.toggle');

    Route::middleware(['web'])->group(function () {


        Route::post('/comments/{post}', [CommentController::class, 'store'])->name('comments.store');
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
        Route::get('/comments/{post}', [CommentController::class, 'getComments'])->name('comments.index');
        Route::get('/user/dashboard/post/edit/{id}', [PostController::class, 'edit'])->name('post.edit');
        Route::put('/user/dashboard/post/update/{id}', [PostController::class, 'update'])->name('post.update');

        Route::delete('/user/dashboard/post/delete/{id}', [PostController::class, 'destroy'])->name('post.delete');
    });
});