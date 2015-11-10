<?php
namespace App\Model\Table;

use App\Model\Entity\Customer;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class CustomersTable extends Table
{

   
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('customers');
        $this->displayField('name');
        $this->primaryKey('id');

    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('personName')
            ->notEmpty('personName', 'Please enter your name')
            ->add('personName', 'length',['rule' => ['maxLength', 255], 'message' => 'Please enter a valid name']);
        
        $validator
            ->requirePresence('phone')
            ->notEmpty('phone', 'Please enter your phone')
           ->add('phone', 'validFormat', [
        'rule' => array('custom','/\(\d{3}\)\d{3}-\d{4}/'),
        'message' => 'Phone must be valid (999)999-9999'
    ]);
        
        $validator
            ->requirePresence('email')
            ->notEmpty('email', 'Please enter your email')
            ->add('email', 'validFormat', [
        'rule' => 'email',
        'message' => 'E-mail must be valid'
    ]);
        
        $validator
            ->requirePresence('street')
            ->notEmpty('street', 'Please enter your street')
            ->add('street', 'length',['rule' => ['maxLength', 255], 'message' => 'Please enter a valid street']);
        
         $validator
            ->requirePresence('province')
            ->add('province', 'inList', [
                'rule' => ['inList', ['QC', 'MB', 'ON', 'SK']],
                'message' => 'Please enter a valid province'
            ]);
        
        $validator
            ->requirePresence('city')
            ->notEmpty('city', 'Please enter your city')
            ->add('city', 'length',['rule' => ['maxLength', 50], 'message' => 'Please enter a valid city']);
        
        $validator
            ->requirePresence('postalCode')
            ->notEmpty('postalCode', 'Please enter your Postal Code')
           ->add('postalCode', 'validFormat', [
        'rule' => array('custom','/[A-Z]{1}\d{1}[A-Z]{1}\s\d{1}[A-Z]{1}\d{1}/'),
        'message' => 'Postal code must be valid - L0L 0L0'
    ]);
        
        return $validator;
    }


    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }
}
