<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    private ?Token $token;
    protected $primaryKey = "uuid";
    protected $keyType = "string";
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    public function getToken(): Token
    {
        if (!isset($this->token)) $this->createToken();
        return $this->token;
    }

    public function createToken(): Token
    {
        $token = Token::create([
            'user_id' => $this->uuid,
            'token' => Str::random(128)
        ]);
        $this->token = $token;
        return $token;
    }

    public function passwordCheck(string $password): bool
    {
        return password_verify($password, $this->password);
    }
}
