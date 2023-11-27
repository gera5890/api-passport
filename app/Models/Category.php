<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

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

    public function scopeIncluded(Builder $query){



        //revisamos si trae parametros el request, o si esta definida nuestra lista blanca
        if(empty($this->allowIncluded) || empty(request('included'))){
            return;
        }
        //convertimos los parametros a un array y los separamos por una coma
        $relations = explode(',', request('included'));
        //convertimos nuestro array a una collection para utilizar algunos metodos para colecciones
        $allowIncluded = collect($this->allowIncluded);


        //creamos un bucle para recorrer el arreglo de relaciones
        //hacemos una condicion, si allowIncluded no contiene el relationships
        //quita la relacion que no existe por su indice en el arreglo relations
        foreach ($relations as $key => $relationship) {
            if(!$allowIncluded->contains($relationship)){
                unset($relations[$key]);
            }
        }
        $query->with($relations);
    }

    public function scopeFilter(Builder $query){
        if(empty($this->allowFilter) || empty(request('filter'))){
            return;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $filter => $valor) {
            if($allowFilter->contains($filter)){
                $query->where($filter, 'LIKE' , '%'. $valor. '%');
            }
        }
    }

    public function scopeSort(Builder $query) {
        // Verifica si la lista blanca de campos permitidos para ordenar está vacía o si no se proporcionó un parámetro de ordenación
        if (empty($this->allowSort) || empty(request('sort'))) {
            return;
        }
    
        // Divide la cadena de ordenación en un array de campos
        $sortFields = explode(',', request('sort'));
        
        // Crea una colección a partir de la lista blanca de campos permitidos
        $allowSort = collect($this->allowSort);
    
        // Itera sobre los campos de ordenación especificados en la solicitud
        foreach ($sortFields as $sortField) {
            // Por defecto, la dirección de orden es ascendente
            $direction = 'asc';
    
            // Si el campo de orden comienza con '-', establece la dirección como descendente y elimina el '-'
            if (str_starts_with($sortField, '-')) {
                $direction = 'desc';
                //eliminamos el caracter -
                $sortField = ltrim($sortField, '-');
            }
    
            // Verifica si el campo de orden está permitido
            if ($allowSort->contains($sortField)) {
                // Aplica la ordenación a la consulta Eloquent
                $query->orderBy($sortField, $direction);
            }
        }
    }

    public function scopeGetAllOrPaginate(Builder $query){
        //Verificamos si existe la variable perPage en el metodo get
        if(request('perPage')){
            //si existe casteamos el string a int
            $perPage = intval(request('perPage'));
            //retornamos la consulta paginada
            return $query->paginate($perPage);
        }

        //sin no existe regresamos todos los datos
        return $query->get();
    }
    
}
