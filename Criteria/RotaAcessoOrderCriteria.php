<?php

namespace Modules\Core\Criteria;

use Portal\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class RotaAcessoOrderCriteria
 * @package namespace Portal\Criteria;
 */
class RotaAcessoOrderCriteria extends BaseCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model->orderByRaw("
             CASE 
                WHEN rota_acessos.parent_id IS NULL
                  THEN rota_acessos.id
                ELSE  rota_acessos.parent_id
             END");
        if(is_null($this->whereArray)){
            return $model
             ->orderByRaw('rota_acessos.id');
        }
        if(!($model instanceof Builder)){
            $model->select($model->getTable().'.*');
        }
        if(array_key_exists('order',$this->whereArray)){
            $order = explode(';',$this->whereArray['order']);
            if(count($order)!=2)
                return $model;
            return $this->builderOrderBy($model,$order[0],$order[1]);
        }
        return $model;
    }
}
