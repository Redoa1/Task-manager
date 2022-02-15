<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'due_date' => 'required|date',
            'hour' => 'required|integer|between:0,23',
            'minute' => 'required|integer|between:0,59',
            'type' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required',
            'title.max' => 'Title must be less than 255 characters',
            'due_date.required' => 'Due date is required',
            'due_date.date' => 'Due date must be a valid date',
            'hour.required' => 'Hour is required',
            'hour.integer' => 'Hour must be an integer',
            'hour.between' => 'Hour must be between 0 and 23',
            'minute.required' => 'Minute is required',
            'minute.integer' => 'Minute must be an integer',
            'minute.between' => 'Minute must be between 0 and 59',
            'type.required' => 'Type is required',
            'type.integer' => 'Type must be an integer',
        ];
    }
}
