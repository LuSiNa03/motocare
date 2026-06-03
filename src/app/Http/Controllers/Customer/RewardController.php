<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function index()
    {
        // Dummy data untuk rewards
        $rewards = [
            [
                'id' => 1,
                'name' => 'Voucher Diskon Servis Rp 20.000',
                'description' => 'Potongan harga langsung untuk servis reguler.',
                'points_required' => 500,
                'image' => 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
            ],
            [
                'id' => 2,
                'name' => 'Gratis Ganti Oli Mesin',
                'description' => 'Berlaku untuk semua tipe motor matic.',
                'points_required' => 2500,
                'image' => 'https://images.unsplash.com/photo-1638024227092-d6fcbb0cfdb3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
            ],
            [
                'id' => 3,
                'name' => 'Voucher Sparepart Rp 100.000',
                'description' => 'Dapat digunakan untuk pembelian sparepart di cabang.',
                'points_required' => 4000,
                'image' => 'https://images.unsplash.com/photo-1599839619722-39751411ea63?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
            ]
        ];

        return view('customer.rewards', compact('rewards'));
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'reward_id' => 'required|integer',
            'points_required' => 'required|integer',
            'reward_name' => 'required|string'
        ]);

        $user = Auth::user();

        if ($user->loyalty_points < $request->points_required) {
            return back()->with('error', 'MotoPoints Anda tidak mencukupi untuk menukar ' . $request->reward_name . '.');
        }

        // Kurangi poin
        $user->loyalty_points -= $request->points_required;
        $user->save();

        return redirect()->route('rewards.index')->with('success', 'Berhasil menukar poin dengan ' . $request->reward_name . '! Voucher akan dikirimkan ke email Anda.');
    }
}
