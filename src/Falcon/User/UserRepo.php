<?php

namespace Falcon\User;

use Falcon\Repository\RepositoryAwareInterface;
use Falcon\Repository\RepositoryInterface;
use Falcon\Exception\RecordNotFoundException;

class UserRepo implements RepositoryAwareInterface
{
    protected $repository;

    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getUserById($id)
    {
        return $this->find(array('id' => $id));
    }

    public function getUserByUUID($uuid)
    {
        return $this->find(array('uuid' => $uuid));
    }

    public function getUserByUsername($username)
    {
        return $this->find(array('username' => $username));
    }

    public function create($username, $password)
    {
        $user = new User(NULL, NULL, $username, sha1($password));
        $user->setCreatedDate('now');
        $user->setModifiedDate('now');
        if ($this->save($user)) {
            return $user;
        }
        throw new \RuntimeException('Cannot create a user.');
    }

    public function update(User $user)
    {
        return $this->save($user);
    }

    protected function find(array $params)
    {
        $result = $this->repository->select('user', array('*'), $params);
        if (!$result->valid()) {
            throw new RecordNotFoundException('User does not exist!');
        }
        $row = $result->current();
        $user = new User($row['id'], $row['uuid'], $row['username'], $row['password']);
        $user->setActive($row['active']);
        $user->setAdmin($row['admin']);
        $user->setDeleted($row['deleted']);
        $user->setCreatedDate($row['created_date']);
        $user->setModifiedDate($row['modified_date']);

        return $user;
    }

    protected function save(User $user)
    {
        try {
            $data = array(
                'id' => $user->getId(),
                'uuid' => $user->getUUID(),
                'username' => $user->getUsername(),
                'password' => $user->getPassword(),
                'admin' => (int)$user->isAdmin(),
                'active' => (int)$user->isActive(),
                'deleted' => (int)$user->isDeleted(),
                'created_date' => $user->getCreatedDate(),
                'modified_date' => $user->getModifiedDate()
            );

            $this->repository->delete('user', array('id' => $user->getId()));
            $this->repository->insert('user', $data);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}