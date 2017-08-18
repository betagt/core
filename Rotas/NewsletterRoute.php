<?php
/**
 * Created by PhpStorm.
 * User: dsoft
 * Date: 06/02/2017
 * Time: 15:58
 */

namespace Modules\Core\Rotas;

use Portal\Interfaces\ICustomRoute;
use \Route;

class NewsletterRoute implements ICustomRoute
{

    public static function run()
    {
        Route::group(['prefix'=>'front', 'namespace'=>'Api\Admin'],function () {
            Route::resource('newsletter', 'NewsletterController',
                [
                    'except' => ['edit','show','update','create', 'edit']
                ]);
            Route::group(['middleware' => ['acl', 'auth:api'],'is' => 'administrador|anunciante|moderator|qative', 'protect_alias'  => 'user'],function (){
            });
        });
    }
}