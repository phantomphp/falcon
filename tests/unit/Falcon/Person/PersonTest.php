<?php

namespace Falcon\Person;

class PersonTest extends \PHPUnit_Framework_TestCase
{

    const ID = 1;
    const UUID = 'bd7715b8-f3a3-da98-76e1-6a6b560233d6';
    const FNAME = 'John';
    const LNAME = 'Doe';
    const MNAME = 'M.';
    const DOB = '2012-01-01';
    const GENDER = 'M';

    public function getPerson()
    {
        return new Person(self::ID, self::UUID, self::FNAME, self::LNAME);
    }

    public function testInstance()
    {
        $person = $this->getPerson();
        $this->assertSame(self::ID, $person->getId());
        $this->assertSame(self::UUID, $person->getUUID());
        $this->assertSame(self::FNAME, $person->getFirstname());
        $this->assertSame(self::LNAME, $person->getLastname());
        $person->setMiddlename(self::MNAME);
        $this->assertSame(self::MNAME, $person->getMiddlename());
        $person->setDateOfBirth(self::DOB);
        $this->assertSame(self::DOB, $person->getDateOfBirth());
        $person->setGender(self::GENDER, $person->getGender());
    }

}