<?php
    require_once 'LoginAzSql.php';
    echo "Processing POST command:<p>";
	$errorMessage = "";
    $date = new DateTime();
    // FUTURE: Show in the correct time zone, i.e. CDT.

    if(empty($_POST['formIndex']))
    {
		$errorMessage .= "<li>You forgot to enter an Index!</li>";
	}
    $varIndex = sanitizeString($_POST['formIndex']);
	
    if(empty($_POST['formDeviceId']) and $varIndex <> "LIST") 
    {
		$errorMessage .= "<li>You forgot to enter a DeviceId!</li>";
	}
    $varDeviceId = sanitizeString($_POST['formDeviceId']);

	if(empty($_POST['formValue']) and $varIndex <> "LIST") 
    {
		$errorMessage .= "<li>You forgot to enter a Value!</li>";
	}
	$varValue = sanitizeString($_POST['formValue']);

	if(empty($errorMessage)) 
    {
        echo "Connecting to Azure Jackalope MySQL db:<p>";
        $mysqli = mysqli_connect($hn, $un, $pw, $db);
        if (mysqli_connect_errno($mysqli)) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        echo "Connection done.<p>";

        echo "Submitting Update, SET My_TimeStamp = '" . $date->format('Y-m-d H:i:s') . "', My_Value = " . $varValue . " WHERE My_Action='" . $varIndex . "' and My_DeviceId=" . $varDeviceId . "<p>";
        $varSql = "UPDATE jt_table2 SET My_TimeStamp = '" . $date->format('Y-m-d H:i:s') . "', My_Value = " . $varValue . " WHERE My_Action='" . $varIndex . "' and My_DeviceId=" . $varDeviceId;

        if ($varIndex == "LIST") { 
            echo "<p>List Only:";
            $varSql = "SELECT * FROM jt_table2";
        };
            
        if ($varIndex == "ADD") { 
            echo "<p>Add Entry:";
            $varSql = "INSERT INTO jt_table2 (My_DeviceId,My_TimeStamp, My_Action, My_Value) VALUES (". $varDeviceId . ",'2015-10-15 01:00', '" . $varValue . "',0)";
        };

        if ($varIndex == "DELETE") { 
            echo "<p>Delete Entry:";
            $varSql = "DELETE FROM jt_table2 WHERE My_Action = '" . $varValue . "' and My_DeviceId=" . $varDeviceId;
        };             

        if ($varIndex == "CLEAR") { 
            if ($varValue == "CLEAR") {
                echo "<p>Clear Table:";
                $varSql = "DELETE FROM jt_table2 where My_TimeStamp > '2015-01-02'";
            };
        };  

        if ($varIndex == "1SHOT") { 
            echo "<p>1SHOT Special:";
            if ($varValue == "CREATE") {
               $varSql = "CREATE TABLE jt_table2 (My_DeviceId Int, My_TimeStamp DateTime, My_Action Varchar(12), My_Value Int)";
            };
            if ($varValue == "DROP") {
               $varSql = "DROP TABLE jt_table2 ";
            };
            if ($varValue == "PRIME") {
               $varSql = "INSERT INTO jt_table2 (My_DeviceId, My_TimeStamp, My_Action, My_Value) values (" . $varDeviceId . ", '2015-01-01', 'Base Record' ,0)";
            };
            if ($varValue == "1SHOT") {
                // Current date/time in server computer's time zone.
                echo $date->format('Y-m-d H:i:sP') . "\n";
                exit();
            };
        };  

        echo "<p>" . $varSql;
        echo "<p>";

        if (!$mysqli->query($varSql)) {
            echo "Query failed: (" . $mysqli->errno . ") " . $mysqli->error;
            echo "<p>";
            exit();
        }
            
        $mysqli->real_query("SELECT My_DeviceId, My_TimeStamp, My_Action, My_Value FROM jt_table2 ORDER BY My_TimeStamp ASC");
        $res = $mysqli->use_result();

        echo "Result set: Device ID, Timestamp, Action, Value";
        while ($row = $res->fetch_assoc()) {
            echo "<p> " . $row['My_DeviceId'] . " " . $row['My_TimeStamp'] . " " . $row['My_Action'] . " " . $row['My_Value'] ;
        }

		exit();
	}
    echo "At end of php processing.  V105d<p>";

    echo "formValue = " . $varValue . " formIndex='" . $varIndex . "'<p>";

?>
<!DOCTYPE html>
<html>
<head>
	<title>PHP Form processing example</title>
<!-- define some style elements-->
<style>
label,a 
{
	font-family : Arial, Helvetica, sans-serif;
	font-size : 12px; 
}

</style>	
</head>

<body>
        Starting Body:
       <?php
            if(!empty($errorMessage)) 
		    {
			    echo("<p>There was an error with your form:</p>\n");
			    echo("<ul>" . $errorMessage . "</ul>\n");
            }
        ?>
            <table border="1">
       <?php
            $i=0;
            while($i<20){
                if($i%10==0){
                    if($i>1){
                        echo "</tr>".PHP_EOL;
                    }
                    echo "<tr>".PHP_EOL;
                }
                echo "<td>".$i."</td>".PHP_EOL;
                $i++;
            }
            echo "</tr>".PHP_EOL;
        ?>
        </table>

        <p>
        At the end now.
</body>
</html>

<?php
  function sanitizeString($var)    
  {
      $var = stripslashes($var);
      $var = strip_tags($var);
      $var = htmlentities($var);
      return $var;
  }

  function sanitizeMySQL($connection, $var)
  {
      $var = $connection->real_escape_string($var);
      $var = sanitizeString($var);
      return $var;
  }
?>