<?php

namespace App\Providers;

use App\Account;
use App\Document;
use App\Movement;
use App\Policies\AccountPolicy;
use App\Policies\MovementPolicy;
use App\Policies\PostPolicy;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Account::class => AccountPolicy::class,
        User::class => AccountPolicy::class,
        User::class => MovementPolicy::class,
        Document::class => MovementPolicy::class,
        Movement::class => MovementPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //
    }
}
