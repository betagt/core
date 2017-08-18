<?php


namespace Modules\Core\Rotas;
use Portal\Interfaces\ICustomRoute;
use \Route;

class RotaAcessoRoute implements ICustomRoute
{

    public static function run()
    {
        Route::group(['prefix'=>'admin','middleware' => ['auth:api'],'namespace'=>"Api\Admin"],function (){
            Route::get('rota_acesso/rota_por_regra/{ambiente}', [
                'as' => 'regra.rota_por_regra',
                'uses' => 'RotaAcessoController@rotasByRole'
            ]);
            Route::get('rota_acesso/check_rota/{ambiente}', [
                'as' => 'regra.check_rota',
                'uses' => 'RotaAcessoController@checkRotasByRole'
            ]);

            Route::group(['middleware'=>['acl'],'is'=>'administrador','protect_alias'=>'user'],function (){
                Route::resource("rota_acesso", "RotaAcessoController",[
                    'except' => ['create', 'edit']
                ]);
            });
        });
    }
}