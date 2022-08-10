<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categorias';
    public $timestamps = false;

    public function despesas()
    {
        return $this->hasMany(Expense::class, 'categoria_id', 'id');
    }
}
