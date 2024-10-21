<?php

namespace Tests\Utilities;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait AuthenticationUtility
{
    protected $userLogged;

    public function userLogin()
    {
        if ($this->userLogged != null) {
            return $this;
        }

        $this->userLogged = User::factory()->create();
        Sanctum::actingAs($this->userLogged);

        return $this;
    }
}
