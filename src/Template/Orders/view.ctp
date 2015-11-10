<!-- File: src/Template/Orders/view.ctp -->

<table>
    <tr>
        <th>Id</th>
        <th>Pizza size</th>
        <th>Crust Type</th>
        <th>Toppings</th>
        <th>Total</th>
        <th>Actions</th>
    </tr>
    <tr>
        <td><?= $order->id ?></td>
        <td><?= $order->pizzaSize?></td>
        <td><?= $order->crustType?></td>
        <td><?= $order->toppings?></td>
        <td><?= 'CAD '.$this->Number->precision($order->total, 2) ?></td>
        <td>
            <?= $this->Html->link('Mark as completed', ['action' => 'complete', $order->id]) ?>
        </td>
    </tr>
</table>