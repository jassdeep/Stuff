<!-- File: src/Template/Orders/index.ctp -->
<p>
<h3><?= $this->Html->link('==> Order here <==', ['action' => 'add']) ?></h3>
</p>
<h1>Orders</h1>
<table>
    <tr>
        <th>Id</th>
        <th>Pizza size</th>
        <th>Total</th>
        <th>Actions</th>
    </tr>

<!--  loop through $orders query object -->

    <?php foreach ($orders as $order): ?>
    <tr>
        <td><?= $order->id ?></td>
        <td>
            <?= $this->Html->link($order->pizzaSize, ['action' => 'view', $order->id]) ?>
        </td>
        <td><?= 'CAD '.$this->Number->precision($order->total, 2) ?></td>
        <td>
            <?= $this->Form->postLink(
                'Delete',
                ['action' => 'delete', $order->id],
                ['confirm' => 'Are you sure?'])
            ?>
            <?= $this->Html->link('Edit', ['action' => 'edit', $order->id]) ?>
        </td>
    </tr>
    <?php endforeach; ?>

</table>

<h1>Previous orders</h1>
<table>
    <tr>
        <th>Id</th>
        <th>Pizza size</th>
        <th>Created</th>
        <th>Actions</th>
    </tr>

<!-- loop through $orders query object, -->

    <?php foreach ($completed_orders as $order): ?>
    <tr>
        <td><?= $order->id ?></td>
        <td>
            <?= $this->Html->link($order->pizzaSize, ['action' => 'view', $order->id]) ?>
        </td>
        <td><?= 'CAD '.$this->Number->precision($order->total, 2) ?></td>
        <td>
            <?= $this->Form->postLink(
                'Delete',
                ['action' => 'delete', $order->id],
                ['confirm' => 'Are you sure?'])
            ?>
            <?= $this->Html->link('Edit', ['action' => 'edit', $order->id]) ?>
        </td>
    </tr>
    <?php endforeach; ?>

</table>