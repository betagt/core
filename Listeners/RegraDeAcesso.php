<?php

namespace Modules\Core\Listeners;


use Modules\Core\Events\UsuarioCadastrado;

class RegraDeAcesso
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UsuarioCadastrado  $event
     * @return void
     */
    public function handle(UsuarioCadastrado $event)
    {
       $role = $event->getRole();
       if(empty($role)){
           return null;
       }

       return $event->getUser()->assignRole($role);
    }
}
