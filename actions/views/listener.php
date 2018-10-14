<h1>Listeners</h1>
<?php
if (empty($data['items'])) {
    echo '<h4>No listeners are found</h4>';
    return;
}

?>
<table align="center" width="90%" border="1">
    <tr>
        <th>ID</th>
        <th>Type</th>
        <th>URL</th>
        <th>Role</th>
        <th>Method</th>
        <th>Date</th>
    </tr>
<? foreach ($data['items'] as $listener) : ?>
    <tr>
        <td><?=$listener['id']?></td>
        <td><?=$listener['event_type']?></td>
        <td>
            <small><code><?=$listener['callbacks'][0]['url']?></code></small>
            <? if(count($listener['callbacks']) > 1) : ?>
            <small>+ <?=count($listener['callbacks'])-1?> more.</small>
            <? endif; ?>
        </td>
        <td><?=$listener['callbacks'][0]['role']?></td>
        <td><?=$listener['callbacks'][0]['verb']?></td>
        <td><?=date('d.m.Y H:i:s', $listener['updated_at'])?></td>
    </tr>
<? endforeach; ?>
</table>