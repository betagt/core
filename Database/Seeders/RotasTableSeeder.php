<?php

use Illuminate\Database\Seeder;

class RotasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\Modules\Core\Models\RotaAcesso::class)->create([
            'text' => 'Inicio',
            'rota' => '',
            'icon' => 'zmdi zmdi-home',
            'disabled' => false,
            'is_menu'=>true,
        ])->roles()->attach([1]);
       $usuario = factory(\Modules\Core\Models\RotaAcesso::class)->create([
            'text' => 'Usuarios',
            'rota' => '',
            'icon' => 'zmdi zmdi-accounts',
            'disabled' => false,
            'is_menu'=>true,
       ]);
        $usuario->roles()->attach([1]);
        factory(\Modules\Core\Models\RotaAcesso::class)->create([
            'parent_id' => $usuario->id,
            'text' => 'Listar',
            'rota' => '/usuarios',
            'icon' => '',
            'disabled' => false,
            'is_menu'=>true,
        ])->roles()->attach([1]);
        factory(\Modules\Core\Models\RotaAcesso::class)->create([
            'parent_id' => $usuario->id,
            'text' => 'Novo',
            'rota' => '/usuarios/new',
            'icon' => '',
            'disabled' => false,
            'is_menu'=>false,
        ])->roles()->attach([1]);
        factory(\Modules\Core\Models\RotaAcesso::class)->create([
            'parent_id' => $usuario->id,
            'text' => 'Editar',
            'rota' => '/usuarios/:id/edit',
            'icon' => '',
            'disabled' => false,
            'is_menu'=>false,
        ])->roles()->attach([1]);
        $rotas = factory(\Modules\Core\Models\RotaAcesso::class)->create([
            'text' => 'Acessos',
            'rota' => '',
            'icon' => 'zmdi zmdi-lock',
            'disabled' => false,
            'is_menu'=>true,
        ]);
        $rotas->roles()->attach([1]);
        factory(\Modules\Core\Models\RotaAcesso::class)->create([
            'parent_id' => $rotas->id,
            'text' => 'Listar',
            'rota' => '/rota_acesso',
            'icon' => '',
            'disabled' => false,
            'is_menu'=>true,
        ])->roles()->attach([1]);
        factory(\Modules\Core\Models\RotaAcesso::class)->create([
            'parent_id' => $rotas->id,
            'text' => 'Novo',
            'rota' => '/rota_acesso/new',
            'icon' => '',
            'disabled' => false,
            'is_menu'=>false,
        ])->roles()->attach([1]);
        factory(\Modules\Core\Models\RotaAcesso::class)->create([
            'parent_id' => $rotas->id,
            'text' => 'Editar',
            'rota' => '/rota_acesso/:id/edit',
            'icon' => '',
            'disabled' => false,
            'is_menu'=>false,
        ])->roles()->attach([1]);
        factory(\Modules\Core\Models\RotaAcesso::class)->create([
            'text' => 'Configurações',
            'rota' => '',
            'icon' => 'zmdi zmdi-home',
            'disabled' => false,
            'is_menu'=>true,
        ])->roles()->attach([1]);
    }
}
