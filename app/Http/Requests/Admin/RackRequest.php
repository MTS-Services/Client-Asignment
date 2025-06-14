<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RackRequest extends FormRequest
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
            'location'=>'required|string|min:3',
            'description'=>'nullable|string|max:255',
            'capacity'=>'required|integer|min:1',
        ] + ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    protected function store(): array
    {
        return [
            'rack_number' => 'required|string|unique:racks,rack_number',
        ];
    }
    protected function update(): array
    {
        return [
            'rack_number' => 'required|string|unique:racks,rack_number,' . decrypt($this->route('rack')) ,
        ];
    }
}
