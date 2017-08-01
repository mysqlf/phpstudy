<div>
    <table>
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
            </tr>
        <?php endforeach; ?>
    </table>
</div>
