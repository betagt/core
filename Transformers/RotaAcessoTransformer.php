<?php

namespace Modules\Core\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Core\Models\RotaAcesso;

/**
 * Class RotaAcessoTransformer
 * @package namespace Modules\Core\Transformers;
 */
class RotaAcessoTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['roles'];
    //public $defaultIncludes = ['child'];

    /**
     * Transform the \RotaAcesso entity
     * @param \RotaAcesso $model
     *
     * @return array
     */
    public function transform(RotaAcesso $model)
    {
        return [
            'id'        => (int) $model->id,
            'parent_id' => $model->parent_id,
            'text'      => (string) $model->text,
            'rota'      => (string) $model->rota,
            'icon'      => (string) $model->icon,
            'disabled'      => (boolean) $model->disabled,
            'is_menu'      => (boolean) $model->is_menu,
            'ambiente'      => (string) $model->ambiente,
            'prioridade'      => (int) $model->prioridade,
            'child'      => is_null($model->child)?[]:array_values($model->child->where('is_menu',true)->toArray()),
            'pai'      => is_null($model->pai)?null:$model->pai,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }

    public function includeRoles(RotaAcesso $model){
        if(is_null($model->roles)){
            return null;
        }
        return $this->collection($model->roles, new RoleTransformer());
    }

    public function includeChild(RotaAcesso $model){
        if(is_null($model->child)){
            return null;
        }

        return $this->collection($model->child, new RotaAcessoTransformer());
    }
}
