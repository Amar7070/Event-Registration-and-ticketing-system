<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $roles = ['admin', 'organizer', 'attendee'];
        foreach ($roles as $role) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => $role]);
        }

        // Create permissions
        $permissions = [
            'manage users', 'approve organizers', 'manage categories',
            'view platform analytics', 'handle disputes',
            'create events', 'edit events', 'delete events', 'publish events',
            'manage attendees', 'scan qr codes', 'view organizer analytics',
            'create coupons', 'send announcements', 'request payouts',
            'book tickets', 'cancel tickets', 'download tickets',
            'join waitlist', 'write reviews', 'request refunds',
        ];
        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        \Spatie\Permission\Models\Role::findByName('admin')->givePermissionTo(\Spatie\Permission\Models\Permission::all());
        \Spatie\Permission\Models\Role::findByName('organizer')->givePermissionTo([
            'create events', 'edit events', 'delete events', 'publish events',
            'manage attendees', 'scan qr codes', 'view organizer analytics',
            'create coupons', 'send announcements', 'request payouts',
        ]);
        \Spatie\Permission\Models\Role::findByName('attendee')->givePermissionTo([
            'book tickets', 'cancel tickets', 'download tickets',
            'join waitlist', 'write reviews', 'request refunds',
        ]);
    }
}
