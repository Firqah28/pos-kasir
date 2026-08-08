<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateMasterUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'master:create
                            {username=master : Username untuk akun master}
                            {--password=master123 : Password untuk akun master}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Buat atau perbarui akun Master Admin yang langsung bisa dipakai';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $username = (string) $this->argument('username');
        $password = (string) $this->option('password');

        $user = User::updateOrCreate(
            ['username' => $username],
            [
                'password' => Hash::make($password),
                'role' => User::ROLE_MASTER_ADMIN,
                'store_id' => null,
            ]
        );

        $this->info('Akun Master Admin siap dipakai:');
        $this->table(
            ['Username', 'Password', 'Role'],
            [[$user->username, $password, User::ROLE_MASTER_ADMIN]]
        );

        return self::SUCCESS;
    }
}
