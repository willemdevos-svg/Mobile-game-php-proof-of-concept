<?php
//entities/User.php

namespace Entities;

class User
{

    private int $id;
    private string $userName;
    private string $email;
    private string $password;

    public function __construct(int $id, string $userName, string $email, string $password)
    {
        $this->id = $id;
        $this->userName = $userName;
        $this->email = $email;
        $this->password = $password;
    }

    function getId(): int
    {
        return $this->id;
    }

    function getUserName(): string
    {
        return $this->userName;
    }

    function getEmail(): string
    {
        return $this->email;
    }

    function getPassword(): string
    {
        return $this->password;
    }

    function setUserName(string $userName)
    {
        $this->userName = $userName;
    }

    function setPassword(string $password)
    {
        $this->password = $password;
    }
}
