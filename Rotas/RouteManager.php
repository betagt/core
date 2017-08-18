<?php
/**
 * Created by PhpStorm.
 * User: dsoft
 * Date: 06/02/2017
 * Time: 16:10
 */

namespace Modules\Core\Rotas;

use Portal\Interfaces\ICustomRoute;

class RouteManager implements ICustomRoute
{

    public static function run()
    {
        \Route::group(['prefix'=>'v1','middleware' => ['cors']], function () {
            \Modules\Core\Rotas\UserRoute::run();
            \Modules\Core\Rotas\RoleRoute::run();
            \Modules\Core\Rotas\PermissionRoute::run();
            \Modules\Core\Rotas\RotaAcessoRoute::run();
        });
    }
}