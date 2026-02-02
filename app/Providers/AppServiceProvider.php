<?php

namespace App\Providers;

use App\Repositories\BreedRepository;
use App\Repositories\BreedRepositoryInterface;
use App\Repositories\PetRepository;
use App\Repositories\PetRepositoryInterface;
use App\Repositories\TypeRepository;
use App\Repositories\TypeRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TypeRepositoryInterface::class, TypeRepository::class);
        $this->app->bind(BreedRepositoryInterface::class, BreedRepository::class);
        $this->app->bind(PetRepositoryInterface::class, PetRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
