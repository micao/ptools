<?php

namespace Album\Model\User;

use phpDocumentor\Reflection\Types\String_;
use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class UserTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false)
    {
        return $this->tableGateway->select();
    }

    public function find($id)
    {
        $id     = (int) $id;
        $rowset = $this->tableGateway->select(['id_user' => $id]);
        $row    = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function findByLogin($login)
    {
        $login  = (string) $login;
        $rowset = $this->tableGateway->select(['login' => $login]);
        $row    = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %s',
                $login
            ));
        }

        return $row;
    }
}