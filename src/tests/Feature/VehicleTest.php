<?php

use App\Models\User;
use App\Models\Vehicle;

test('customer can add a vehicle', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $response = $this->actingAs($user)
        ->post(route('vehicle.store'), [
            'brand' => 'Honda',
            'model' => 'Beat',
            'plate_number' => 'B 1234 ABC',
            'year' => 2022,
            'color' => 'Black',
            'init_km' => 12000,
        ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertDatabaseHas('vehicles', [
        'user_id' => $user->id,
        'brand' => 'Honda',
        'model' => 'Beat',
        'plate_number' => 'B 1234 ABC',
    ]);
});

test('customer can delete their own vehicle', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $vehicle = Vehicle::create([
        'user_id' => $user->id,
        'brand' => 'Honda',
        'model' => 'Scoopy',
        'plate_number' => 'B 5678 DEF',
        'qr_code' => 'TEST-QR',
    ]);

    $response = $this->actingAs($user)
        ->delete(route('vehicle.destroy', $vehicle->id));

    $response->assertRedirect(route('dashboard'));
    $this->assertDatabaseMissing('vehicles', [
        'id' => $vehicle->id,
    ]);
});

test('customer cannot delete someone else\'s vehicle', function () {
    $user1 = User::factory()->create();
    $user1->assignRole('user');

    $user2 = User::factory()->create();
    $user2->assignRole('user');

    $vehicle = Vehicle::create([
        'user_id' => $user2->id,
        'brand' => 'Yamaha',
        'model' => 'NMAX',
        'plate_number' => 'B 9999 XYZ',
        'qr_code' => 'TEST-QR-2',
    ]);

    $response = $this->actingAs($user1)
        ->delete(route('vehicle.destroy', $vehicle->id));

    $response->assertStatus(404);
    $this->assertDatabaseHas('vehicles', [
        'id' => $vehicle->id,
    ]);
});

test('non-customer cannot add a vehicle', function () {
    $admin = User::factory()->create();
    $admin->assignRole('super_admin');

    $response = $this->actingAs($admin)
        ->post(route('vehicle.store'), [
            'brand' => 'Honda',
            'model' => 'Beat',
            'plate_number' => 'B 1234 ABC',
        ]);

    // Admin should get redirected because of RedirectIfNotCustomer middleware
    $response->assertRedirect('/super-admin');
});
