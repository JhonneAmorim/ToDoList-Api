<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TodoListRequest extends FormRequest
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
        $common = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|integer|min:0|max:5',
        ];

        if ($this->isMethod('post')) {
            return $common;
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|nullable|string',
                'due_date' => 'sometimes|nullable|date',
                'priority' => 'sometimes|nullable|integer|min:0|max:5',
                'is_completed' => 'sometimes|boolean',
            ];
        }

        return $common;
    }

    public function messages(): array
    {
        return [
            'title.required'    => 'O título da lista é obrigatório.',
            'title.max'         => 'O título não pode ter mais que 150 caracteres.',
            'description.string' => 'A descrição deve ser uma string.',
            'due_date.date'     => 'A data de vencimento deve ser um formato de data válido.',
            'priority.integer'  => 'A prioridade deve ser um número inteiro.',
            'priority.min'      => 'A prioridade mínima é 0.',
            'priority.max'      => 'A prioridade máxima é 5.',
            'is_completed.boolean' => 'O campo concluído deve ser verdadeiro ou falso.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->isMethod('post')) {
            $this->merge([
                'priority' => $this->input('priority', 0),
            ]);
        }
    }


    protected function failedValidation(Validator $validator)
    {
        $payload = [
            'status' => 'error',
            'errors' => $validator->errors(),
        ];

        throw new HttpResponseException(
            response()->json($payload, 422)
        );
    }
}
