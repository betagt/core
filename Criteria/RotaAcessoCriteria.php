<?php


namespace Modules\Core\Criteria;


use Portal\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FormaPgtoCriteria
 * @package namespace Portal\Criteria;
 */
class RotaAcessoCriteria extends BaseCriteria implements CriteriaInterface
{
    protected $filterCriteria = [
        'rota_acessos.text' =>'ilike',
        'rota_acessos.rota'=>'ilike',
        'rota_acessos.parent_id'=>'=',
        'rota_acessos.disabled'=>'=',
        'rota_acessos.is_menu'=>'=',
    ];
}