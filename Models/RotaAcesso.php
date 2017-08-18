<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class RotaAcesso extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes, Auditable;

    protected $fillable = [
        'parent_id',
        'text',
        'rota',
        'icon',
        'disabled',
        'is_menu',
        'ambiente',
        'prioridade',
    ];

    protected $dates = ['deleted_at'];

    public function roles(){
        return $this->belongsToMany(Role::class, 'rota_acesso_roles', 'rota_acesso_id', 'role_id');
    }

    public function child(){
        return $this->hasMany(RotaAcesso::class, 'parent_id');
    }

    public function pai(){
        return $this->belongsTo(RotaAcesso::class, 'parent_id');
    }

}
