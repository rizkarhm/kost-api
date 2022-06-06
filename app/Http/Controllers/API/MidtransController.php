<?php

namespace App\Http\Controllers\API;

use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Booking;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {

        //Config midtrans
        Config::$serverKey = config('services.midtrans.serverkey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        //Buat instance midtrans notif
        $notification = new Notification();

        //Asign ke variable
        $status = $notification->transaction_status;
        $type = $notification->payment_type;
        $fraud = $notification->fraud_status;
        $order_id = $notification->order_id;

        //Cari transaksi berdasarkan id
        $booking = Booking::findOrFail($order_id);

        //Handle notif status midtrans
        if ($status = 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'chalenge') {
                    $booking->status = 'PENDING';
                } else {
                    $booking->status = 'SUCCESS';
                }
            }
        } else if ($status == 'settlement') {
            $booking->status = 'SUCCESS';
        } else if ($status == 'pending') {
            $booking->status = 'PENDING';
        } else if ($status == 'deny') {
            $booking->status = 'CANCELED';
        } else if ($status == 'expired') {
            $booking->status = 'CANCELED';
        } else if ($status == 'cancel') {
            $booking->status = 'CANCELED';
        }

        //Simpan transaksi
        $booking->save();
    }

    public function success()
    {
        return view('midtrans.success');
    }

    public function unfinish()
    {
        return view('midtrans.unfinish');
    }

    public function error()
    {
        return view('midtrans.error');
    }
}
