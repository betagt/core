<?php

namespace Modules\Core\Repositories;

use Modules\Core\Presenters\RotaAcessoPresenter;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Modules\Core\Repositories\RotaAcessoRepository;
use Modules\Core\Models\RotaAcesso;
use Modules\Core\Validators\RotaAcessoValidator;

/**
 * Class RotaAcessoRepositoryEloquent
 * @package namespace Modules\Core\Repositories;
 */
class RotaAcessoRepositoryEloquent extends BaseRepository implements RotaAcessoRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return RotaAcesso::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function presenter()
    {
        return RotaAcessoPresenter::class;
    }

    public function findByRoleIds(array $roles, $ambiente = null)
    {
        $model = $this->model;
        if($ambiente)
            $model = $model->where('rota_acessos.ambiente','=',$ambiente);
        return $this->parserResult($model
            ->join('rota_acesso_roles','rota_acessos.id','=','rota_acesso_roles.rota_acesso_id')
            ->join('roles','roles.id','=','rota_acesso_roles.role_id')
            ->where('rota_acessos.parent_id','=',NULL)
            ->where('rota_acessos.is_menu','=',true)
            ->where(function ($query)use($roles){
                foreach ($roles as $role) {
                    $query->whereIn('rota_acesso_roles.role_id', [$role->id]);
                }
            })
            ->orderBy('prioridade', 'asc')
            ->select('rota_acessos.*')->get());
    }

    public function findByRoleAllIds(array $roles, $ambiente = null)
    {
        $model = $this->model;

        if($ambiente)
            $model = $model->where('rota_acessos.ambiente','=',$ambiente);

        return $this->parserResult($model
            ->join('rota_acesso_roles','rota_acessos.id','=','rota_acesso_roles.rota_acesso_id')
            ->join('roles','roles.id','=','rota_acesso_roles.role_id')
            ->where(function ($query)use($roles){
                foreach ($roles as $role) {
                    $query->whereIn('rota_acesso_roles.role_id', [$role->id]);
                }
            })->orderBy('prioridade', 'asc')->select('rota_acessos.*')->get());
    }
}
