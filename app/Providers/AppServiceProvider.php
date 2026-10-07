<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        View::composer('components.layout', function ($view): void {
            $user = Auth::user();
            $library = $user?->ownLibrary;

            $view->with([
                'pickupNotificationCount' => $user
                    ? Loan::where('user_id', $user->id)
                        ->where('active', 1)
                        ->where(function ($query) {
                            $query->whereNull('description')->orWhere('description', 'rezervisano');
                        })
                        ->count()
                    : 0,
                'libraryConfirmationNotificationCount' => $library
                    ? Loan::where('library_id', $library->id)
                        ->where('active', 1)
                        ->where(function ($query) {
                            $query->whereNull('description')->orWhere('description', 'rezervisano');
                        })
                        ->count()
                    : 0,
            ]);
        });
    }
}
