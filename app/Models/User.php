<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = "user";
    protected $fillable = [
        'name',
        'user_uid',
        'username',
        'refresh_token',
        'password',
        'password_hash',
        'email',
        'gender',
        'birthday',
        'address',
        'phone_number',
        'email_send',
        'email_reply',
        'email_footer',
        'cccd',
        'avatar',
        'created_by',
        'active',
        'is_root',
        'role_id',
        'department_id',
        'position_id',
        'mail_footer',
    ];

    protected $hidden = [
        'password',
        'refresh_token',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
