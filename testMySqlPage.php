<?php
    require_once 'LoginAzSql.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>JT PHP Page Title</title>
    </head>
    <body>
         <div> <p>Connecting to MySQL within PHP:</p>
        </div>       
<?php
$mysqli = mysqli_connect($hn, $un, $pw, $db);
if (mysqli_connect_errno($mysqli)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


?>
<div> <p>Connection done.</p>
</div>     
<?php
$mysqli->real_query("SELECT My_Value FROM jt_table1 ORDER BY AutoInc ASC");
$res = $mysqli->use_result();

echo "Result set order...\n";
while ($row = $res->fetch_assoc()) {
    echo " My_Value = " . $row['My_Value'] . "\n";
}
?>
    <div> <p>Next:</p>
    </div>   

<?php  
if (!$mysqli->query("UPDATE jt_table1 SET My_Value = My_Value + 1 WHERE My_Action='Example Entry'")) {
    echo "Table update failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

$mysqli->real_query("SELECT My_Value FROM jt_table1 ORDER BY AutoInc ASC");
$res = $mysqli->use_result();

echo "Result set order...\n";
while ($row = $res->fetch_assoc()) {
    echo " My_Value = " . $row['My_Value'] . "\n";
}

?>
        <div> <p>Finished.</p>
        </div>         
    </body>
</html>
