<?php

    $database = "dbname='young sailors'";
    $user = "user='young sailors'";
    $password = "password=123456";
  $dbconnect = pg_connect ("$database $user $password");

  $title = "Query 1";
  echo "<h1>Query 1</h1>";
  echo "<h4>Items dependent on {$_REQUEST['config']}</h4>";
  
  $config = $_REQUEST['config'];

  //Specify a query for the database.
  $query = "SELECT C2.*
            FROM configuration_items C1, configuration_items C2,
            dependent_on D
            WHERE C1.name = '{$config}' AND C1.cid = D.super
            AND C2.cid = D.child;";
  //Get the result of the query
  $result = pg_query($dbconnect, $query);
  
  //And record the number of fields from the query.
  $numberOfFields = pg_num_fields($result);
  
  //Print the table header with the field names.  Use bold font.
  echo "<table align=\"center\" class=\"table table-striped table-bordered\">";
  echo "<tr>";
    for ($i=0; $i<$numberOfFields; $i++) {
      $fieldName = pg_field_name($result, $i);
      $fieldType = pg_field_type($result, $i);
      echo "<th>{$fieldName} ({$fieldType})</th>";
    }
  echo "</tr>";
  
  //Then, print all the results of the query.
  while($array = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
    echo '<tr>';
    foreach($array as $elem) {
      echo '<td>'.$elem.'</td>';
    }
    echo '</tr>'; 
  }
  echo '</table>';
  //Close the database when done.
  pg_close($dbconnect);
?>
