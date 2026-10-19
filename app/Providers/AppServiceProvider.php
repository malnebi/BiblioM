<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\ProfileChangeRequest;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
            $adminNotificationCounts = $user?->role === 'admin'
                ? [
                    'Нови захтјеви за регистрацију' => User::where('approved', 0)
                        ->where(fn ($query) => $query->whereNull('role')->orWhere('role', '!=', 'admin'))
                        ->count(),
                    'Захтјеви за измјену профила' => ProfileChangeRequest::where('status', 'pending')->count(),
                    'Предлози нових ознака за књиге' => Tag::where('approved', false)->count(),
                ]
                : [];
            $adminNotificationCount = array_sum($adminNotificationCounts);
            $adminNotificationMessage = collect($adminNotificationCounts)
                ->filter(fn (int $count) => $count > 0)
                ->map(fn (int $count, string $label) => "{$label}: {$count}")
                ->implode('. ');

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
                'newBooksNotificationCount' => $user
                    ? Book::where(
                        'created_at',
                        '>',
                        $user->books_notification_viewed_at ?? $user->created_at
                    )->count()
                    : 0,
                'adminNotificationCount' => $adminNotificationCount,
                'adminNotificationMessage' => $adminNotificationMessage,
            ]);
        });
    }
}
