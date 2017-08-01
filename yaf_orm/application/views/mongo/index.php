<!DOCTYPE html>
<html>
<head>
    <title>数据库列表</title>
</head>
<body>
<table>
    <tr> 
        <th>数据库名</th>
        <th>大小</th>
        <th>操作</th>
    </tr>
    <?php foreach ($list as $value): ?>
        <tr>
            <td><a href=""><?php echo $value['name']; ?></a></td>
            <td><?php echo $value['sizeOnDisk']/1024; ?>K</td>
            <td>删除</td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
