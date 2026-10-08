<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = array_key_exists((string) $request->query('status'), Order::STATUSES) ? $request->query('status') : null;

        $orders = Order::with(['user', 'items'])
            ->status($status)
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders', compact('orders', 'status'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate(['status' => ['required', Rule::in(array_keys(Order::STATUSES))]]);

        $order->status = $validated['status']; // status tidak mass-assignable
        $order->save();

        return back()->with('success', "Status {$order->code} diperbarui: {$order->status_label}.");
    }
}
