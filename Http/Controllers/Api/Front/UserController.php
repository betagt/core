<?php

namespace Modules\Core\Http\Controllers\Api\Front;

use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Lcobucci\JWT\Parser;
use Modules\Core\Criteria\UserCriteria;
use Modules\Core\Events\UsuarioCadastrado;
use Modules\Core\Http\Requests\UserResetSendEmailRequest;
use Modules\Core\Models\User;
use Portal\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\UserResetPasswordRequest;
use Modules\Core\Repositories\UserRepository;
use Modules\Core\Services\ImageUploadService;
use Portal\Services\MailService;
use Prettus\Repository\Exceptions\RepositoryException;
use Validator;

/**
 * @resource API Usuário - Frontend
 *
 * Essa API é responsável pelo gerenciamento de Usuários no Modules\Core qImob.
 * Os próximos tópicos apresenta os endpoints de Consulta, Cadastro, Edição e Deleção.
 */
class UserController extends BaseController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    private $passwordBroker;
    /**
     * @var ImageUploadService
     */
    private $imageUploadService;
    /**
     * @var MailService
     */
    private $mailService;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     * @param PasswordBroker $passwordBroker
     */
    public function __construct(
        UserRepository $userRepository,
        PasswordBroker $passwordBroker,
        ImageUploadService $imageUploadService,
        MailService $mailService)
    {
        $this->userRepository = $userRepository;
        $this->passwordBroker = $passwordBroker;
        $this->setPathFile(public_path('arquivos/img/user'));
        $this->imageUploadService = $imageUploadService;
        parent::__construct($userRepository, UserCriteria::class);
        $this->mailService = $mailService;
    }

    /**
     * @return array
     */
    public function getValidator($id = null)
    {
        return $this->validator;
    }

    /**
     * Consultar Perfil Usuário
     *
     * Endpoint para consultar perfil do usuário passando o ID como parametro
     *
     * @param $id
     * @return mixed
     */
    public function myProfile(Request $request){
        $id  = $request->user()->id;
        try{
            return $this->userRepository->find($id);
        }catch (ModelNotFoundException $e){
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
     * Cadastrar Anunciante
     *
     * Endpoint para cadastrar página Anunciante
     *
     */
    public function cadastrar(Request $request){
        $request->merge(['email_alternativo'=>$request->get('email')]);
        $request->merge(['remember_token' => str_random(10)]);
        $request->merge(['status'=>User::ATIVO]);
        $data = $request->all();
        \Validator::make($data, [
            'name'=>'required|min:3|string',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:3',
            'email_alternativo'     => 'nullable|email',
            'data_nascimento'       => 'date|nullable',
            'sexo'                  => 'integer|in:1,2|nullable',
            'chk_newsletter'        => 'boolean|nullable',
        ])->validate();
        try {
            $data = $this->userRepository->skipPresenter(true)->create($data);
            $data->assignRole('anunciante');
            $user = $this->userRepository->skipPresenter(false)->find($data->id);
            event(new UsuarioCadastrado($this->userRepository->skipPresenter(true)->find($user['data']['id']),'anunciante'));
            return $user;
        } catch (ModelNotFoundException $e) {
            return self::responseError(self::HTTP_CODE_NOT_FOUND, $e->getMessage());
        } catch (RepositoryException $e) {
            return self::responseError(self::HTTP_CODE_NOT_FOUND, $e->getMessage());
        } catch (\Exception $e) {
            return self::responseError(self::HTTP_CODE_BAD_REQUEST, $e->getMessage());
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
    public function updateCurrentUser(Request $request)
    {
        $id = $request->user()->id;
        $data = $request->all();
        \Validator::make($data, [
            'name'=>'required|min:3|string',
            'password'=>'nullable|min:3',
            'email_alternativo'     => 'nullable|email',
            'data_nascimento'       => 'date|nullable',
            'sexo'                  => 'integer|in:1,2|nullable',
            'chk_newsletter'        => 'boolean|nullable',
            'ddd'        => 'numeric|nullable',
            'numero'        => 'numeric|nullable',

            'old_password' => 'nullable|alphaNum|min:8',
            'new_password' => 'nullable|alphaNum|min:8|confirmed'
        ])->validate();
        try {
            $user = $this->userRepository->skipPresenter(true)->update($data, $id);
            if(!empty($data['ddd']) && !empty($data['numero'])){
                if(!is_null($user->telefone)){
                    $user->telefone->ddd = $data['ddd'];
                    $user->telefone->numero = $data['numero'];
                    $user->telefone->save();
                }else{
                    $data['principal'] = true;
                    $data['tipo'] = 'fixo';
                    $user->telefone()->create($data);
                }
            }
            return $this->userRepository->skipPresenter(false)->parserResult($user);
        } catch (ModelNotFoundException $e) {
            return self::responseError(self::HTTP_CODE_NOT_FOUND, trans('errors.registre_not_found', ['status_code' => $e->getCode(), 'line' => $e->getLine()]));
        } catch (RepositoryException $e) {
            return self::responseError(self::HTTP_CODE_NOT_FOUND, trans('errors.registre_not_found', ['status_code' => $e->getCode(), 'line' => $e->getLine()]));
        } catch (\Exception $e) {
            return self::responseError(self::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }

    /**
     * Alterar Imagem Anunciante logado
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function changeImage(Request $request){
        $data = $request->all();
        Validator::make($data, [
            'imagem' => [
                'required',
                'image',
                'mimes:jpg,jpeg,bmp,png'
            ]
        ])->validate();
        try{
            $this->imageUploadService->upload('imagem',$this->getPathFile(),$data);
            return $this->userRepository->update($data,$request->user()->id);
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
     * Solicitar Nova Senha
     *
     * Enviar email com link para usuário recuperar senha
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function solicitarNovaSenha(UserResetSendEmailRequest $request)
    {
        $response = $this->passwordBroker->sendResetLink($request->only('email'), function ($m) {
            $m->subject($this->getEmailSubject());
        });

        switch ($response) {
            case PasswordBroker::RESET_LINK_SENT:
                return parent::responseSuccess(parent::HTTP_CODE_OK, "O link de recuperação de senha foi enviado para seu endereço de e-mail");
            case PasswordBroker::INVALID_USER:
                return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, "Não é possível encontrar um usuário com esse endereço de e-mail");
        }
    }

    /**
     * Criar Nova Senha
     *
     * Enviar dados para criar a nova senha solicitada pelo Anunciante
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function criarNovaSenha(UserResetPasswordRequest $request)
    {
        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
        $response = $this->passwordBroker->reset($credentials, function($user, $password)
        {
            $user->forceFill([
                'password' => $password,
                'remember_token' => Str::random(10),
            ])->save();
        });

        switch ($response)
        {
            case PasswordBroker::PASSWORD_RESET:
                return parent::responseSuccess(parent::HTTP_CODE_OK, "Senha alterada com sucesso.");
            case PasswordBroker::INVALID_TOKEN:
                return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, "O Token de reinicialização inválida ou já expirou.");
            case PasswordBroker::INVALID_USER:
                return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, "Não é possível encontrar um Anunciante com esse endereço de e-mail");
            default:
                return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, "Erro inesperado.");
        }
    }

    /**
     * Alterar Senha
     *
     * Enviar dados para alterar a senha
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function alterarSenha(Request $request)
    {
        $data = $request->all();
        Validator::make($data, [
            'old_password' => 'required',
            'new_password' => 'required|alphaNum|min:8|confirmed'
        ])->validate();
        $user = $request->user();
        if(password_verify($request->get('old_password'), $user->password)) {
            $this->userRepository->update(['password' => $request->get('new_password')], $user->id);
            return parent::responseSuccess(parent::HTTP_CODE_OK, "Senha alterada com sucesso.");
        }
        return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, "A senha atual não confere.");
    }

    public function logout(Request $request)
    {
        $value = $request->bearerToken();
        $id = (new Parser())->parse($value)->getHeader('jti');

        \DB::table('oauth_access_tokens')
            ->where('id', '=', $id)
            ->update(['revoked' => true]);
        //$this->getRequest()->session()->flush();
        $json = [
            'success' => true,
            'code' => 200,
            'message' => 'You are Logged out.',
        ];
        return response()->json($json, '200');
    }

    public function faleConosco(Request $request){
        $data = $request->only('nome','email','telefone','website','assunto','mensagem');
        Validator::make($data, [
            'nome' => 'required',
            'telefone' => 'required',
            'assunto' => 'required',
            'mensagem' => 'required|max:700',
            'email' => 'required|email'
        ])->validate();
        try{
            $this->mailService->queue(env('EMAIL_DEFAULT'),'Fale Conosco! - '.$data['assunto'],'emails.user.faleconosco',$data);
            return parent::responseSuccess(parent::HTTP_CODE_OK, "Mensagem Enviada!");
        }catch (\Exception $e){
            return parent::responseError(parent::HTTP_CODE_BAD_REQUEST, $e->getMessage());
        }
    }
}
