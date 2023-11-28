<?php

namespace App\Models;

use App\Traits\ApiTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    //Mandamos a llamar a nuestro trait para reutilizar nuestro codigo en todos los modelos
    use HasFactory, ApiTrait;

    protected $fillable = [
        'name',
        'slug',
        'status'
    ];

    protected $allowIncluded = ['posts', 'posts.user'];
    protected $allowFilter = ['id', 'name', 'slug'];
    protected $allowSort = ['id', 'name', 'created_at'];

    public function posts(): HasMany{
        return $this->hasMany(Post::class);
    }
    
}
