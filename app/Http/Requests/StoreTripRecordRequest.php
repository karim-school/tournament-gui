<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTripRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rideable_type' => ['required', Rule::in(['electric_bike', 'classic_bike'])],
            'started_at' => ['required', 'date'],
            'ended_at' => ['required', 'date', 'after:started_at'],
            'start_station_id' => ['required', 'integer', 'exists:stations,id'],
            'end_station_id' => ['required', 'integer', 'exists:stations,id'],
            'member_casual' => ['required', Rule::in(['member', 'casual'])],
        ];
    }

    public function messages(): array
    {
        return [
            'rideable_type.required' => 'The ride type is required.',
            'rideable_type.in' => 'The ride type must be electric_bike or classic_bike.',
            'started_at.required' => 'The start date/time is required.',
            'ended_at.required' => 'The end date/time is required.',
            'ended_at.after' => 'The end time must be after the start time.',
            'start_station_id.required' => 'The start station is required.',
            'start_station_id.exists' => 'The selected start station is invalid.',
            'end_station_id.required' => 'The end station is required.',
            'end_station_id.exists' => 'The selected end station is invalid.',
            'member_casual.required' => 'The rider type is required.',
            'member_casual.in' => 'The rider type must be member or casual.',
        ];
    }
}
