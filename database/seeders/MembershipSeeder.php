<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        $roles = collect(['admin', 'editor', 'member'])->mapWithKeys(function (string $role) {
            return [$role => Role::firstOrCreate(['name' => $role, 'guard_name' => 'web'])];
        });

        $plans = collect([
            ['name' => 'Free', 'slug' => 'free', 'description' => 'Free forever plan', 'monthly_price' => null, 'yearly_price' => null, 'limits' => ['max_projects' => 3, 'max_notes' => 50]],
            ['name' => 'Pro', 'slug' => 'pro', 'description' => 'For power users', 'monthly_price' => 9.99, 'yearly_price' => 99.00, 'limits' => ['max_projects' => 20, 'max_notes' => 500]],
            ['name' => 'Business', 'slug' => 'business', 'description' => 'Team collaboration suite', 'monthly_price' => 29.99, 'yearly_price' => 299.00, 'limits' => ['max_projects' => 100, 'max_notes' => 2000]],
        ])->map(function (array $data) {
            return SubscriptionPlan::updateOrCreate(['slug' => $data['slug']], $data);
        })->keyBy('slug');

        $admin = User::updateOrCreate(
            ['email' => 'maliarkun@arkun.net'],
            [
                'name' => 'Mali Arkun',
                'password' => Hash::make('Sifre123!'),
                'email_verified_at' => now(),
                'username' => 'maliarkun',
            ]
        );
        $admin->syncRoles([$roles['admin']]);

        UserSubscription::updateOrCreate(
            ['user_id' => $admin->id, 'plan_id' => $plans->get('business')->id ?? $plans->first()->id],
            [
                'starts_at' => now()->subMonth(),
                'ends_at' => now()->addYear(),
                'status' => 'active',
            ]
        );

        $editor = User::factory()->create([
            'name' => 'Chrono Editor',
            'email' => 'editor@example.com',
            'password' => Hash::make('Password123!'),
            'username' => 'chronoeditor',
        ]);
        $editor->syncRoles([$roles['editor']]);

        UserSubscription::create([
            'user_id' => $editor->id,
            'plan_id' => $plans->get('pro')->id ?? $plans->first()->id,
            'starts_at' => now()->subWeeks(2),
            'ends_at' => now()->addMonths(2),
            'status' => 'trial',
        ]);

        User::factory()->count(3)->create()->each(function (User $user) use ($roles, $plans) {
            $user->syncRoles([$roles['member']]);

            UserSubscription::create([
                'user_id' => $user->id,
                'plan_id' => $plans->get('free')->id ?? $plans->first()->id,
                'starts_at' => now()->subMonth(),
                'ends_at' => now()->addMonth(),
                'status' => 'active',
            ]);
        });
    }
}
