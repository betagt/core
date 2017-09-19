<?php
    namespace Modules\Core\Rotas;
    use Portal\Interfaces\ICustomRoute;
    use \Route;
/**
 * Created by PhpStorm.
 * User: dsoft
 * Date: 06/02/2017
 * Time: 15:22
 */
class UserRoute implements ICustomRoute
{
    public static function run()
    {
        Route::get('admin/user/logout', [
            'as' => 'user.meu_perfil',
            'uses' => 'Api\Admin\UserController@logout'
        ]);
        Route::post('admin/user/faleconosco', [
            'as' => 'user.solicitar_nova_senha',
            'uses' => 'Api\Front\UserController@faleConosco'
        ]);
        Route::post('admin/user/password/reset', [
            'as' => 'user.solicitar_nova_senha',
            'uses' => 'Api\Admin\UserController@solicitarNovaSenha'
        ]);
        Route::post('admin/user/registrar', [
            'as' => 'user.registrar',
            'uses' => 'Api\Front\UserController@cadastrar'
        ]);

        Route::post('front/user/password/reset/change', [
            'as' => 'user.criar_nova_senha',
            'uses' => 'Api\Front\UserController@criarNovaSenha',
        ]);
        Route::post('front/user/password/reset', [
            'as' => 'user.solicitar_nova_senha',
            'uses' => 'Api\Front\UserController@solicitarNovaSenha'
        ]);

        Route::group(['prefix'=>'admin','middleware' => ['auth:api'],'namespace'=>'Api\Admin'],function (){
            Route::get('user/perfil/', [
                'as' => 'user.meu_perfil',
                'uses' => 'UserController@myProfile'
            ]);
            Route::put('user/password/change', [
                'as' => 'user.alterar_senha',
                'uses' => 'UserController@alterarSenha',
            ]);
            //Route::group(['middleware' => ['acl'],'is' => 'administrador', 'protect_alias'  => 'user'],function (){
            Route::group(['middleware'=>['acl'],'is'=>'administrador','protect_alias'=>'user'],function (){
               /* Route::post('user/password/reset', [
                    'as' => 'user.solicitar_nova_senha',
                    'uses' => 'UserController@solicitarNovaSenha'
                ]);*/
                Route::get('user/select-list/{like}', [
                    'as' => 'user.select-list',
                    'uses' => 'UserController@selectList',
                ])->where('like', '[A-Za-z0-9\s+]+');
                Route::put('user/userupdate', [
                    'as' => 'user.alterar_senha',
                    'uses' => 'UserController@updateCurrentUser',
                ]);
                Route::post('user/password/reset/change', [
                    'as' => 'user.criar_nova_senha',
                    'uses' => 'UserController@criarNovaSenha',
                ]);
                Route::post('user/alterar_imagem', [
                    'as' => 'user.alterar_imagem',
                    'uses' => 'UserController@changeImage',
                ]);

                Route::post('user/alterar_imagem_admin/{id}', [
                    'as' => 'user.alterar_imagem',
                    'uses' => 'UserController@changeImageAdmin',
                ]);

                Route::resource('user', 'UserController',[
                    'except' => ['create', 'edit']
                ]);
            });
        });
        Route::group(['prefix'=>'front','middleware' => ['auth:api','acl'],'is' => 'administrador|anunciante|moderator|qative','namespace'=>'Api\Front'],function (){


            Route::put('user/userupdate', [
                'as' => 'user.alterar_senha',
                'uses' => 'UserController@updateCurrentUser',
            ]);
            Route::get('user/perfil/', [
                'as' => 'user.meu_perfil',
                'uses' => 'UserController@myProfile'
            ]);

            Route::post('user/alterar_imagem', [
                'as' => 'user.alterar_imagem',
                'uses' => 'UserController@changeImage',
            ]);

            Route::put('user/password/change', [
                'as' => 'user.alterar_senha',
                'uses' => 'UserController@alterarSenha',
            ]);

        });
    }
}