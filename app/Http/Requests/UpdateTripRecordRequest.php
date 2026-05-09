<?php

namespace App\Http\Requests;

use App\Enums\RideType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTripRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $trip = $this->route()->parameter('trip');

        return auth()->check() && auth()->id() === $trip->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ride_type' => ['required', Rule::in(RideType::cases())],
            'started_at' => ['required', 'date'],
            'ended_at' => ['required', 'date', 'after:started_at'],
            'start_station_id' => ['required', 'integer', 'exists:stations,id'],
            'end_station_id' => ['required', 'integer', 'exists:stations,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'ride_type.required' => 'The ride type is required.',
            'ride_type.in' => 'The ride type must be one of '.implode(', ', array_map(fn ($rideType) => $rideType->value, RideType::cases())).'.',
            'started_at.required' => 'The start date/time is required.',
            'ended_at.required' => 'The end date/time is required.',
            'ended_at.after' => 'The end time must be after the start time.',
            'start_station_id.required' => 'The start station is required.',
            'start_station_id.exists' => 'The selected start station is invalid.',
            'end_station_id.required' => 'The end station is required.',
            'end_station_id.exists' => 'The selected end station is invalid.',
        ];
    }
}
