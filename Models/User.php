<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kodeine\Acl\Traits\HasRole;
use Laravel\Passport\HasApiTokens;
use Modules\Localidade\Models\Endereco;
use Modules\Localidade\Models\Telefone;
use OwenIt\Auditing\Auditable;
use Portal\Notifications\PasswordReset;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class User extends Authenticatable implements Transformable
{
    use Notifiable, HasApiTokens, SoftDeletes, TransformableTrait, HasRole, Auditable;


    const INATIVO = "inativo";
    const ATIVO = "ativo";
    const BLOQUEADO = "bloqueado";


    const PESSOA_FISICA = 1;
    const PESSOA_JURIDICA = 2;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'cpf_cnpj', 'password', 'pagina_user', 'email_alternativo', 'sexo', 'imagem', 'chk_newsletter', 'status', 'remember_token', 'cep', 'data_nascimento'
    ];

    public function findForPassport($username)
    {
        $return = $this->where('email', $username)->first();
        if ($return->status != self::ATIVO) {
            return;
        }
        return $return;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['deleted_at'];

    public static $_SEXO = [
        1 => 'masculino',
        2 => 'feminino'
    ];


    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function setCpfCnpjAttribute($value)
    {
        if ($value)
            $this->attributes['cpf_cnpj'] = preg_replace('([\.\-\/])', '', $value);
    }
    public function setCepAttribute($value)
    {
        if ($value)
            $this->attributes['cep'] = preg_replace('([\.\-\/])', '', $value);
    }
    public function setEmailAttribute($value)
    {
        if ($value)
            $this->attributes['email'] = strtolower($value);
    }

    public function setEmailAlternativoAttribute($value)
    {
        if ($value)
            $this->attributes['email_alternativo'] = strtolower($value);
    }

    public function getCpfCnpjAttribute($value)
    {
        if(is_null($value))
            return null;

        if(validar_cnpj($value)) {
            return mask($this->attributes['cpf_cnpj'], '##.###.###/####-##');
        }

        return mask($this->attributes['cpf_cnpj'], '###.###.###-##');
    }

    public function tipo(){
        return (validar_cnpj($this->attributes['cpf_cnpj']))?self::PESSOA_JURIDICA:self::PESSOA_FISICA;
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user', 'user_id', 'permission_id');
    }

    public function endereco()
    {
        return $this->morphOne(Endereco::class, 'enderecotable');
    }

    public function telefones()
    {
        return $this->morphMany(Telefone::class, 'telefonetable');
    }

    public function telefone()
    {
        return $this->morphOne(Telefone::class, 'telefonetable')->where('principal', '=', true);
    }

    public function return_roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

}
