<?php

namespace Application;

use Falcon\ServiceManagerProviderTestCase;
use Zend\Authentication\AuthenticationService;

class DbAuthAdapterTest extends ServiceManagerProviderTestCase
{
	const USERNAME = 'test';
	const PASSWORD = 'password';
	
	public function setUp()
	{
		parent::setUp();
		$this->getDb()->delete('user', array('username' => self::USERNAME));
		$this->getDb()->insert('user', array(
			'username' => self::USERNAME,
			'password' => sha1(self::PASSWORD),
			'active' => 1
		));
	}
	
	public function getAdapter()
	{
		$userRepo = $this->get('User\UserRepo');
		return new Authentication\DbAuthAdapter($userRepo);
	}
	
	public function testInstance()
	{
		$this->assertInstanceOf('Zend\Authentication\Adapter\AdapterInterface', $this->getAdapter());
	}
	
	public function testAuthenticateFail()
	{
		$adapter = $this->getAdapter();
		$adapter->setUsername(self::USERNAME);
		$adapter->setPassword('foo');
		$result = $adapter->authenticate();
		$this->assertInstanceOf('Zend\Authentication\Result', $result);
		$this->assertTrue($result->getCode() != 1);
	}
	
	public function testAuthenticateSuccess()
	{
		$adapter = $this->getAdapter();
		$adapter->setUsername(self::USERNAME);
		$adapter->setPassword(self::PASSWORD);
		$result = $adapter->authenticate();
		$this->assertInstanceOf('Zend\Authentication\Result', $result);
		$this->assertTrue($result->getCode() == 1);
		$this->assertInstanceOf('Falcon\User\User', $result->getIdentity());
	}
	
	public function testZendAuthServiceIntegration()
	{
		$adapter = $this->getAdapter();
		$adapter->setUsername(self::USERNAME);
		$adapter->setPassword(self::PASSWORD);
		$service = new AuthenticationService(null, $adapter);
		$result = $service->authenticate();
		$this->assertTrue($result->getCode() == 1);
	}
	
	public function tearDown()
	{
		parent::tearDown();
		$this->getDb()->delete('user', array('username' => self::USERNAME));
	}
}
