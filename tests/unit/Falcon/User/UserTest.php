<?php

namespace Falcon\User;

class UserTest extends \PHPUnit_Framework_TestCase
{

    const ID = 1;
    const UUID = 'abcdefgh-ijkl-mnop-qrst-uvwxyz123456';
    const USERNAME = 'john';
    const PASSWORD = 'password';
    const ADMIN = 1;

    protected function getUser()
    {
        return new User(self::ID, self::UUID, self::USERNAME, sha1(self::PASSWORD));
    }

    public function testInstance()
    {
        $user = $this->getUser();
        $this->assertInstanceOf('Falcon\Model\ModelAbstract', $user);
        $this->assertSame(self::ID, $user->getId());

        $this->assertSame(self::USERNAME, $user->getUsername());
        $this->assertSame(sha1(self::PASSWORD), $user->getPassword());
        $this->assertTrue($user->checkPassword(self::PASSWORD));
        $this->assertTrue($user->isAdmin() === false);
        $user->setAdmin();
        $this->assertTrue($user->isAdmin() === true);
        $user->setAdmin(false);
        $this->assertTrue($user->isAdmin() === false);
        $user->setAdmin(true);
        $this->assertTrue($user->isAdmin() === true);
        //check abstract methods
        $this->assertTrue($user->isActive() === true);
        $user->setActive(false);
        $this->assertTrue($user->isActive() === false);
        $user->setActive(true);
        $this->assertTrue($user->isActive() === true);

        $this->assertTrue($user->isDeleted() === false);
        $user->setDeleted(false);
        $this->assertTrue($user->isDeleted() === false);
        $user->setDeleted(true);
        $this->assertTrue($user->isDeleted() === true);

        $yesterday = date('Y-m-d H:i:s', strtotime('-1 day'));
        $user->setCreatedDate($yesterday);
        $this->assertSame($yesterday, $user->getCreatedDate());

        $now = date('Y-m-d H:i:s', strtotime('now'));
        $user->setModifiedDate($now);
        $this->assertSame($now, $user->getModifiedDate());

    }
}