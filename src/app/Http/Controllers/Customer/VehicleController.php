<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VehicleController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand'          => 'required|string|max:100',
            'model'          => 'required|string|max:100',
            'plate_number'   => 'required|string|max:20|unique:vehicles,plate_number',
            'year'           => 'nullable|integer|min:1990|max:' . (date('Y') + 1),
            'color'          => 'nullable|string|max:50',
            'engine_number'  => 'nullable|string|max:100',
            'chassis_number' => 'nullable|string|max:100',
            'init_km'        => 'nullable|integer|min:0',
        ]);

        $qrCode = 'MOTOCARE-' . strtoupper($validated['plate_number']) . '-' . time();

        Vehicle::create([
            'user_id'        => Auth::id(),
            'brand'          => $validated['brand'],
            'model'          => $validated['model'],
            'plate_number'   => strtoupper($validated['plate_number']),
            'year'           => $validated['year'] ?? null,
            'color'          => $validated['color'] ?? null,
            'engine_number'  => $validated['engine_number'] ?? null,
            'chassis_number' => $validated['chassis_number'] ?? null,
            'init_km'        => $validated['init_km'] ?? 0,
            'qr_code'        => $qrCode,
        ]);

        return redirect()->route('dashboard')->with('success', 'Kendaraan berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $vehicle->delete();

        return redirect()->route('dashboard')->with('success', 'Kendaraan berhasil dihapus.');
    }
}
