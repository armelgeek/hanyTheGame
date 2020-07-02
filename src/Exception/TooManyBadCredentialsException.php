<?php

namespace App\Exception;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class TooManyBadCredentialsException extends AuthenticationException
{

    private $user;

    public function __construct($user)
    {
        $this->user = $user;
        parent::__construct('Too many attempt.', 0, null);
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getMessageKey()
    {
        return 'Too many attempt.';
    }

}
