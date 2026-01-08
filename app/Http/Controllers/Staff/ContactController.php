<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    // Hiển thị trang danh sách liên hệ
    public function index()
    {
        return view('staff.contacts.index');
    }

    // API: Lấy danh sách liên hệ
    public function apiIndex()
    {
        $contacts = Contact::with(['customer', 'respondedBy'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($contact) {
                return [
                    'ContactID' => $contact->ContactID,
                    'customer_name' => $contact->customer->full_name ?? 'N/A',
                    'customer_email' => $contact->customer->Email ?? 'N/A',
                    'customer_phone' => $contact->customer->Phone ?? 'N/A',
                    'Subject' => $contact->subject,
                    'Message' => $contact->message,
                    'Status' => $contact->status,
                    'CreatedAt' => $contact->created_at->toDateTimeString(),
                    'RespondedAt' => $contact->responded_at ? $contact->responded_at->toDateTimeString() : null,
                    'staff_name' => $contact->respondedBy->full_name ?? null,
                ];
            });

        return response()->json(['contacts' => $contacts]);
    }

    // API: Lấy chi tiết một liên hệ
    public function apiShow($id)
    {
        $contact = Contact::with(['customer', 'respondedBy'])->findOrFail($id);

        return response()->json([
            'contact' => [
                'ContactID' => $contact->ContactID,
                'customer_name' => $contact->customer->full_name ?? 'N/A',
                'customer_email' => $contact->customer->Email ?? 'N/A',
                'customer_phone' => $contact->customer->Phone ?? 'N/A',
                'Subject' => $contact->subject,
                'Message' => $contact->message,
                'Status' => $contact->status,
                'CreatedAt' => $contact->created_at->toDateTimeString(),
                'RespondedAt' => $contact->responded_at ? $contact->responded_at->toDateTimeString() : null,
                'staff_name' => $contact->respondedBy->full_name ?? null,
            ]
        ]);
    }

    // API: Cập nhật trạng thái (đánh dấu đã phản hồi)
    public function apiUpdateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,responded'
        ]);

        $contact = Contact::findOrFail($id);
        $contact->status = $validated['status'];
        
        // Nếu chuyển sang responded, ghi nhận thời gian và người phản hồi
        if ($contact->status === 'responded') {
            $contact->responded_at = now();
            $contact->responded_by = Auth::id();
        } else {
            // Nếu chuyển về pending, xóa thông tin phản hồi
            $contact->responded_at = null;
            $contact->responded_by = null;
        }
        
        $contact->save();

        return response()->json([
            'success' => true, 
            'message' => 'Cập nhật trạng thái thành công',
            'contact' => [
                'Status' => $contact->status,
                'RespondedAt' => $contact->responded_at ? $contact->responded_at->toDateTimeString() : null,
                'staff_name' => $contact->respondedBy->full_name ?? null,
            ]
        ]);
    }
}