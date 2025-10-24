<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombres','apellidos','cedula','email','telefono','id_rol','password','estado'
    ];

    protected $hidden = ['password','remember_token'];

    public $timestamps = true;

    // AGREGAR ESTA RELACIÓN
    public function rol()
    {
        return $this->belongsTo(\App\Models\Rol::class, 'id_rol', 'id_rol');
    }
}