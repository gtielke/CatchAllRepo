<?php
  function get_products()
  {
      echo "<p>Test Version 103";
      $conn = mysqli_connect("us-cdbr-azure-southcentral-e.cloudapp.net", "bf877c2bb3f600", "8a053857", "JackaloAlHF0dmbv");
      if(!$conn)
      {
          die('cannot connect to server');
      }
      $products=array();

      $query="SELECT My_DeviceId, My_TimeStamp, My_Action, My_Value FROM jt_table2 ORDER BY My_TimeStamp ASC";
      
      $rs=$conn->query($query);
      if($rs === false) {
          trigger_error('Wrong SQL: ' . $query . ' Error: ' . $conn->error, E_USER_ERROR);
      } else {
          $rs->data_seek(0);
          $products = $rs->fetch_all(MYSQLI_ASSOC);
      }

      mysql_close($conn);
      //echo "<p>FINISHED WITH GET_PRODUCTS:";
      return $products;
  }
  function get_table()
  {
     //create table in HTML
     $table_str='<table id=product_table>';
     $products=get_products();
     $i=1;
     $table_str.='<tr class="head_table">';
     $table_str.='<th>DeviceId</th><th>TimeStamp</th><th>Action</th><th>Value</th>';
     $table_str.='</tr>';
     foreach($products as $product)
     {
         $class='';
         if($i%2==0)
         {
             $class='row_even';
         }
         else
         {
             $class='row_odd';
         }
         $table_str.='<tr class="'.$class.'">';
         $table_str.='<td width=30>'.$product['My_DeviceId'].'</td><td>'.$product['My_TimeStamp'].'</td><td>'.$product['My_Action'].'</td><td>'.$product['My_Value'].'</td>';
         $table_str.='</tr>';
         $i++;
     } 
     $table_str.='</table>';
     return $table_str;
  }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Demo PHP Table to HTML</title>
        <style type="text/css">
            #product_table
            {
                border:1px solid gray;
                border-collapse: collapse;
            }
            #product_table td,th
            {
                border: 1px solid gray;
            }
            .head_table
            {
                background-color: black;
                color: white;
            }
            .row_even
            {
                background-color: #0ff;
            }
            .row_odd
            {
                background-color: #d5bbbb;
            }
        </style>
    </head>
    <body>
        <?php echo get_table(); ?>
    </body>
</html>
