<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'parent_id',
        'user_id'
    ];

    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
}
