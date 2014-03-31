<?php

namespace BukkaAccounts\Model;

use Zend\Db\TableGateway\TableGateway;

class IncomeTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function getIncome($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('income_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row id '$id'");
        }
        return $row;
    }
    
    public function saveIncome(Income $income)
    {
        $data = $income->getParams();
        $id = (int) $income->id;
        if (!$id) {
            $this->tableGateway->insert($data);
        } elseif ($this->getIncome($id)) {
            $this->tableGateway->update($data, array('income_id' => $id));
        } else {
            throw new \Exception("Income id does not exist");
        }
    }
    
    public function deleteIncome($id)
    {
        $this->tableGateway->delete(array('income_id' => (int) $id));
    }
}

