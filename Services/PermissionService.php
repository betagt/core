<?php
/**
 * Created by PhpStorm.
 * User: dsoft
 * Date: 06/01/2017
 * Time: 13:50
 */

namespace Modules\Core\Services;


use Modules\Core\Models\Permission;
use Modules\Core\Models\Role;
use Modules\Core\Models\User;
use Modules\Core\Repositories\PermissionRepository;
use Modules\Core\Repositories\RoleRepository;
use Modules\Core\Repositories\UserRepository;

class PermissionService
{
    /**
     * @var PermissionRepository
     */
    private $permissionRepository;
    /**
     * @var RoleRepository
     */
    private $roleRepository;

    const defaltSlug = [
        ['value'=>'create','label'=>'Criar'],
        ['value'=>'store','label'=>'Salvar'],
        ['value'=>'read','label'=>'Vizualizar local'],
        ['value'=>'view','label'=>'Vizualizar API'],
        ['value'=>'edit','label'=>'Editar local'],
        ['value'=>'update','label'=>'Editar API'],
        ['value'=>'delete','label'=>'Delete API'],
        ['value'=>'trasheds','label'=>'Lixeira'],
        ['value'=>'excluir','label'=>'Excluir'],
        ['value'=>'restaurar','label'=>'restaurar'],
    ];
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(PermissionRepository $permissionRepository, RoleRepository $roleRepository,UserRepository $userRepository)
    {
        $this->permissionRepository = $permissionRepository;
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param int $roleId
     * @param int $permissionId
     * @return mixed
     */
    public function assocPermissionRole(int $roleId, $permUser){
        return $this->roleRepository->assignPermission($roleId, $permUser);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function assocPermissionUser($data){
        $rs = $this->userRepository->addPermission($data['user_id'],$data['name'],$data['slug']);
        if( !$rs){
            abort(301,'Alias ja esite');
        }
        return $rs;
    }
}