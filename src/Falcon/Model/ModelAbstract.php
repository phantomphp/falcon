<?php

namespace Falcon\Model;
use Falcon\Helper\Helper;

abstract class ModelAbstract
{
    protected $id;
    protected $uuid;
    protected $active = true;
    protected $deleted = false;
    protected $createdDate;
    protected $modifiedDate;

    public function __construct($id, $uuid)
    {
        if (!empty($id) && !is_numeric($id)) {
            throw new \InvalidArgumentException('Invalid id detected!');
        }
        $this->id = $id;

        if (!empty($uuid) && !preg_match('/^[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}$/i', $uuid)) {
            throw new \RuntimeException('Invalid UUID detected!');
        } elseif (empty($uuid)) {
            $uuid = Helper::generateUuid();
        }
        $this->uuid = $uuid;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUUID()
    {
        return $this->uuid;
    }

    public function setActive($bool = true)
    {
        $this->active = (bool)$bool;
    }

    public function isActive()
    {
        return $this->active;
    }

    public function setDeleted($bool = true)
    {
        $this->deleted = (bool)$bool;
    }

    public function isDeleted()
    {
        return $this->deleted;
    }

    public function setCreatedDate($date)
    {
        $this->createdDate = date('Y-m-d H:i:s', strtotime($date));
    }

    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    public function setModifiedDate($date = 'now')
    {
        $this->modifiedDate = date('Y-m-d H:i:s', strtotime($date));
    }

    public function getModifiedDate()
    {
        return $this->modifiedDate;
    }

}