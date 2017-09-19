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

class RoleRoute implements ICustomRoute
{

    public static function run()
    {
        Route::group(['prefix'=>'admin','middleware' => ['auth:api'],'namespace'=>'Api\Admin'],function () {
            Route::group(['middleware' => ['acl'],'is' => 'administrador', 'protect_alias'  => 'user'],function (){
                Route::get('regra/revogar_todas_regras_usuario/{id}', [
                    'as' => 'regra.revogar_todas_regras_usuario',
                    'uses' => 'RoleController@revokeAllRoles'
                ]);
                Route::get('regra/lista-roles', [
                    'as' => 'regra.lista_roles',
                    'uses' => 'RoleController@listaSelect'
                ]);
                Route::post('regra/sincronizar_regra_permissoes', [
                    'as' => 'regra.sincronizar_regra_permissoes',
                    'uses' => 'RoleController@syncPermissions'
                ]);
                Route::post('regra/revogar_regra_usuario', [
                    'as' => 'regra.associar_regra_usuario',
                    'uses' => 'RoleController@revokeRole'
                ]);
                Route::post('regra/associar_regra_usuario', [
                    'as' => 'regra.associar_regra_usuario',
                    'uses' => 'RoleController@assocRuleUser'
                ]);
                Route::resource('regra', 'RoleController',
                    [
                        'except' => ['create', 'edit']
                    ]);
                Route::get('regra/regras_do_usuario/{id}', [
                    'as' => 'regra.regras_do_usuario',
                    'uses' => 'RoleController@roleByUser'
                ]);
            });
        });
    }
}