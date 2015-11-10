<!-- File: src/Template/Customers/add.ctp -->

<div class="container">
    <div class="row">
        <div class="form-group col-lg-6 col-sm-12">
            <?php
                echo $this->Form->create($customer); ?>
            <fieldset> <legend>Personal Information</legend>
            <?php
                echo $this->Form->input('personName', array('class'=>'form-control'));
                echo $this->Form->input('phone', array('class'=>'form-control', 'size'=>'13'));
                echo $this->Form->input('email', array('class'=>'form-control')); ?>
                <fieldset> <legend>Address</legend>
               <?php 
                    echo $this->Form->input('street', array('class'=>'form-control')); 
                    echo $this->Form->input('province', array(
                        'options' => array('QC', 'MB', 'ON', 'SK'),
                        'empty' => 'Select'
                    ));
                    echo $this->Form->input('city', array('class'=>'form-control'));
                    echo $this->Form->input('postalCode', array('class'=>'form-control'));
                ?>
                </fieldset>
            </fieldset>
             <?php
                echo $this->Form->button(__('Save Customer')); 
                echo $this->Form->end();
            ?>
        </div>
    </div>
</div>
