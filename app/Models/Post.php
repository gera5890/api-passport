<?php

namespace App\Models;

use App\Traits\ApiTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    use HasFactory, ApiTrait;

    protected $fillable = [
        'name',
        'slug',
        'extract',
        'body',
        'status',
        'category_id',
        'user_id'
    ];

    //Relacion uno a muchos inversa
    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function category():BelongsTo{
        return $this->belongsTo(Category::class);
    }

    //Relacion muchos a muchos
    public function tags():BelongsToMany{
        return $this->belongsToMany(Tag::class);
    }

    //Relacion morph uno a muchos
    public function images():MorphMany{
        return $this->morphMany(Image::class, 'imageable');
    }
}
