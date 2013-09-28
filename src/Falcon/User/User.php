<?php

namespace Falcon\User;

use Falcon\Model\ModelAbstract;

class User extends ModelAbstract
{
    protected $username;
    protected $password;
    protected $admin = false;

    public function __construct($id, $uuid, $username, $password)
    {
        parent::__construct($id, $uuid);
        if (empty($username)) {
            throw new \InvalidArgumentException('Username cannot be empty');
        }
        $this->username = $username;

        if (empty($password)) {
            throw new \InvalidArgumentException('Password cannot be empty');
        }
        $this->password = $password;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function checkPassword($plain)
    {
        return $this->password == sha1($plain);
    }

    public function setAdmin($bool = true)
    {
        $this->admin = (bool)$bool;
    }

    public function isAdmin()
    {
        return $this->admin;
    }
}