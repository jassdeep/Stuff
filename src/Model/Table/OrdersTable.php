<?php
namespace App\Model\Table;

use App\Model\Entity\Order;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class OrdersTable extends Table
{

   
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('orders');
        $this->displayField('id');
        $this->primaryKey('id');

    }

    
    public function validationDefault(Validator $validator)
    {
       $validator
            ->allowEmpty('toppings');
        
        return $validator;
    }
    
    public function isOwnedBy($customerId)
    {
        return $this->exists(['customer' => $customerId]);
    }
}
