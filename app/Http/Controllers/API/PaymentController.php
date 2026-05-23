<?php

namespace App\Http\Controllers\API;

use App\Models\Subscription;
use App\Models\Payment;
use App\Models\Package;
use App\Services\iPaymuService;
use Illuminate\Http\Request;
use Auth;

class PaymentController extends BaseController
{
    protected $ipaymuService;

    public function __construct(iPaymuService $ipaymuService)
    {
        $this->ipaymuService = $ipaymuService;
    }

    public function startPayment(Request $request)
    {
        // Cek apakah user sudah memiliki subscription
        $subscription = Subscription::where('user_id', Auth::id())->first();
    
        // Jika sudah ada subscription, lanjutkan dengan memperbarui payment_id
        if ($subscription) {
            $amount = 99000; // Harga subscription
            $transactionId = uniqid(); // Buat transaction ID
    
            // Buat record pembayaran baru di database
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'transaction_id' => $transactionId,
                'amount' => $amount
            ]);
    
            // Panggil layanan iPaymu untuk request pembayaran
            $response = $this->ipaymuService->createPayment($amount, $transactionId);
            $responseData = $response; // Misalkan $response sudah berupa array
    // return $responseData;
            if ($responseData['Status'] == 200 && $responseData['Success']) {
                // Update subscription dengan payment_id baru
                $subscription->update([
                    'payment_id' => $payment->id,
                    'is_active' => false // Status tetap false sampai pembayaran dikonfirmasi
                ]);
    
                return $responseData;
            } else {
                // Tangani error jika pembayaran gagal
                return $this->sendError('Payment Error.', 'Pembayaran Gagal');
            }
        }
    
        // Jika user belum punya subscription, buat subscription baru
        $amount = 99000; // Harga subscription
        $transactionId = uniqid(); // Buat transaction ID
    
        // Buat record pembayaran baru di database
        $payment = Payment::create([
            'user_id' => Auth::id(),
            'transaction_id' => $transactionId,
            'amount' => $amount
        ]);
    
        // Panggil layanan iPaymu untuk request pembayaran
        $response = $this->ipaymuService->createPayment($amount, $transactionId);
        $responseData = $response; // Misalkan $response sudah berupa array
    
        if ($responseData['Status'] == 200 && $responseData['Success']) {
            // Buat subscription baru jika pembayaran berhasil
            Subscription::create([
                'user_id' => Auth::id(),
                'payment_id' => $payment->id,
                'is_active' => false // Status awal false sampai pembayaran dikonfirmasi
            ]);
    
            return $responseData;
        } else {
            // Tangani error jika ada
            return $this->sendError('Payment Error.', 'Pembayaran Gagal');
        }
    }
    
    public function startPayment_(Request $request)
    {
        // Cek apakah user sudah memiliki subscription
        $subscription = Subscription::where('user_id', Auth::id())->first();
        $package = Package::where('id', $request->package_id)->first();
    
        // Jika sudah ada subscription, lanjutkan dengan memperbarui payment_id
        if ($subscription) {
            $amount = $package->price; // Harga subscription
            $transactionId = uniqid(); // Buat transaction ID
    
            // Buat record pembayaran baru di database
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'transaction_id' => $transactionId,
                'package_id' => $request->package_id,
                'amount' => $amount
            ]);
    
            // Panggil layanan iPaymu untuk request pembayaran
            $response = $this->ipaymuService->createPayment($amount, $transactionId);
            $responseData = $response; // Misalkan $response sudah berupa array
    
            if ($responseData['Status'] == 200 && $responseData['Success']) {
                // Update subscription dengan payment_id baru
                $subscription->update([
                    'payment_id' => $payment->id,
                    'is_active' => false // Status tetap false sampai pembayaran dikonfirmasi
                ]);
    
                return $responseData;
            } else {
                // Tangani error jika pembayaran gagal
                return $this->sendError('Payment Error.', 'Pembayaran Gagal');
            }
        }
    
        // Jika user belum punya subscription, buat subscription baru
        $amount = 99000; // Harga subscription
        $transactionId = uniqid(); // Buat transaction ID
    
        // Buat record pembayaran baru di database
        $payment = Payment::create([
            'user_id' => Auth::id(),
            'transaction_id' => $transactionId,
            'package_id' => $request->package_id,
            'amount' => $amount
        ]);
    
        // Panggil layanan iPaymu untuk request pembayaran
        $response = $this->ipaymuService->createPayment($amount, $transactionId);
        $responseData = $response; // Misalkan $response sudah berupa array
    
        if ($responseData['Status'] == 200 && $responseData['Success']) {
            // Buat subscription baru jika pembayaran berhasil
            Subscription::create([
                'user_id' => Auth::id(),
                'payment_id' => $payment->id,
                'is_active' => false // Status awal false sampai pembayaran dikonfirmasi
            ]);
    
            return $responseData;
        } else {
            // Tangani error jika ada
            return $this->sendError('Payment Error.', 'Pembayaran Gagal');
        }
    }



    // Callback dari iPaymu
    public function paymentCallback(Request $request)
    {
        $transactionId = $request->get('reference_id');
        $status = $request->get('status'); // 'berhasil' atau 'gagal'
        $status_code = $request->get('status_code');
    
        // Cari pembayaran berdasarkan transaction_id
        $payment = Payment::where('transaction_id', $transactionId)->first();
        
        if ($payment) {
            // Simpan JSON request untuk logging
            $payment->json = json_encode($request->all());
    
            if ($status == 'berhasil') {
                // Jika pembayaran berhasil, perbarui status pembayaran dan catat waktu pembayaran
                $payment->status = $status_code;
                $payment->paid_at = now();
                $payment->save();
    
                // Update subscription terkait agar menjadi aktif (is_active = true)
                $subscription = Subscription::where('user_id', $payment->user_id)->first();
    
                if ($subscription) {
                    // Jika subscription sudah ada, update menjadi aktif
                    $subscription->update([
                        'is_active' => true,
                        'payment_id' => $payment->id
                    ]);
                } else {
                    // Jika belum ada subscription, buat yang baru
                    Subscription::create([
                        'user_id' => $payment->user_id,
                        'payment_id' => $payment->id,
                        'is_active' => true
                    ]);
                }
    
                // return redirect()->route('subscription.success'); // Jika ingin redirect
            } else {
                // Jika pembayaran gagal, perbarui status pembayaran dan reset waktu pembayaran
                $payment->status = $status_code;
                $payment->paid_at = NULL;
                $payment->save();
            }
        }
    }


    public function success(Request $request)
    {
        $returnValue = $request->query('return'); // Mengambil nilai dari query string
        // Atau
        // $returnValue = $request->input('return');

        return response()->json([
            'return' => $returnValue
        ]);
    }

    public function fail()
    {
        return view('payment.fail');
    }
}
