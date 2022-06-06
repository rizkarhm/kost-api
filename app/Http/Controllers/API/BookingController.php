<?php

namespace App\Http\Controllers\API;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $kost_id = $request->input('booking_id');
        $status = $request->input('status');

        if ($id) {

            $booking = Booking::with(['kost', 'user'])->find($id);

            if ($booking) {
                return ResponseFormatter::success(
                    $booking,
                    'Data booking berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data booking tidak tersedia',
                    484
                );
            }
        }
        $booking = Booking::query();

        $booking = Booking::with(['kost', 'user'])->where('user_id', Auth::user()->id);


        if ($kost_id) {
            $booking->where('kost_id', $kost_id);
        }

        if ($status) {
            $booking->where('status', $status);
        }

        return ResponseFormatter::success(
            $booking->paginate($limit),
            'Data list booking berhasil diambil'
        );
    }

    public function update(Request $request, $id){
        $booking = Booking::findOrFailed($id);

        $booking->update($request->all());

        return ResponseFormatter::success($booking, 'Booking berhasil diperbarui');
    }

    public function checkout(Request $request){
        $request->validate([
            'kost_id' => 'required|exist:kost,id',
            'user_id' => 'required|exist:user,id',
            'start_date' => 'required',
            'end_date' => 'required',
            'total' => 'required',
            'status' => 'required'
        ]);

        $booking = Booking::create([
            'kost_id' => $request->kost_id,
            'user_id' => $request->user_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total' => $request->total,
            'status' => $request->status,
            'payment_url' => ''
        ]);

        //Config midtrans
        Config::$serverKey = config('services.midtrans.serverkey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        //Memanggil transaksi yg dibuat
        $booking = Booking::with(['kost', 'user'])->find($booking->id);

        //Create transaksi midtrans
        $midtrans = [
            'booking_details' => [
                'order_id' => $booking->id,
                'gross_amount' => (int) $booking->total,
            ],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' -> $booking->user->email,
            ],
            'enable_payments' => [
                'gopay',
                'bank_transfer'
            ],
            'vtweb' => []
        ]

        //Memanggil midtrans
        try {
            //Ambil halaman payment midtrans
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;
            $booking->payment_url = $paymentUrl;
            $booking->save();

            //Mengembalikan data ke API
            return ResponseFormatter::success($booking, 'Transaksi Berhasil');
        }
        catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 'Transaksi Gagal');
        }
    }
}
