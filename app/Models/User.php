<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    // ---RELACIONES DEL SISTEMA DEL TALLER ---
    //Relacion: Herramientas que este usuario tiene o ha tenido prestadas.
    public function loans(): HasMany
    {
        return $this->hasMany(ToollLoan::class, 'borrower_id');
    }

    //Relacion: Prestamos que este usuario (siendo admin) ha autorizado.
    public function authorizedLoans(): HasMany
    {
        return $this->hasMany(ToollLoan::class, 'admin_id');
    }

    //Relación: Insumos o refacciones que el usuario ha solicitado/gastado

    public function consumptions(): HasMany
    {
        return $this->hasMany(consumptionLog::class, 'user_id');

    }

    //Metodos de Ayuda
    //Verifica si el usuario es Administrador
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

}
