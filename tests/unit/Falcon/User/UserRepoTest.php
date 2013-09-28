<?php

namespace Falcon\User;

use Falcon\ServiceManagerProviderTestCase;
use Falcon\Helper\Helper;

class UserRepoTest extends ServiceManagerProviderTestCase
{

    const ID = 1;
    const UUID = '424422df-13cd-11e3-b252-080027150945';
    const USERNAME = 'falcon';
    const PASSWORD = '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8'; //sha1 of 'password'
    const ADMIN = 1;
    const ACTIVE = 1;
    const DELETED = 0;
    const CREATED_DATE = '2013-09-01 23:00:00';
    const MODIFIED_DATE = '2013-09-02 19:00:00';

    public function setUp()
    {
        $this->getDb()->execute('truncate user');
        $this->createUserRecord();
    }

    public function tearDown()
    {
        $this->getDb()->execute('truncate user');
    }

    protected function getRepo()
    {
        return new UserRepo();
    }

    public function testInstance()
    {
        $this->assertInstanceOf('Falcon\User\UserRepo', $this->getRepo());
    }

    /**
     * @expectedException Falcon\Exception\RecordNotFoundException
    */
    public function testGetUserByUsernameThrowsExceptionWhenUserIsNotFound()
    {
        $repo = $this->get('User\UserRepo');
        $repo->getUserByUsername('foo');
    }

    protected function createUserRecord()
    {
        $data = array(
            'id' => self::ID,
            'uuid' => self::UUID,
            'username' => self::USERNAME,
            'password' => self::PASSWORD,
            'admin' => self::ADMIN,
            'active' => self::ACTIVE,
            'deleted' => self::DELETED,
            'created_date' => self::CREATED_DATE,
            'modified_date' => self::MODIFIED_DATE
        );
        $this->getDb()->insert('user', $data);

    }

    public function testGetUserByUsername()
    {
        $repo = $this->get('User\UserRepo');
        $expected = new User(self::ID, self::UUID, self::USERNAME, self::PASSWORD);
        $expected->setAdmin(self::ADMIN);
        $expected->setActive(self::ACTIVE);
        $expected->setDeleted(self::DELETED);
        $expected->setCreatedDate(self::CREATED_DATE);
        $expected->setModifiedDate(self::MODIFIED_DATE);

        $actual = $repo->getUserByUsername(self::USERNAME);
        $this->assertEquals($expected, $actual);
    }

    public function testGetUserById()
    {
        $repo = $this->get('User\UserRepo');
        $expected = new User(self::ID, self::UUID, self::USERNAME, self::PASSWORD);
        $expected->setAdmin(self::ADMIN);
        $expected->setActive(self::ACTIVE);
        $expected->setDeleted(self::DELETED);
        $expected->setCreatedDate(self::CREATED_DATE);
        $expected->setModifiedDate(self::MODIFIED_DATE);

        $actual = $repo->getUserById(self::ID);
        $this->assertEquals($expected, $actual);
    }

    public function testCreateUser()
    {
        $repo = $this->get('User\UserRepo');
        $username = 'jane';
        $password = 'secret';
        $user = $repo->create($username, $password);
        $this->assertSame($username, $user->getUsername());
        $this->assertSame(sha1($password), $user->getPassword());
        $this->assertTrue($user->isActive());
    }

    public function testUpdateUser()
    {
        $repo = $this->get('User\UserRepo');
        $user = $repo->getUserById(self::ID);
        $user->setPassword(sha1('foo'));
        $user->setAdmin(0);
        $repo->update($user);
        $actual = $repo->getUserById(self::ID);
        $this->assertEquals($user, $actual);
    }
}