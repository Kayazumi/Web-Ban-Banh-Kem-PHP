<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có quyền thực hiện yêu cầu này không.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Định nghĩa các quy tắc kiểm tra (Validation Rules).
     */
    public function rules(): array
    {
        return [
            // Họ tên bắt buộc nhập, tối đa 100 ký tự
            'full_name' => 'required|string|max:100',

            // Số điện thoại không bắt buộc, tối đa 20 ký tự
            'phone'     => 'nullable|string|max:20',

            // Địa chỉ không bắt buộc, tối đa 255 ký tự
            'address'   => 'nullable|string|max:255',
        ];
    }

    /**
     * Tùy chỉnh thông báo lỗi bằng tiếng Việt.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Họ và tên không được để trống.',
            'full_name.max'      => 'Họ và tên không được vượt quá 100 ký tự.',
            'phone.max'         => 'Số điện thoại không được vượt quá 20 ký tự.',
            'address.max'       => 'Địa chỉ quá dài (tối đa 255 ký tự).',
        ];
    }
}
