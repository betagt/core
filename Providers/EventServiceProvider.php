<?php

namespace Modules\Core\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Core\Events\UsuarioCadastrado;
use Modules\Core\Listeners\EmailDeCadastro;
use Modules\Core\Listeners\RegraDeAcesso;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
            UsuarioCadastrado::class => [
                EmailDeCadastro::class,
                RegraDeAcesso::class,
            ],
        ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
