<?php

namespace Modules\Core\Http\Controllers\Api\Admin;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Core\Criteria\OrderCriteria;
use Modules\Core\Criteria\PermissionCriteria;
use Portal\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\PermissionRequest;
use Modules\Core\Repositories\PermissionRepository;
use Modules\Core\Repositories\RoleRepository;
use Modules\Core\Repositories\UserRepository;
use Modules\Core\Services\PermissionService;
use Prettus\Repository\Exceptions\RepositoryException;
use Validator;


/**
 * @resource API Permissão de Usuários - Backend
 *
 * Essa API é responsável pelo gerenciamento de Permissão de Usuários no portal qImob.
 * Os próximos tópicos apresenta os endpoints de Consulta, Cadastro, Edição e Deleção.
 */
class PermissionController extends BaseController
{


    /**
     * @var RoleRepository
     */
    private $roleRepository;
    /**
     * @var PermissionRepository
     */
    private $permissionRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var PermissionService
     */
    private $permissionService;

    public function __construct(
        RoleRepository $roleRepository,
        PermissionRepository $permissionRepository,
        UserRepository $userRepository,
        PermissionService $permissionService
    ){
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
        $this->userRepository = $userRepository;
        $this->permissionService = $permissionService;
        parent::__construct($permissionRepository, PermissionCriteria::class);
    }
    /**
     * @return array
     */
    public function getValidator($id = null)
    {
        $this->validator = (new PermissionRequest())->rules($id);
        return $this->validator;
    }

    /**
     *
     * Associar Permissão a Regra
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assocPermissionRole(Request $request){
        $data = $request->all();
        Validator::make($data, [
            'role_id' => [
                'required',
                'exists:roles,id',
                'integer',
                Rule::unique('permission_role')->where(function ($query) use ($data){
                    $query->where('permission_id', $data['permission']);
                    $query->where('role_id', $data['role_id']);
                }),
            ],
            'permission' => [
                'required',
                'exists:permissions,id',
                'integer',
                Rule::unique('permission_role')->where(function ($query) use ($data){
                    $query->where('permission_id', $data['permission']);
                    $query->where('role_id', $data['role_id']);
                }),
            ],
        ])->validate();
        try{
            $this->permissionService->assocPermissionRole($data['role_id'],$data['permission']);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, trans('errors.registre_not_found', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, trans('errors.undefined', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
    }

    /**
     * Listar todas permissões do grupo.
     *
     * @param $id
     * @return mixed
     */
    public function permissionByRole($id){
        try{
            return $this->roleRepository->getPermissions($id);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, trans('errors.registre_not_found', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
        catch (RepositoryException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, trans('errors.registre_not_found', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, trans('errors.undefined', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
    }

    /**
     * Listar todas permissões do grupo.
     *
     * @param $id
     * @return mixed
     */
    public function ativas(Request $request){
       return $this->permissionByRole($request->user()->role->id);
    }

    /**
     * Listar todas permissões do usuário.
     *
     * @param $id
     * @return mixed
     */
    public function permissionByUser($id){
        try{
            return $this->roleRepository->getPermissions($id);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, trans('errors.registre_not_found', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, trans('errors.undefined', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
    }

    /**
     * Revogar Permissões
     */
    public function revokeAllPermissions($id){
        try{
            $this->roleRepository->revokeAllPermissions($id);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, trans('errors.registre_not_found', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, trans('errors.undefined', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
    }

    /**
     * Remover permissão do usuário
     *
     * @param Request $request
     * @return mixed
     */
    public function removePermission(PermissionRequest $request){
        $data = $request->all();
        Validator::make($data, [
            'user_id' => [
                'required',
                'exists:users,id',
                'integer'
            ],
        ])->validate();
        $slug = $data['slug'];
        try{
            return $this->userRepository->removePermission($data['name'],$slug);
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, trans('errors.registre_not_found', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, trans('errors.undefined', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
    }

    /**
     * Listar tipos de permissões de requisições
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listSlugs(){
        return response()->json(['data'=>PermissionService::defaltSlug],parent::HTTP_CODE_OK['status']);
    }

    /**
     * Criar uma permissão para um unico usuário.
     *
     * @param PermissionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assocPermissionUser(PermissionRequest $request){
        $data = $request->all();
        Validator::make($data, [
            'user_id' => [
                'required',
                'exists:users,id',
                'integer'
            ],
        ])->validate();
        try{
            $this->permissionService->assocPermissionUser($data);
        }catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, trans('errors.registre_not_found', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, trans('errors.undefined', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
    }

    public function listaSelect(){
        try{
            return $this->permissionRepository->all();
        }
        catch (ModelNotFoundException $e){
            return parent::responseError(parent::HTTP_CODE_NOT_FOUND, trans('errors.registre_not_found', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
        catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, trans('errors.undefined', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
    }
}
