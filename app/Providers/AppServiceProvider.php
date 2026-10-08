<?php

namespace App\Providers;

use App\Http\Middleware\EnsureUserHasRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Alias middleware custom:  ->middleware('role:admin')
        Route::aliasMiddleware('role', EnsureUserHasRole::class);

        // Gates (dipakai di Blade: @can('access-admin'))
        Gate::define('access-admin', fn (User $user) => $user->isAdmin());
        Gate::define('manage-all-products', fn (User $user) => $user->canManageAllProducts());

        // Direktif Blade: @rupiah($angka) -> "Rp 1.250.000"
        Blade::directive('rupiah', fn ($expression) => "<?php echo 'Rp ' . number_format((float) ({$expression}), 0, ',', '.'); ?>");

        // Pagination bergaya "pill" (resources/views/pagination/brand.blade.php)
        Paginator::defaultView('pagination.brand');

        // Eager loading demo: deteksi N+1 saat development. Hanya MENCATAT ke
        // storage/logs/laravel.log (tidak melempar error), jadi aman dipakai belajar.
        Model::preventLazyLoading(! $this->app->isProduction());
        Model::handleLazyLoadingViolationUsing(function ($model, string $relation) {
            Log::warning(sprintf('[N+1] %s::%s() di-lazy-load. Pertimbangkan with(\'%s\').', $model::class, $relation, $relation));
        });
    }
}
