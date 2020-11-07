<?php

namespace App\Providers;

use Dingo\Api\Auth\Auth;
use Dingo\Api\Auth\Provider\JWT;
use Dingo\Api\Http\RateLimit\Handler;
use Dingo\Api\Http\RateLimit\Throttle\Authenticated;
use Dingo\Api\Transformer\Adapter\Fractal;
use Dingo\Api\Transformer\Factory;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;
use League\Fractal\Serializer\JsonApiSerializer;
use Tymon\JWTAuth\JWTAuth;
use Dingo\Api\Exception\Handler as EHandler;

class DingoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        # Authentication Providers
        $this->app[Auth::class]->extend('oauth', function ($app) {
            return new JWT($app[JWTAuth::class]);
        });

        # Throttling / Rate Limiting
        $this->app[Handler::class]->extend(function ($app) {
            return new Authenticated;
        });

        # Response Transformer
        $this->app[Factory::class]->setAdapter(function ($app) {
            $fractal = new Manager;

            $fractal->setSerializer(new JsonApiSerializer);

            return new Fractal($fractal);
        });

        # Error Format
        $this->app[EHandler::class]->setErrorFormat([
            'error' => [
                'message' => ':message',
                'errors' => ':errors',
                'code' => ':code',
                'status_code' => ':status_code',
                'debug' => ':debug'
            ]
        ]);
    }
}
