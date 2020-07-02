<?php

namespace App\Events;

use App\Entity\User;

class BadPasswordLoginEvent
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

}
