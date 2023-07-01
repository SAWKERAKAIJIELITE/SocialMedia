<?php

namespace App\Providers;

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FavoritePostController;
use App\Http\Controllers\Api\FriendController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Controller;
// use App\Http\Controllers\Api\UserController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::name('auth.')
                ->middleware('api')
                ->prefix('api/auth')
                ->group(base_path('routes/Api/auth.php'));

            Route::name('users.')
                ->middleware(['api', 'auth:sanctum'])
                ->controller(UserController::class)
                ->prefix('api/users')
                ->group(base_path('routes/Api/users.php'));

            Route::name('posts.')
                ->middleware(['api', 'auth:sanctum'])
                ->controller(PostController::class)
                ->prefix('api/posts')
                ->group(base_path('routes/Api/posts.php'));

            Route::name('posts.saves')
                ->middleware(['api', 'auth:sanctum'])
                ->controller(FavoritePostController::class)
                ->prefix('api/posts/saves')
                ->group(base_path('routes/Api/saves.posts.php'));

            Route::name('comments.')
                ->middleware(['api', 'auth:sanctum'])
                ->controller(CommentController::class)
                ->prefix('api/comments')
                ->group(base_path('routes/Api/comments.php'));

            Route::name('friends.')
                ->middleware(['api', 'auth:sanctum'])
                ->controller(FriendController::class)
                ->prefix('api/friends')
                ->group(base_path('routes/Api/friends.php'));
            });

            Route::name('pages.')
                ->middleware(['api', 'auth:sanctum'])
                ->controller(PageController::class)
                ->prefix('api/pages')
                ->group(base_path('routes/Api/pages.php'));
    }
}
