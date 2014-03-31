<?php

namespace BukkaAccountsTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AccountsControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;
    
    public function setUp()
    {
        $this->setApplicationConfig(
            include dirname(dirname(dirname(dirname(dirname(__DIR__)))))
                . '/config/application.config.php'
        );
        parent::setUp();
    }
    
    private function getAccountsMock($class)
    {
         return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->getMock();
    }
    
    private function setAccountsMock($class, $mock)
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService($class, $mock);
    }
    
    private function mockAccounts($class, $method, $returnValue)
    {
        $mock = $this->getAccountsMock($class);
        
        $mock->expects($this->once())
             ->method($method)
             ->will($this->returnValue($returnValue));
        
        $this->setAccountsMock($class, $mock);
    }
    
    public function testIndexActionGet()
    {
        $this->mockAccounts('BukkaAccounts\Model\IncomeTable', 'fetchAll', array());
        
        $this->dispatch('/accounts');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('BukkaAccounts');
        $this->assertControllerName('BukkaAccounts\Controller\Accounts');
        $this->assertControllerClass('AccountsController');
        $this->assertMatchedRouteName('accounts');
        $this->assertQuery("#incomes-table");
    }
    
    public function testAddIncomeActionGet()
    {
        $this->dispatch('/accounts/add-income');
        $this->assertResponseStatusCode(200);
        $this->assertActionName('add-income');
        $this->assertQuery("#submitbutton");
    }
    
    public function testAddIncomeActionPost()
    {
        $this->mockAccounts('BukkaAccounts\Model\IncomeTable', 'saveIncome', null);
         
        $postData = array(
            'id' => "",
            'price' => 2000,
            'invoice_id' => "i2",
            'description' => "bub",
            'submit' => "Add",
        );
        $this->dispatch('/accounts/add-income', 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/accounts/');
    }
    
    public function testEditIncomeActionGet()
    {
        $income = new \BukkaAccounts\Model\Income();
        $this->mockAccounts('BukkaAccounts\Model\IncomeTable', 'getIncome', $income);
        
        $this->dispatch('/accounts/edit-income/1');
        $this->assertResponseStatusCode(200);
        $this->assertActionName('edit-income');
        $this->assertQuery("#submitbutton");
    }
    
    public function testEditIncomeActionGetIdNotFound()
    {   
        $mock = $this->getAccountsMock('BukkaAccounts\Model\IncomeTable');
        $mock->expects($this->once())
             ->method('getIncome')
             ->will($this->throwException(new \Exception("Could not find row id '1'")));
        $this->setAccountsMock('BukkaAccounts\Model\IncomeTable', $mock);
        
        $this->dispatch('/accounts/edit-income/1');
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/accounts/add');
        $this->assertActionName('edit-income');
    }
    
    public function testEditIncomeActionPost()
    {
        $income = new \BukkaAccounts\Model\Income();
        $mock = $this->getAccountsMock('BukkaAccounts\Model\IncomeTable');
        $mock->expects($this->once())
             ->method('getIncome')
             ->will($this->returnValue($income));
        $mock->expects($this->once())
             ->method('saveIncome')
             ->will($this->returnValue(null));
        $this->setAccountsMock('BukkaAccounts\Model\IncomeTable', $mock);
         
        $postData = array(
            'id' => "1",
            'price' => 2000,
            'invoice_id' => "i2",
            'description' => "bub",
            'submit' => "Add",
        );
        $this->dispatch('/accounts/edit-income/1', 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/accounts/');
    }
    
    public function testDeleteIncomeActionPost()
    {
        $income = new \BukkaAccounts\Model\Income();
        $mock = $this->getAccountsMock('BukkaAccounts\Model\IncomeTable');
        $mock->expects($this->once())
             ->method('getIncome')
             ->will($this->returnValue($income));
        $mock->expects($this->once())
             ->method('deleteIncome')
             ->will($this->returnValue(null));
        $this->setAccountsMock('BukkaAccounts\Model\IncomeTable', $mock);
        
        $this->dispatch('/accounts/delete-income/1', 'POST');
        $this->assertResponseStatusCode(200);
    }
}
