<?php 
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VNPayController extends Controller
{
    private $vnp_TmnCode = 'HAJJKVKM';
    private $vnp_HashSecret = 'NQJNOXBODGJE9A3IPIJLI5J69ZIT1H68';
    private $vnp_Url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';

    public function createPaymentLink(Request $request)
    {
        $orderData = $request->all();
        
        $order_id = $orderData['order_id'];
        $amount = $orderData['totalAmount'];

        $vnp_Params = [
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $this->vnp_TmnCode,
            'vnp_Amount' => $amount * 100,
            'vnp_Command' => 'pay',
            'vnp_CurrCode' => 'VND',
            'vnp_OrderInfo' => 'Thanh toán đơn hàng ' . $order_id,
            'vnp_OrderType' => 'other',
            'vnp_ReturnUrl' => route('vnpay.return'),
            'vnp_TxnRef' => $order_id,
            'vnp_Locale' => 'vn',
            'vnp_CreateDate' => now()->format('YmdHis'),
            'vnp_IpAddr' => $request->ip(),
        ];

        ksort($vnp_Params);
        $query = http_build_query($vnp_Params);
        $secureHash = hash_hmac('sha512', $query, $this->vnp_HashSecret);
        $vnp_Params['vnp_SecureHash'] = $secureHash;
        $paymentUrl = $this->vnp_Url . '?' . http_build_query($vnp_Params);
        return redirect($paymentUrl);
    }

    public function callback(Request $request)
    {
        $vnp_Params = $request->all();
        $vnp_SecureHash = $vnp_Params['vnp_SecureHash'];
        unset($vnp_Params['vnp_SecureHash']);

        ksort($vnp_Params);
        $calculatedHash = hash_hmac('sha512', urldecode(http_build_query($vnp_Params)), $this->vnp_HashSecret);

        if ($calculatedHash !== $vnp_SecureHash) {
            Log::error('Invalid secure hash');
            return response()->json(['error' => 'Dữ liệu không hợp lệ'], 400);
        }

        $vnp_ResponseCode = $vnp_Params['vnp_ResponseCode'];
        $vnp_TxnRef = $vnp_Params['vnp_TxnRef'];
        $payment = Order::where('transaction_id', $vnp_TxnRef)->first();

        if (!$payment) {
            Log::error('Payment not found for transaction ID: ' . $vnp_TxnRef);
            return response()->json(['error' => 'Giao dịch không tồn tại'], 404);
        }

        if ($vnp_ResponseCode == '00') {
            $payment->update([
                'transaction_status' => 'success',
                'vnp_response_code' => $vnp_ResponseCode,
                'bank_code' => $vnp_Params['vnp_BankCode'],
            ]);
            Log::info('Payment success for transaction ID: ' . $vnp_TxnRef);
            return response()->json(['message' => 'Thanh toán thành công']);
        } else {
            $payment->update([
                'transaction_status' => 'failed',
                'vnp_response_code' => $vnp_ResponseCode,
            ]);
            Log::info('Payment failed for transaction ID: ' . $vnp_TxnRef);
            return response()->json(['error' => 'Thanh toán thất bại'], 400);
        }
    }

    public function return(Request $request)
    {
        $vnp_Params = $request->all();
        $vnp_SecureHash = $vnp_Params['vnp_SecureHash'];
        unset($vnp_Params['vnp_SecureHash']);
        ksort($vnp_Params);

        $query = urldecode(http_build_query($vnp_Params));
        $calculatedHash = hash_hmac('sha512', $query, $this->vnp_HashSecret);

        if ($calculatedHash !== $vnp_SecureHash) {
            Log::error('Dữ liệu VNPay không hợp lệ', ['params' => $vnp_Params]);
            return redirect()->route('page.home')->withErrors('Dữ liệu từ VNPay không hợp lệ.');
        }

        $vnp_ResponseCode = $vnp_Params['vnp_ResponseCode'];
        if ($vnp_ResponseCode == '00') {
            DB::table('payments')->where('transaction_id', $vnp_Params['vnp_TxnRef'])->update([
                'transaction_status' => 'success',
                'vnp_response_code' => $vnp_ResponseCode,
                'bank_code' => $vnp_Params['vnp_BankCode'] ?? null,
            ]);
            return redirect()->route('cart')->with('message', 'Thanh toán thành công');
        } else {
            DB::table('payments')->where('transaction_id', $vnp_Params['vnp_TxnRef'])->update([
                'transaction_status' => 'failed',
                'vnp_response_code' => $vnp_ResponseCode,
            ]);
            return redirect()->route('page.home')->withErrors('Thanh toán thất bại.');
        }
    }

    private function generateSecureHash($params)
    {
        ksort($params);
        $query = urldecode(http_build_query($params));
        return hash_hmac('sha512', $query, $this->vnp_HashSecret);
    }
}
?>