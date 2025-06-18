<?php

namespace App\Http\Requests\Admin\IssuesManagement;

use App\Models\BookIssues;
use Illuminate\Foundation\Http\FormRequest;

class BookLostReuest extends FormRequest
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
            'returned_by' => 'required|exists:users,id',
            'fine_amount' => 'sometimes|numeric|min:0|max:999999.99',
            'fine_status' => 'sometimes|string|in:' . implode(',', array_keys(BookIssues::fineStatusList())),
            'notes' => 'nullable|string',
        ] + ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    protected function store(): array
    {
        return [
            //
        ];
    }
    protected function update(): array
    {
        return [
            //
        ];
    }
}
