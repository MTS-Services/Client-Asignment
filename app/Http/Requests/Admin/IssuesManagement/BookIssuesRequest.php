<?php

namespace App\Http\Requests\Admin\IssuesManagement;

use Illuminate\Foundation\Http\FormRequest;

class BookIssuesRequest extends FormRequest
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
        'user_id'      => 'required|exists:users,id',
        'book_id'      => 'required|exists:books,id', // ✅ fixed typo
        'notes'        => 'nullable|string',
        'issue_date'   => 'required|date',
        'return_date'  => 'required|date',
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
          'returned_by' => 'required|exists:users,id',
        ];
    }
}
