<?php

namespace Falcon\Person;

use Falcon\Model\ModelAbstract;
use Falcon\Helper\Helper;

class Person extends ModelAbstract
{
    protected $fname;
    protected $lname;
    protected $mname;
    protected $dob;
    protected $gender;

    public function __construct($id, $uuid, $fname, $lname)
    {
        parent::__construct($id, $uuid);

        if (empty($fname)) {
            throw new \InvalidArgumentException('Firstname is empty');
        }
        $this->fname = $fname;

        if (empty($lname)) {
            throw new \InvalidArgumentException('Lastname is empty');
        }
        $this->lname = $lname;
    }

    public function getFirstname()
    {
        return $this->fname;
    }

    public function getLastname()
    {
        return $this->lname;
    }

    public function setMiddlename($name)
    {
        $this->mname = $name;
    }

    public function getMiddlename()
    {
        return $this->mname;
    }

    public function setDateOfBirth($date)
    {
        $this->dob = $date;
    }

    public function getDateOfBirth()
    {
        return $this->dob;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    public function getGender()
    {
        return $this->gender;
    }
}