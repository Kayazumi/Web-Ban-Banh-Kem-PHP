<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Cho phép nhân viên đã đăng nhập thực hiện yêu cầu.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Các quy tắc kiểm tra cho việc đổi mật khẩu.
     */
    public function rules(): array
    {
        return [
            // Mật khẩu cũ: bắt buộc phải nhập
            'oldPassword' => 'required|string',
            
            // Mật khẩu mới: bắt buộc, tối thiểu 6 ký tự và phải khớp với ô xác nhận
            'newPassword' => 'required|string|min:6|confirmed',
        ];
    }

    /**
     * Thông báo lỗi bằng tiếng Việt.
     */
    public function messages(): array
    {
        return [
            'oldPassword.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'newPassword.required' => 'Vui lòng nhập mật khẩu mới.',
            'newPassword.min'      => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'newPassword.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ];
    }
}