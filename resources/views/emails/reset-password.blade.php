<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #ff6b6b; padding: 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px;">La Cuisine Ngọt</h1>
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #333; margin-top: 0; margin-bottom: 20px;">Đặt lại mật khẩu</h2>
                            
                            <p style="color: #666; line-height: 1.6; margin-bottom: 20px;">
                                Xin chào,
                            </p>
                            
                            <p style="color: #666; line-height: 1.6; margin-bottom: 20px;">
                                Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.
                            </p>
                            
                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ $resetLink }}" 
                                   style="display: inline-block; padding: 15px 40px; background-color: #ff6b6b; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;">
                                    Đặt lại mật khẩu
                                </a>
                            </p>
                            
                            <p style="color: #666; line-height: 1.6; margin-bottom: 20px;">
                                Link này sẽ hết hạn sau 24 giờ.
                            </p>
                            
                            <p style="color: #666; line-height: 1.6; margin-bottom: 20px;">
                                Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.
                            </p>
                            
                            <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
                            
                            <p style="color: #999; font-size: 14px; line-height: 1.6;">
                                Nếu bạn gặp vấn đề khi click vào nút "Đặt lại mật khẩu", copy và paste URL dưới đây vào trình duyệt:
                            </p>
                            <p style="color: #666; font-size: 12px; word-break: break-all;">
                                {{ $resetLink }}
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f8f8; padding: 20px 30px; text-align: center;">
                            <p style="color: #999; font-size: 14px; margin: 0;">
                                © {{ date('Y') }} La Cuisine Ngọt. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>