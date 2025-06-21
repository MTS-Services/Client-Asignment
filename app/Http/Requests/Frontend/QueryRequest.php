<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class QueryRequest extends FormRequest
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
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email',
            'contact' => 'required|string|max:20|regex:/^[0-9+\-\s]+$/',
            'address' => 'required|string|min:3|max:500',
            'message' => 'nullable|string|min:3|max:1000',
        ];
    }


    public function messages()
    {
        return [
            'name.regex' => 'Name can only contain letters and spaces.',
            'contact.regex' => 'Contact number format is invalid.',
        ];
    }

    // Sanitize input data
    protected function prepareForValidation()
    {
        $this->merge([
            'name' => $this->sanitizeString($this->name),
            'email' => $this->sanitizeEmail($this->email),
            'contact' => $this->sanitizeString($this->contact),
            'address' => $this->sanitizeString($this->address),
            'message' => $this->sanitizeString($this->message),
        ]);
    }

    private function sanitizeString($input)
    {
        if (!$input) return $input;

        // Remove HTML tags and encode special characters
        $sanitized = strip_tags($input);
        $sanitized = htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');
        return trim($sanitized);
    }



    private function sanitizeEmail($email)
    {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }
}
