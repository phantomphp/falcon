<?php

namespace Falcon\Repository;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\PreparableSqlInterface;
use Zend\Db\Sql\Select;


class DbRepository implements RepositoryInterface
{
    /**
     * @var Zend\Db\Sql\Sql
     * */
    private $sql;

    private $adapter;

    /**
     * @var bool
     * */
    private $bufferResults;

    /**
     * Constructor
     * @param Zend\Db\Adapter\Adapter $adapter
     * @param bool $bufferResults
     * */
    public function __construct(Adapter $adapter, $bufferResults = true)
    {
        $this->adapter = $adapter;
        $this->sql = new Sql($adapter);
        $this->bufferResults = (bool) $bufferResults;
    }

    /**
     * Returns select object
     *
     * @return Zend\Db\Sql\Select
     * */
    public function getSelect()
    {
        return $this->sql->select();
    }

    /**
     * Runs the query and return result
     *
     * @param Zend\Db\Sql\Select | string $sql
     * @return  Zend\Db\Adapter\Driver\ResultInterface
     * */
    public function execute($sql)
    {
        if ($sql instanceof PreparableSqlInterface) {
            $statement = $this->sql->prepareStatementForSqlObject($sql);
        } elseif(is_string($sql)) {
            $statement = $this->adapter->createStatement($sql);
        } else {
            throw new \InvalidArgumentException('Invalid argument provided to execute(). Must be Zend\Db\Sql\PreparableSqlInterface or string. Actual:' . gettype($slq));
        }
        $result = $statement->execute();
        if ($this->bufferResults) {
            $result->buffer();
        }

        return $result;
    }

    /**
     * @see RepositoryInterface::select()
     * */
    public function select($table, array $columns, array $where = array())
    {
        $select = $this->sql->select();
        $select->columns($columns);
        $select->from($table);
        if (!empty($where)){
            $select->where($where);
        }

        return $this->execute($select);
    }

    /**
     * @see RepositoryInterface::insert()
     * */
    public function insert($table, array $data)
    {
        $insert = $this->sql->insert();
        $insert->into($table);
        $insert->values($data);
        return $this->execute($insert)->getGeneratedValue();
    }

    /**
     * @see RepositoryInterface::update()
     * */
    public function update($table, array $data, array $where)
    {
        if (empty($where)) {
            throw new \InvalidArgumentException('Update operation without condition is not permitted.');
        }
        $update = $this->sql->update();
        $update->table($table);
        $update->set($data);
        $update->where($where);
        return $this->execute($update)->getAffectedRows();
    }

    /**
     * @see RepositoryInterface::delete()
     * */
    public function delete($table, array $where)
    {
        if (empty($where)) {
            throw new \InvalidArgumentException('Delete operation without condition is not permitted.');
        }
        $delete = $this->sql->delete();
        $delete->from($table);
        $delete->where($where);
        return $this->execute($delete)->getAffectedRows();
    }

}
