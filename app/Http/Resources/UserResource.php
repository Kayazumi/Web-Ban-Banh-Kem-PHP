<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Biến đổi dữ liệu từ Model thành mảng JSON.
     */
    public function toArray(Request $request): array
    {
        // Khớp các cột từ Database với các trường mà Frontend (JS) sử dụng
        return [
            'id'         => $this->UserID, // Khóa chính từ bảng users
            'username'   => $this->username,
            'email'      => $this->email,
            'full_name'  => $this->full_name,
            'phone'      => $this->phone,
            'address'    => $this->address,
            'role'       => $this->role,
            'status'     => $this->status,
            'avatar'     => $this->avatar,
            'last_login' => $this->last_login ? $this->last_login->format('d/m/Y H:i') : null, // Định dạng ngày tháng
        ];
    }
}