<?php

namespace Falcon\Repository;

interface RepositoryInterface
{
    /**
     * Read from repository
     *
     * @param array $columns
     * @param string $table
     * @param array $where
     * @return Zend\Db\Adapter\Driver\ResultInterface
     * */
    public function select($table, array $columns, array $where = array());

    /**
     * Write into repository
     *
     * @param array $data
     * @param string $table
     * @return int Generated value aka last insert id
     * */
    public function insert($table, array $data);

    /**
     * Update data in repository
     *
     * @param array $data
     * @param string $table
     * @param array $where
     * @return int Number of affected records
     * */
    public function update($table, array $data, array $where);

    /**
     * Delete from repository
     *
     * @param strign $table
     * @param array $where
     * @return int Number of affected records
     * */
    public function delete($table, array $where);
}
