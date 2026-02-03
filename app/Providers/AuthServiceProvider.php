<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\Distributor;
use App\Policies\UserPolicy;
use App\Policies\VendorPolicy;
use App\Policies\OrderPolicy;
use App\Policies\DeliveryPolicy;
use App\Policies\DistributorPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Vendor::class => VendorPolicy::class,
        Order::class => OrderPolicy::class,
        Delivery::class => DeliveryPolicy::class,
        Distributor::class => DistributorPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define admin role gate
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        // Define vendor role gate
        Gate::define('vendor', function (User $user) {
            return $user->role === 'vendor';
        });

        // Define customer role gate
        Gate::define('customer', function (User $user) {
            return $user->role === 'customer';
        });

        // Define distributor role gate
        Gate::define('distributor', function (User $user) {
            return $user->role === 'distributor';
        });

        // Admin can do anything
        Gate::before(function (User $user, $ability) {
            if ($user->role === 'admin') {
                return true;
            }
        });
        
        // Additional role-based permissions
        Gate::define('manage-users', function (User $user) {
            return in_array($user->role, ['admin']);
        });
        
        Gate::define('manage-vendors', function (User $user) {
            return in_array($user->role, ['admin']);
        });
        
        Gate::define('manage-orders', function (User $user) {
            return in_array($user->role, ['admin', 'vendor', 'distributor']);
        });
        
        Gate::define('view-reports', function (User $user) {
            return in_array($user->role, ['admin', 'vendor']);
        });

        // Define user role gate
        Gate::define('user', function (User $user) {
            return $user->role === 'user';
        });
    }
}
