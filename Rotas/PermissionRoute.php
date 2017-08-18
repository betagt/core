<?php
/**
 * Created by PhpStorm.
 * User: dsoft
 * Date: 06/02/2017
 * Time: 16:00
 */

namespace Modules\Core\Rotas;

use Portal\Interfaces\ICustomRoute;
use \Route;

class PermissionRoute implements ICustomRoute
{

    public static function run()
    {
        Route::group(['prefix'=>'admin','middleware' => ['auth:api'],'namespace'=>'Api\Admin'],function (){
            Route::get('permissao/ativas', [
                'as' => 'permissao.ativas',
                'uses' => 'PermissionController@ativas'
            ]);
            Route::group(['middleware' => ['acl'],'is' => 'administrador', 'protect_alias'  => 'user'],function (){
                Route::get('permissao/revogar_permissoes/{id}', [
                    'as' => 'permissao.revogar_permissoes',
                    'uses' => 'PermissionController@revokeAllPermissions'
                ]);
                Route::get('permissao/lista_de_permissao_regra/{id}', [
                    'as' => 'permissao.lista_de_permissao_regra',
                    'uses' => 'PermissionController@permissionByRole'
                ]);
                Route::get('permissao/lista_de_permissao_usuario/{id}', [
                    'as' => 'permissao.lista_de_permissao_usuario',
                    'uses' => 'PermissionController@permissionByUser'
                ]);
                Route::get('permissao/list_slugs', [
                    'as' => 'permissao.list_slugs',
                    'uses' => 'PermissionController@listSlugs'
                ]);
                Route::get('permissao/lista-select', [
                    'as' => 'permissao.list_slugs',
                    'uses' => 'PermissionController@listaSelect'
                ]);
                Route::post('permissao/associar_permissao_regra', [
                    'as' => 'permissao.associar_permissao_regra',
                    'uses' => 'PermissionController@assocPermissionRole'
                ]);
                Route::post('permissao/criar_permissao_usuario', [
                    'as' => 'permissao.criar_permissao_usuario',
                    'uses' => 'PermissionController@assocPermissionUser'
                ]);
                Route::resource('permissao', 'PermissionController',[
                    'except' => ['create', 'edit']
                ]);
            });
        });
    }
}