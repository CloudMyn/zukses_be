<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeviceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $device = $this->route('device');
        return $this->user()->tipe_user === 'ADMIN' || $this->user()->id === $device->id_user;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $deviceId = $this->route('device')->id;

        return [
            'device_id' => ['required', 'string', 'max:255', Rule::unique('perangkat_pengguna')->ignore($deviceId)],
            'device_type' => 'required|in:MOBILE,TABLET,DESKTOP,TV',
            'device_name' => 'required|string|max:255',
            'operating_system' => 'required|string|max:255',
            'app_version' => 'nullable|string|max:50',
            'push_token' => 'nullable|string|max:255',
            'adalah_device_terpercaya' => 'boolean',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'device_id.required' => 'ID perangkat wajib diisi.',
            'device_id.unique' => 'ID perangkat sudah terdaftar.',
            'device_type.required' => 'Tipe perangkat wajib diisi.',
            'device_name.required' => 'Nama perangkat wajib diisi.',
            'operating_system.required' => 'Sistem operasi wajib diisi.',
        ];
    }
}
