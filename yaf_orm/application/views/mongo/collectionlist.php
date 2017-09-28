        <style type="text/css">
table.imagetable {
    font-family: verdana,arial,sans-serif;
    font-size:11px;
    color:#333333;
    border-width: 1px;
    border-color: #999999;
    border-collapse: collapse;
    background-color: #c4e4ff;
    width: 50%;
}
table.imagetable th {
    border-width: 1px;
    padding: 8px;
    border-style: solid;
    border-color: #999999;
}
table.imagetable td {
    border-width: 1px;
    padding: 8px;
    border-style: solid;
    border-color: #999999;
}

</style>
<table class="imagetable">
<p>表列表</p>
<?php foreach($collection as $key => $value):?>

<tr><td>
    <a href="<?php echo site_url('mongodb/collectioninfo?dbname=').$dbname.'&collname='.$value['name']; ?>" target="info"><?php echo $value['name']; ?></a>
</td>
    <td><a href="<?php echo site_url('mongodb/delcollection?dbname=').$dbname.'&collname='.$value['name'] ?>">删除</a></td>
</tr>
<?php endforeach; ?>    
</table>
