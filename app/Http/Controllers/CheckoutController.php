<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function start(Request $request)
    {
        $request->validate([
            'line_ids' => ['required', 'array', 'min:1'],
            'line_ids.*' => ['string'],
        ]);

        $cart = session()->get('cart', []);

        $lineIds = array_values(array_filter($request->input('line_ids', []), function ($lineId) use ($cart) {
            return isset($cart[$lineId]);
        }));

        if (empty($lineIds)) {
            return redirect()->route('cart.index')->with('success', 'Pilih minimal 1 item untuk checkout.');
        }

        session()->put('checkout_line_ids', $lineIds);

        return redirect()->route('checkout.show');
    }

    public function show()
    {
        $lineIds = session()->get('checkout_line_ids', []);
        $cart = session()->get('cart', []);

        if (empty($lineIds)) {
            return redirect()->route('cart.index');
        }

        $items = [];
        $productIds = [];

        foreach ($lineIds as $lineId) {
            if (!isset($cart[$lineId])) {
                continue;
            }

            $items[$lineId] = $cart[$lineId];
            $productIds[] = $cart[$lineId]['product_id'];
        }

        if (empty($items)) {
            session()->forget('checkout_line_ids');
            return redirect()->route('cart.index');
        }

        $products = Product::whereIn('id', array_values(array_unique($productIds)))->get()->keyBy('id');

        $total = 0;
        foreach ($items as $item) {
            $product = $products[$item['product_id']] ?? null;
            $unitPrice = (int) ($product->price ?? 0);
            $quantity = (int) ($item['quantity'] ?? 0);

            $copies = (int) data_get($item, 'options.copies', 1);
            if ($copies < 1) {
                $copies = 1;
            }

            $total += $unitPrice * $quantity * $copies;
        }

        return view('checkout.index', [
            'items' => $items,
            'products' => $products,
            'total' => $total,
        ]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_whatsapp' => ['required', 'string', 'max:30'],
            'delivery_method' => ['required', 'in:diambil,diantar'],
            'delivery_address' => ['nullable', 'string', 'max:2000'],
            'additional_note' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($request->delivery_method === 'diantar' && empty($request->delivery_address)) {
            return back()->withErrors([
                'delivery_address' => 'Alamat wajib diisi jika memilih diantar.',
            ])->withInput();
        }

        $lineIds = session()->get('checkout_line_ids', []);
        $cart = session()->get('cart', []);

        $selectedItems = [];
        foreach ($lineIds as $lineId) {
            if (isset($cart[$lineId])) {
                $selectedItems[$lineId] = $cart[$lineId];
            }
        }

        if (empty($selectedItems)) {
            session()->forget('checkout_line_ids');
            return redirect()->route('cart.index')->with('success', 'Item checkout tidak ditemukan.');
        }

        $checkoutId = (string) Str::uuid();

        foreach ($selectedItems as $lineId => $item) {
            $order = new Order();
            $order->checkout_id = $checkoutId;
            $order->product_id = $item['product_id'];
            $order->customer_name = $request->customer_name;
            $order->customer_whatsapp = $request->customer_whatsapp;
            $order->quantity = $item['quantity'];
            $order->note = $item['note'] ?? null;
            $order->additional_note = $request->additional_note;
            $order->delivery_method = $request->delivery_method;
            $order->delivery_address = $request->delivery_method === 'diantar' ? $request->delivery_address : null;
            $order->document_file = $item['document_file'] ?? null;
            $order->options = $item['options'] ?? [];
            $order->status = 'pending';
            $order->save();

            unset($cart[$lineId]);
        }

        session()->put('cart', $cart);
        session()->forget('checkout_line_ids');

        return redirect()->route('checkout.payment', ['checkoutId' => $checkoutId]);
    }

    public function payment(string $checkoutId)
    {
        $orders = Order::with('product')->where('checkout_id', $checkoutId)->latest()->get();

        if ($orders->isEmpty()) {
            abort(404);
        }

        $total = $orders->sum(fn (Order $order) => $order->totalPrice());

        return view('checkout.payment', [
            'checkoutId' => $checkoutId,
            'orders' => $orders,
            'total' => $total,
        ]);
    }

    public function paymentProofForm(string $checkoutId)
    {
        $orders = Order::with('product')->where('checkout_id', $checkoutId)->latest()->get();

        if ($orders->isEmpty()) {
            abort(404);
        }

        $paymentProof = $orders->first()->payment_proof;

        return view('checkout.payment-proof', [
            'checkoutId' => $checkoutId,
            'orders' => $orders,
            'paymentProof' => $paymentProof,
        ]);
    }

    public function uploadPaymentProof(Request $request, string $checkoutId)
    {
        $request->validate([
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $exists = Order::where('checkout_id', $checkoutId)->exists();
        if (!$exists) {
            abort(404);
        }

        $filePath = $this->storePublicUpload($request->file('payment_proof'), 'uploads/payment_proofs');

        Order::where('checkout_id', $checkoutId)->update([
            'payment_proof' => $filePath,
        ]);

        return redirect()
            ->route('checkout.paymentProofForm', ['checkoutId' => $checkoutId])
            ->with('success', 'Bukti pembayaran berhasil diupload.');
    }

    private function storePublicUpload(UploadedFile $file, string $relativeDir): string
    {
        $absoluteDir = public_path($relativeDir);

        if (!File::exists($absoluteDir)) {
            File::makeDirectory($absoluteDir, 0755, true);
        }

        $filename = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move($absoluteDir, $filename);

        return trim($relativeDir, '/') . '/' . $filename;
    }
}
