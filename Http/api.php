<?php
Route::group(['prefix'=>'v1','middleware' => ['cors']], function () {
    \Modules\Core\Rotas\UserRoute::run();
    \Modules\Core\Rotas\RoleRoute::run();
    \Modules\Core\Rotas\PermissionRoute::run();
    \Modules\Core\Rotas\RotaAcessoRoute::run();
    \Modules\Core\Rotas\NewsletterRoute::run();
});