<?php

namespace App\DTO\auth;

class UserResponseDTO
{
    public int $id;
    public string $email;
    public string $role;

    public function __construct(int $id, string $email,string $role)
    {
        $this->id = $id;
        $this->email = $email;
        $this->role=$role;
    }
}
