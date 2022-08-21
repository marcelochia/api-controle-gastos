<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    
    protected $table = 'despesas';
    public $timestamps = false;
    protected $fillable = [
        'descricao',
        'valor',
        'data',
        'categoria_id'
    ];

    public function categoria()
    {
        return $this->hasone(Category::class, 'id', 'categoria_id');
    }
}
