<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\NotificationUser;
use Illuminate\Support\Facades\Auth;
// class NotificationController extends Controller
// {
// public function index()
// {
//     if (Auth::guard('giang_viens')->check()) {
//         $user = Auth::guard('giang_viens')->user();

//         switch ($user->chucVuObj->maChucVu) {
//             case 'GV':
//                 $notifications = Notification::where('loai', 'lich')
//                     ->where('doiTuong', 'giang_vien')
//                     ->latest()->get();
//                 break;

//             case 'TBM':
//             case 'TK':
//                 $notifications = Notification::whereIn('loai', ['xac_nhan_phieu', 'xac_nhan_bien_ban'])
//                     ->latest()->get();
//                 break;

//             default:
//                 $notifications = collect();
//                 break;
//         }
//     } elseif (Auth::guard('nhan_vien_p_d_b_c_ls')->check()) {
//         $notifications = Notification::whereIn('loai', ['lich','bien_ban', 'phieu_dang_ky'])
//         ->where('doiTuong', 'nhan_vien')    
//         ->latest()->get();
//     } else {
//         $notifications = collect();
//     }


//     // return response()->json($notifications);
//     return response()->json(
//     $notifications->map(function ($item) {
//         return [
//             'id' => $item->id,
//             'noiDung' => $item->noiDung,
//             'daDoc' => $item->daDoc,
//             'link' => $item->link ?? '#',
//             'created_at' => $item->created_at->format('H:i d/m/Y'), // thời gian định dạng đẹp
//         ];
//     })
// );
// }

// public function markAllAsRead()
// {
//     if (auth()->guard('giang_viens')->check()) {
//         $user = auth()->guard('giang_viens')->user();

//         switch ($user->chucVuObj->maChucVu) {
//             case 'GV':
//                 Notification::where('loai', 'lich')
//                     ->where('doiTuong', 'giang_vien')
//                     ->update(['daDoc' => true]);
//                 break;

//             case 'TBM':
//             case 'TK':
//                 Notification::whereIn('loai', ['xac_nhan_phieu', 'xac_nhan_bien_ban'])
//                     ->update(['daDoc' => true]);
//                 break;
//         }
//     } elseif (auth()->guard('nhan_vien_p_d_b_c_ls')->check()) {
//         Notification::whereIn('loai', ['lich', 'bien_ban', 'phieu_dang_ky'])
//             ->where('doiTuong', 'nhan_vien')
//             ->update(['daDoc' => true]);
//     }

//     return response()->json(['message' => 'Đã đánh dấu đã đọc']);
// }

// public function markAsRead($id)
// {
//     $notification = Notification::find($id);
//     if ($notification) {
//         $notification->update(['daDoc' => true]);
//     }

//     return response()->json(['message' => 'Đã đánh dấu đã đọc thông báo']);
// }


// public function delete(Request $request)
// {
//     Notification::where('id', $request->id)->delete();
//     return response()->json(['message' => 'Đã xóa']);
// }


// }


class NotificationController extends Controller
{
    public function index()
    {
        $guard = $this->getCurrentGuard();
        $user = Auth::guard($guard)->user();
          if ($guard === 'giang_viens') {
            $userId = $user->maGiangVien;
        } elseif ($guard === 'nhan_vien_p_d_b_c_ls') {
            $userId = $user->maNV;
        } else {
            abort(403, 'Không xác định user_id');
        }
        $notifications = NotificationUser::with('notification')
            ->where('user_id', $userId)
            ->where('guard_name', $guard)
            ->latest()
            ->get();
        return response()->json(
            $notifications->map(function ($item) {
                return [
                    'id' => $item->id, // ID của NotificationUser
                    'noiDung' => $item->notification->noiDung,
                    'daDoc' => $item->daDoc,
                    'link' => $item->notification->link ?? '#',
                    'created_at' => $item->created_at->format('H:i d/m/Y'),
                ];
            })
        );
    }

   public function markAllAsRead()
    {
        $guard = $this->getCurrentGuard();
        $user = Auth::guard($guard)->user();

        $userId = $guard === 'giang_viens' ? $user->maGiangVien : $user->maNV;

        NotificationUser::where('user_id', $userId)
            ->where('guard_name', $guard)
            ->update(['daDoc' => true]);

        return response()->json(['message' => 'Đã đánh dấu đã đọc']);
    }


    public function markAsRead($id)
    {
        $guard = $this->getCurrentGuard();
        $user = Auth::guard($guard)->user();

        $userId = $guard === 'giang_viens' ? $user->maGiangVien : $user->maNV;

        $notificationUser = NotificationUser::where('id', $id)
            ->where('user_id', $userId)
            ->where('guard_name', $guard)
            ->first();

        if ($notificationUser) {
            $notificationUser->update(['daDoc' => true]);
        }

        return response()->json(['message' => 'Đã đánh dấu đã đọc thông báo']);
    }


   public function delete(Request $request)
    {
        $guard = $this->getCurrentGuard();
        $user = Auth::guard($guard)->user();

        $userId = $guard === 'giang_viens' ? $user->maGiangVien : $user->maNV;

        NotificationUser::where('id', $request->id)
            ->where('user_id', $userId)
            ->where('guard_name', $guard)
            ->delete();

        return response()->json(['message' => 'Đã xóa']);
    }


    private function getCurrentGuard()
    {
        if (Auth::guard('giang_viens')->check()) {
            return 'giang_viens';
        } elseif (Auth::guard('nhan_vien_p_d_b_c_ls')->check()) {
            return 'nhan_vien_p_d_b_c_ls';
        }

        abort(403, 'Không xác định guard hiện tại');
    }
}
