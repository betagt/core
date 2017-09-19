<?php

namespace Modules\Core\Transformers;

use Modules\Core\Models\User;
use Modules\Localidade\Transformers\EnderecoTransformer;
use Modules\Localidade\Transformers\TelefoneTransformer;
use Portal\Transformers\BaseTransformer;

/**
 * Class UserTransformer
 * @package namespace Modules\Core\Transformers;
 */
class UserTransformer extends BaseTransformer
{
    public $availableIncludes = ['permissions', 'roles'];
    public $defaultIncludes = [ 'endereco', 'telefones'];

    /**
     * Transform the \User entity
     * @param \User $model
     *
     * @return array
     */
    public function transform(User $model)
    {
        return [
            'id' => (int)$model->id,
            'name' => (string)$model->name,
            'email' => (string)$model->email,
            'email_alternativo' => (string)$model->email_alternativo,
            'pagina_user' => (string)$model->pagina_user,
            'sexo_label' => ($model->sexo)?(string)User::$_SEXO[$model->sexo]:null,
            'sexo' => (int)$model->sexo,
            'cep' => $model->cep,
            'data_nascimento' => $model->data_nascimento,
            'cpf_cnpj' => (string)$model->cpf_cnpj,
            'tipo' => (int)$model->tipo(),
            'imagem' => (string) ($model->imagem) ? url('/arquivos/img/user/' . $model->imagem) : null,
            'status' => (string)$model->status,
            'ddd' => (is_null($model->telefone))?null:$model->telefone->ddd,
            'numero' => (is_null($model->telefone))?null:$model->telefone->numero,
            'chk_newsletter' => (boolean)$model->chk_newsletter,
            'excluido' => (boolean)$model->trashed(),
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }

    public function includePermissions(User $model)
    {
        if (!$model->permissions) {
            return null;
        }
        return $this->collection($model->permissions, new PermissionTransformer());
    }

    public function includeRoles(User $model)
    {
        if (!$model->return_roles) {
            return null;
        }
        return $this->collection($model->return_roles, new RoleTransformer());
    }

    public function includeTelefones(User $model)
    {
        if (!$model->telefones) {
            return null;
        }
        return $this->collection($model->telefones, new TelefoneTransformer());
    }

    public function includeEndereco(User $model)
    {
        if (!$model->endereco) {
            return null;
        }
        return $this->item($model->endereco, new EnderecoTransformer());
    }
}
