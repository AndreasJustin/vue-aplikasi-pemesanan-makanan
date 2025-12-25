<?php

use App\Models\User;
use App\Models\Role;

test('waitress can create order', function () {
    $role = Role::firstOrCreate(['id' => 1], ['name' => 'waitress']);
    $user = User::factory()->create(['role_id' => 1]);

    $response = $this->actingAs($user)->postJson('/api/order', [
        'customer_name' => 'John Doe',
        'table_no' => 5,
    ]);

    $response->assertStatus(201);
});
