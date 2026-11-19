<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use InvalidArgumentException;

class AdminUserSeeder extends Seeder
{
    /**
     * Create or update the initial administrator from config/auth.php.
     */
    public function run(): void
    {
        $adminSettings = config('auth.initial_admin');

        if (blank($adminSettings['email']) || blank($adminSettings['password'])) {
            throw new InvalidArgumentException(
                'Set ADMIN_EMAIL and ADMIN_PASSWORD in your environment before seeding the administrator.'
            );
        }

        $administrator = User::updateOrCreate(
            ['email' => $adminSettings['email']],
            ['name' => $adminSettings['name'], 'password' => $adminSettings['password']],
        );

        // is_admin is not mass assignable, so it is set explicitly.
        $administrator->forceFill(['is_admin' => true])->save();
    }
}
