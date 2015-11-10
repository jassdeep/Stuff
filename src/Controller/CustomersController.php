<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Customers Controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 */
class CustomersController extends AppController
{
    
    public function beforeFilter(Event $event)
    {
        
        $this->Auth->allow(['add']);
    }
    
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Flash'); 
        $this->set(compact('customers'));
        $customer = $this->Customers->newEntity();
        $this->$customer;
        
    }
    
    public function isAuthorized($user)
    {
        return parent::isAuthorized($user);
    }

    public function index()
    {
        $user = $this->Auth->user();
        if ($this->isAuthorized($user)) {
            $customer = $this->Customers->newEntity();
            $this->$customer;
            $this->set('customers', $this->Customers->find('all'));
        }
    }
    

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $customer = $this->Customers->newEntity();
        if ($this->request->is('post')) {
            $this->request->data['province'] = $this->getProvince($this->request->data['province']);
            
     
        $session = $this->request->session();
        $user_session = $session->read('user_id');
        
        $customer = $this->Customers->patchEntity($customer, $this->request->data);
            $customer->user = $user_session['id'];
           if ($this->Customers->save($customer)) {
                $this->Flash->success(__('Your customer has been saved.'));
               
               $session->write('customer_id', $customer->id);
               
               return $this->redirect(['controller'=>'users','action' => 'login']);
            } 
            $this->Flash->error(__('Unable to add your customer.')); 
        }
        $this->set('customer', $customer);
    }
    
    private function getProvince($position){
       
        switch ($position){
            case 0:
                return 'QC';
             case 1:
                return 'MB';
             case 2:
                return 'ON';
             case 3:
                return 'SK';
             default:
                return '';
        }
    }
}
