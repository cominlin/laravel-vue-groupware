<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
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
            'schedule.schedule_category_id' => 'required|exists:schedule_categories,id',
            'schedule.title' => 'required|string|max:100',
            'schedule.type' => 'required|integer|between:0,2',
            'schedule.from' => 'nullable|date',
            'schedule.to' => 'nullable|date',
            'schedule.from_date' => 'nullable|date',
            'schedule.to_date' => 'nullable|date',
            'schedule.memo' => 'nullable|string|max:10000',
            'edit_type' => 'nullable|integer|between:0,2',
            'selected_date' => 'nullable|date',
            'timezone' => 'required|string|max:50',
            'users' => 'array',
            'users.*' => 'required|exists:users,id',
            'files' => 'array|max:5',
            'files.*' => 'file|max:51200',
            'existed_files' => 'array|max:5',
            'master' => 'required|array',
        ];
    }
}
