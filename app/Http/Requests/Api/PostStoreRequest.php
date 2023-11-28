<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */


    public function rules(): array
    {
        $user_id = auth()->user()->id;

        return [
            'name' => 'required|string|min:1|max:255',
            'slug' => 'required|string|min:1|max:255|unique:posts',
            'extract' => 'required|string|min:1',
            'body' => 'required|string|min:1',
            'category_id' => 'required|exists:categories,id',
            'user_id' => $user_id
        ];
    }
}
