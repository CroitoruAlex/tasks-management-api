<?php

namespace App\Http\Requests;

use App\Rules\ValidTaskAssignee;

class StoreTaskRequest extends BaseApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'manager';
    }

    /**
     * Validation rules for creating a task.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:pending,in-progress,done',
            'due_date' => 'nullable|date|after_or_equal:today',
            'assigned_to' => ['required', 'integer', new ValidTaskAssignee()],
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'title.required' => 'A task title is required.',
            'due_date.after_or_equal' => 'The due date must be today or later.',
            'assigned_to.required' => 'You must assign this task to a user.',
        ];
    }
}
