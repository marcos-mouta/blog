<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetUserRole extends Command
{
    protected $signature = 'user:role {email : E-mail do usuário} {role : user, moderator ou admin}';

    protected $description = 'Define o papel (role) de um usuário';

    public function handle(): int
    {
        $role = $this->argument('role');

        if (! in_array($role, User::ROLES, true)) {
            $this->error('Papel inválido. Use: ' . implode(', ', User::ROLES));

            return self::FAILURE;
        }

        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('Nenhum usuário encontrado com esse e-mail.');

            return self::FAILURE;
        }

        $user->role = $role;
        $user->save();

        $this->info("{$user->name} agora tem o papel: {$role}.");

        return self::SUCCESS;
    }
}
