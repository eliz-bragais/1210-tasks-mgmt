<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubTaskRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'sub_task_title' => 'required|unique:tasks,title|max:100',
            'sub_task_content' => 'required',
            'sub_task_status' => 'required',
            'sub_task_file' => 'file|max:5000',

        ];
    }
}
