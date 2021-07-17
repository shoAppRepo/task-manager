<?php

namespace App\Providers;

use App\Auth\userTokenProvider;
use App\DataProvider\Database\UserToken;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // config/auth.phpにサービスプロバイダで登録した名前を記述すると、アプリで使用できる
        $this->app->make('auth')->provider(
            'user_token',
            function(Application $app, array $config){
                return new USerTokenProvider(new UserToken($app->make('db')));
            }
        );
    }
}
