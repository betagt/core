<?php

namespace Modules\Core\Http\Controllers\Api\Admin;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Modules\Anuncio\Criteria\MensagemAnuncioCriteria;
use Modules\Core\Criteria\NewsletterCriteria;
use Modules\Core\Http\Requests\NewsletterResquest;
use Modules\Core\Repositories\NewsletterRepository;
use Portal\Http\Controllers\BaseController;
use Prettus\Repository\Exceptions\RepositoryException;

class NewsletterController extends BaseController
{
    /**
     * @var NewsletterRepository
     */
    private $newsletterRepository;

    public function __construct(NewsletterRepository $newsletterRepository)
    {
        parent::__construct($newsletterRepository, NewsletterCriteria::class);
        $this->newsletterRepository = $newsletterRepository;
    }

    public function getValidator($id = null)
    {
        $this->validator = (new NewsletterResquest())->rules($id);
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
    public function newsletterMobile(Request $request){
        $data = $request->all();
        $data['plataforma'] = 'mobile';
        \Validator::make($data, $this->getValidator())->validate();
        try{
            return $this->newsletterRepository->create($data);
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
     * Cadastrar
     *
     * Endpoint para cadastrar
     *
     * @param Request $request
     * @return retorna um registro criado
     */
    public function newsletterPortal(Request $request){
        $data = $request->all();
        $data['plataforma'] = 'portal';
        \Validator::make($data, $this->getValidator())->validate();
        try{
            return $this->newsletterRepository->create($data);
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
