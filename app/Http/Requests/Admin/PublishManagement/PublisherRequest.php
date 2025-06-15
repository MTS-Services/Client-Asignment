<?php

namespace App\Http\Requests\Admin\PublishManagement;

use Illuminate\Foundation\Http\FormRequest;

class PublisherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
             return [
            'address'    => 'nullable|string',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:255',
            'website'    => 'nullable|url|max:255',
        ] + ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    protected function store(): array
    {
        return [
            'name' => 'required|unique:publishers,name',
            'slug' => 'required|unique:publishers,slug',
        ];
    }
    protected function update(): array
    {
        return [
            'name' => 'required|unique:publishers,name,' . decrypt($this->route('publisher')),
            'slug' => 'required|unique:publishers,slug,' . decrypt($this->route('publisher')),
        ];
    }
}
