<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $table = 'receitas';
    public $timestamps = false;
    protected $fillable = [
        'descricao',
        'valor',
        'data'
    ];
}
