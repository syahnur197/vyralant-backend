<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\UserService;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class UsersSeeder extends Seeder
{

    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->testUser()
            ->count(1)
            ->create()
            ->each(function ($user) {
                $this->service->uploadProfilePicture($user);
            });

        User::factory()
            ->count(100)
            ->create()
            ->each(function ($user) {
                $this->service->uploadProfilePicture($user);
            });
    }
}
