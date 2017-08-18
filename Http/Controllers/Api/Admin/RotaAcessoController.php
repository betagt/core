<?php
/**
 * Created by PhpStorm.
 * User: dsoft
 * Date: 16/03/2017
 * Time: 11:36
 */
namespace Modules\Core\Http\Controllers\Api\Admin;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Modules\Core\Criteria\RotaAcessoCriteria;
use Portal\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\RotaAcessoRequest;
use Modules\Core\Repositories\RotaAcessoRepository;
use Modules\Core\Services\RotaAcessoService;
use Prettus\Repository\Exceptions\RepositoryException;

class RotaAcessoController extends BaseController
{
    /**
     * @var RotaAcessoRepository
     */
    private $RotaAcessoRepository;
    /**
     * @var RotaAcessoService
     */
    private $rotaAcessoService;

    public function __construct(
        RotaAcessoRepository $RotaAcessoRepository,
        RotaAcessoService $rotaAcessoService)
    {
        parent::__construct($RotaAcessoRepository, RotaAcessoCriteria::class);
        $this->RotaAcessoRepository = $RotaAcessoRepository;
        $this->rotaAcessoService = $rotaAcessoService;
    }

    /**
     * @return array
     */
    public function getValidator($id = null)
    {
        $this->validator = (new RotaAcessoRequest())->rules();
        return $this->validator;
    }

    /**
     * Cadastrar
     *
     * Endpoint para cadastrar
     *
     * @param Request $request
     * @return retorna um registro criado
     */
    public function store(Request $request){
        $data = $request->all();
        \Validator::make($data, $this->getValidator())->validate();
        try{
            return $this->rotaAcessoService->createOrUpdate($data);
        }catch (ModelNotFoundException $e){
            return self::responseError(self::HTTP_CODE_NOT_FOUND, trans('errors.registre_not_found', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
        catch (RepositoryException $e){
            return self::responseError(self::HTTP_CODE_NOT_FOUND, trans('errors.registre_not_found', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
        catch (\Exception $e){
            return self::responseError(self::HTTP_CODE_BAD_REQUEST, trans('errors.undefined', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
    }

    /**
     * Alterar
     *
     * Endpoint para alterar
     *
     * @param Request $request
     * @param $id
     * @return retorna registro alterado
     */
    public function update(Request $request, $id){
        $data = $request->all();
        \Validator::make($data, $this->getValidator($id))->validate();
        try{
            return $this->rotaAcessoService->createOrUpdate($request->all(),$id);
        }catch (ModelNotFoundException $e){
            return self::responseError(self::HTTP_CODE_NOT_FOUND, trans('errors.registre_not_found', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
        catch (RepositoryException $e){
            return self::responseError(self::HTTP_CODE_NOT_FOUND, trans('errors.registre_not_found', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
        catch (\Exception $e){
            return self::responseError(self::HTTP_CODE_BAD_REQUEST, trans('errors.undefined', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
    }

    public function rotasByRole($ambiente, Request $request){
        try{
            return $this->RotaAcessoRepository->findByRoleIds($request->user()->roles->all(), $ambiente);
        }catch (ModelNotFoundException $e){
            return self::responseError(self::HTTP_CODE_NOT_FOUND, trans('errors.registre_not_found', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
        catch (RepositoryException $e){
            return self::responseError(self::HTTP_CODE_NOT_FOUND, trans('errors.registre_not_found', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
        catch (\Exception $e){
            return self::responseError(self::HTTP_CODE_BAD_REQUEST, trans('errors.undefined', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
    }

    public function checkRotasByRole($ambiente, Request $request){
        try{
            return $this->RotaAcessoRepository->findByRoleAllIds($request->user()->roles->all(), $ambiente);
        }catch (ModelNotFoundException $e){
            return self::responseError(self::HTTP_CODE_NOT_FOUND, trans('errors.registre_not_found', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
        catch (RepositoryException $e){
            return self::responseError(self::HTTP_CODE_NOT_FOUND, trans('errors.registre_not_found', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
        catch (\Exception $e){
            return self::responseError(self::HTTP_CODE_BAD_REQUEST, trans('errors.undefined', ['status_code'=>$e->getCode(),'message'=>$e->getMessage()]));
        }
    }
}