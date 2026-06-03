<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\ServicePackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $vehicles = $user->vehicles;
        $branches = Branch::all();
        $packages = ServicePackage::all();

        return view('customer.booking', compact('vehicles', 'branches', 'packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'branch_id' => 'required|exists:branches,id',
            'service_package_id' => 'nullable|exists:service_packages,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'notes' => 'nullable|string'
        ]);

        // Generate nomor antrian sederhana
        $branch = Branch::find($request->branch_id);
        $queueNumber = substr($branch->name, 0, 1) . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

        Booking::create([
            'user_id' => Auth::id(),
            'vehicle_id' => $request->vehicle_id,
            'branch_id' => $request->branch_id,
            'service_package_id' => $request->service_package_id,
            'date' => $request->date,
            'time' => $request->time,
            'status' => 'menunggu',
            'queue_number' => $queueNumber,
            'notes' => $request->notes,
        ]);

        // Bisa menggunakan session flash message
        return redirect()->route('dashboard')->with('success', 'Booking berhasil dibuat! Nomor antrian Anda: ' . $queueNumber);
    }
}
