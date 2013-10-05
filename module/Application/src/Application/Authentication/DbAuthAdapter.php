<?php

namespace Application\Authentication;

use Falcon\User\UserRepo;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

class DbAuthAdapter implements AdapterInterface
{
	protected $userRepo;
	protected $username;
	protected $password;
	
	public function __construct(UserRepo $userRepo)
	{
		$this->userRepo = $userRepo;
	}
	
	public function setUsername($username)
	{
		$this->username = $username;
	}
	
	public function setPassword($password)
	{
		$this->password = $password;
	}
	
	public function authenticate()
	{
		$result = new Result(Result::FAILURE, NULL);
		try {
			$user = $this->userRepo->getUserByUsername($this->username);
			if (!empty($user) && $user->checkPassword($this->password)) {
				$result = new Result(Result::SUCCESS, $user);
			}
		} catch (\Exception $e) {
			
		}
		
		return $result;
	}
}
