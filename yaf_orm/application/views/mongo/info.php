    <style type="text/css">
table.imagetable {
    margin-left:20%;
    margin-top: 50px; 
    font-family: verdana,arial,sans-serif;
    font-size:11px;
    color:#333333;
    border-width: 1px;
    border-color: #999999;
    border-collapse: collapse;
    background-color: #c4e4ff;
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
<div>
    <table class="imagetable">
        <tr><?php for ($i=0; $i <count($key) ; $i++) { 
            echo "<th>".$key[$i]."</th>";
        } ?>
            <th>操作</th>
        </tr>
        <?php foreach ($list as  $value):?>
            <tr>
            <?php for($i=0; $i <count($key) ; $i++) { 
                    echo "<td>".$value[$key[$i]]."</td>";
            } ; ?>
            <td><a href="<?php echo site_url("mongodb/del?dbname=$dbname&collname=$collname&id=").$value['_id'] ?>">删除</a></td> 
            </tr>
        <?php endforeach; ?>
    </table>
</div>
