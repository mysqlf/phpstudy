<table>
<p>表列表</p>
<?php foreach($collection as $key => $value):?>

<tr><td>
    <a href="<?php echo site_url('mongodb/collectioninfo?dbname=').$dbname.'&collname='.$value['name']; ?>" target="info"><?php echo $value['name']; ?></a>
</td></tr>
<?php endforeach; ?>    
</table>