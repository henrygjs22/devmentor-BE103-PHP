<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostEventRequest extends FormRequest
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
            'name' => 'required|string',
            'trigger_time' => 'required|date_format:Y-m-d H:i:s',
            'notify_channel_ids' => 'required|array|min:1',
            'notify_channel_ids.*' => 'integer',
            'messages' => 'required',
            'messages.zh_tw' => 'required|string',
            'messages.en_us' => 'required|string',
        ];
    }
}
