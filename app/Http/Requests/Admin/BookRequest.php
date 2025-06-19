<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'publisher_id' => 'required|exists:publishers,id',
            'rack_id' => 'required|exists:racks,id',
            'publication_date' => 'nullable|date',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'language' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'total_copies' => 'required|integer|min:1',
            'available_copies' => 'required|integer',
            'file' => 'nullable|mimes:pdf|max:2048',
        ] + ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    protected function store(): array
    {
        return [
            'slug' => 'nullable|string|max:255|unique:books,slug',
            'isbn' => 'required|string|max:20|unique:books,isbn',
        ];
    }
    protected function update(): array
    {
        return [
            'slug' => 'nullable|string|max:255|unique:books,slug,' . decrypt($this->route('book')),
            'isbn' => 'required|string|max:20|unique:books,isbn,' . decrypt($this->route('book')),
        ];
    }
}
