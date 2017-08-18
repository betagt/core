<?php


namespace Modules\Core\Services;


use Modules\Core\Models\Role;
use Modules\Core\Repositories\RoleRepository;

class RoleService
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function createAssoc($data){
        $user = \Modules\Core\Models\User::find($data['user_id']);
        return $user->assignRole(Role::find($data['role_id']));
    }

    public function createOrUpdate($data, $id=null){
        \DB::beginTransaction();
        try{
            $role = $this->roleRepository->skipPresenter(true)->updateOrCreate(['id'=>$id],$data);
            if(isset($data['permissions']))
                $role->syncPermissions($data['permissions']);
            \DB::commit();
            return $this->roleRepository->parserResult($role);
        }catch (\Exception $e){
            \DB::rollback();
        }
    }
}