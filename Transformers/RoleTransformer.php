<?php

namespace Modules\Core\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Core\Models\Role;

/**
 * Class RoleTransformer
 * @package namespace Modules\Core\Transformers;
 */
class RoleTransformer extends TransformerAbstract
{
    public $availableIncludes = ['permissions', 'rota_acesso'];

    /**
     * Transform the \Role entity
     * @param \Role $model
     *
     * @return array
     */
    public function transform(Role $model)
    {
        return [
            'id'          => (int) $model->id,
            'name'        => (string) $model->name,
            'slug'        => (string) $model->slug,
            'description' => (string) $model->description,
            'created_at'  => $model->created_at,
            'updated_at'  => $model->updated_at
        ];
    }

    public function includePermissions(Role $model)
    {
        if (!$model->permissions)
        {
            return null;
        }
        return $this->collection($model->permissions, new PermissionTransformer());
    }

    public function includeRotaAcesso(Role $model)
    {
        if (!$model->rotaAcessos)
        {
            return null;
        }
        return $this->collection($model->rotaAcessos, new RotaAcessoTransformer());
    }

}
