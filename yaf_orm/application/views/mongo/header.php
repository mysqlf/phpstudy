<!DOCTYPE html>
<html>
<head>
    <title>mongodb</title>
        <style type="text/css">
table.imagetable {
    font-family: verdana,arial,sans-serif;
    font-size:11px;
    color:#333333;
    border-width: 1px;
    border-color: #999999;
    border-collapse: collapse;
    width: 50%;
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
</head>
<body style="width: 100%;height: 100%;">
<div style="width: 100%;height: 10%;">
    
</div>
<div style="width:20%;height:90%;float: left;">
<div style="width:100%;height:50%;">
<p>数据库列表</p>
    <table class="imagetable">
        <?php foreach ($list as $value): ?>
        <tr>
        <td>
            <a href="<?php echo site_url('mongodb/collectionlist?dbname=').$value['name'] ?>" target="coll"><?php echo $value['name']; ?></a>
        </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div>
    <iframe style="width: 100% ;height: 500px;" src="" name="coll"></iframe>
</div>
</div>
<div style="width:80%;height:90%;float:right;">
    <iframe style="width: 100% ;height:700px;" src="" name="info"></iframe>
</div>
</body>
</html>
