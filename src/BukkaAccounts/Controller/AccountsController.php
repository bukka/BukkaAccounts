<?php
/* 
 * Bukka Accounts Module
 */

namespace BukkaAccounts\Controller;

use BukkaAccounts\Form\IncomeForm;
use BukkaAccounts\Model\Income;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class AccountsController extends AbstractActionController
{
    protected $incomeTable = null;
    
    /**
     * Get income from route params
     * @return BukkaAccounts\Model\Income
     */
    protected function getIncome()
    {
        $id = $this->params()->fromRoute('id', false);
        if (false === $id) {
            return false;
        }
        
        try {
            return $this->getIncomeTable()->getIncome($id);
        } catch (\Exception $ex) {
            return false;
        }
    }
    
    /**
     * Returns template prefix
     * @return string
     * @todo Generate name by the __NAMESPACE__ and Controller name
     */
    protected function getTemplate($action)
    {
        return 'bukka-accounts/accounts/' . $action;
    }
    
    /**
     * This is used to neste action views to the content view.
     * The layout is not used to allow nesting view to the Application layout
     * @param Zend\View\Model\ViewModel|array $child
     * @return Zend\View\Model\ViewModel
     */
    protected function getView($action, $child, $options = array())
    {
        if (!$child instanceof ViewModel)
        {
            $child = new ViewModel($child);
        }
        $child->setTemplate($this->getTemplate($action));
        $view = new ViewModel();
        $view->setTemplate($this->getTemplate('content'));
        $view->addChild($child, 'content');
        $view->options = $options;
        return $view;
    }
    
    /**
     * Get income table
     * @return IncomeTable
     */
    protected function getIncomeTable()
    {
        if (!$this->incomeTable) {
            $sm = $this->getServiceLocator();
            $this->incomeTable = $sm->get('BukkaAccounts\Model\IncomeTable');
        }
        return $this->incomeTable;
    }
    
    /**
     * Index action
     * @return Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        return $this->getView(
            'index',
            array('incomes' => $this->getIncomeTable()->fetchAll()),
            array('extra' => array('data-tables')));
    }
	
    /**
     * Add income action
     * @return Zend\View\Model\ViewModel
     */
	public function addIncomeAction()
    {
        $form = new IncomeForm();
        $form->get('submit')->setValue('Add');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $income = new Income();
            $form->setInputFilter($income->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $income->exchangeArray($form->getData());
                $this->getIncomeTable()->saveIncome($income);
                
                return $this->redirect()->toRoute('accounts');
            }
        }
        
        
        return $this->getView('add-income', array('form' => $form));
    }
    
    /**
     * Edit action
     * @return Zend\View\Model\ViewModel
     */
	public function editIncomeAction()
    {
        $income = $this->getIncome();
        if (false === $income) {
            return $this->redirect()->toRoute('accounts', array('action' => 'add'));
        }
        
        $form = new IncomeForm();
        $form->bind($income);
        $form->get('submit')->setValue('Edit');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($income->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $this->getIncomeTable()->saveIncome($income);
                return $this->redirect()->toRoute('accounts');
            }
        }
        
        return $this->getView('edit-income', array(
            'id'   => $income->id,
            'form' => $form,
        ));
    }
    
    /**
     * Delete AJAX action
     * @return Zend\View\Model\JsonModel
     */
    public function deleteIncomeAction()
    {
        $income = $this->getIncome();
        $view = new JsonModel();
        
        if (false === $income) {
            $view->error = 'Invalid ID';
        } else {
            $this->getIncomeTable()->deleteIncome($income->id);
            $view->success = 'Income deleted';
        }
        
        return $view;
    }
}
