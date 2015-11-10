<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Orders Controller
 *
 * @property \App\Model\Table\OrdersTable $Orders
 */
class OrdersController extends AppController
{
    
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->set(compact('orders'));
        $order = $this->Orders->newEntity();
        $this->$order;
    }
    
    public function isAuthorized($user)
    {
     
        if ($this->request->action === 'add') {
            return true;
        }
        
        if (in_array($this->request->action, ['edit', 'delete', 'complete'])) {
            return parent::isAuthorized($user);
        }

        return parent::isAuthorized($user);
    }

    public function index()
    {
        $order = $this->Orders->newEntity();
        $this->$order;
        
        $user = $this->Auth->user();
        if (!parent::isAuthorized($user)) {
            $session = $this->request->session();
            $customer_session = $session->read('customer_id');
            if ($this->Orders->isOwnedBy($customer_session)) {
                $this->set('completed_orders', $this->Orders->find()->where(['completed' => 1, 'customer' => $customer_session]));
            $this->set('orders', $this->Orders->find()->where(['completed' => 0, 'customer' => $customer_session]));
            } else {
                $this->set('completed_orders', []);
                $this->set('orders', []);
            }
        } else {
            $this->set('completed_orders', $this->Orders->find()->where(['completed' => 1]));
            $this->set('orders', $this->Orders->find()->where(['completed' => 0]));
        }
      
    }

    /**
     * View method
     *
     * @param string|null $id Order id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $order = $this->Orders->get($id, [
            'contain' => []
        ]);
        $this->set('order', $order);
        $this->set('_serialize', ['order']);
    }
    

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $order = $this->Orders->newEntity();
        if ($this->request->is('post')) {
          
            $session = $this->request->session();
            $customer_session = $session->read('customer_id');
            $this->request->data['customer'] = $customer_session;
            
            $order = $this->Orders->patchEntity($order, $this->request->data);
            
            $order = $this->calculateTotal($order);
            
           if ($this->Orders->save($order)) {
                $this->Flash->success(__('Your order has been saved.'));
                return $this->redirect(['action' => 'index']);
            } 
            $this->Flash->error(__('Unable to add your order.'));
        }
        $this->set('order', $order);
    }
    
    private function addToppings($order){
        
        $toppings = '';
        
        if (isset($this->request->data['toppings'])){
            $count = sizeof($this->request->data['toppings']);
            
            foreach ($this->request->data['toppings'] as $row) {
                if (!empty($toppings)){
                    $toppings = $toppings.', ';
                }
                $toppings = $toppings.$row;
            }
            
            $order->toppings = $toppings;
            
            if ($count > 1){
                $order->total = $order->total + ($count * 0.5);
            }
        }
        
         return $order;

    }
    
    function calculateTotal($order){
		$total = 0;
		
        $pizzaSize = $order->pizzaSize;	
		switch ($pizzaSize){
			case "Small":
				$total += 5;
				break;
			case "Med":
				$total += 10;
				break;
			case "Large":
				$total += 15;
				break;
			case "XL":
				$total += 20;
				break;			
        }
        
        if ($order->crustType === 'Stuffed'){
            $total += 2;
        }
        
        $order->total = $total;
        
        //add toppings
        $order = $this->addToppings($order);
        
        return $this->calculateProvinceTax($order);
        
    }
    
    function calculateProvinceTax($order){
        $session = $this->request->session();
        $province = $session->read('customer_province');
        $total = $order->total;
        echo $total;
        $tax = 1;
        switch ($province){
            case 'QC':
                $tax = 14.975;
				break;
             case 'MB':
                $tax = 8;
				break;
             case 'ON':
                $tax = 13;
				break;
             case 'SK':
                $tax = 10;
				break;
             default:
                $tax = 1;
        }
        
        $total = $total * ((100+$tax)/100);

        $order->total = $total;
        
        return $order;
    }

    /**
     * Edit method
     *
     * @param string|null $id Order id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $order = $this->Orders->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $order = $this->Orders->patchEntity($order, $this->request->data);
            
            $order = $this->calculateTotal($order);
            
            if ($this->Orders->save($order)) {
                $this->Flash->success(__('The order has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The order could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('order'));
        $this->set('_serialize', ['order']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Order id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $order = $this->Orders->get($id);
        if ($this->Orders->delete($order)) {
            $this->Flash->success(__('The order has been deleted.'));
        } else {
            $this->Flash->error(__('The order could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
    
    public function complete($id = null)
    {
        $order = $this->Orders->get($id, [
            'contain' => []
        ]);
        $order = $this->Orders->patchEntity($order, $this->request->data);
        $order->completed = 1;
        echo $order;
        if ($this->Orders->save($order)) {
            $this->Flash->success(__('The order has been completed.'));
            return $this->redirect(['action' => 'index']);
        } else {
            $this->Flash->error(__('The order could not be saved. Please, try again.'));
        }
        $this->set(compact('order'));
        $this->set('_serialize', ['order']);
    }
}
