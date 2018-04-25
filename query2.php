<?php

    $database = "dbname='young sailors'";
    $user = "user='young sailors'";
    $password = "password=123456";
  $dbconnect = pg_connect ("$database $user $password");

  $title = "Query 2";
  echo "<h1>Query 2</h1>";
  echo "<h3>Items {$_REQUEST['config']} is dependent on</h3>";
  
  $config = $_REQUEST['config'];
  
  $config = pg_escape_string($config);

  if($_REQUEST['id'] != NULL) {
      //Specify a query for the database.
  $query = "SELECT C2.*
            FROM configuration_items C1, configuration_items C2,
            dependent_on D
            WHERE C1.cid = '{$config}' AND C1.cid = D.child
            AND C2.cid = D.super;";
  
  } else {
          //Specify a query for the database.
  $query = "SELECT C2.*
            FROM configuration_items C1, configuration_items C2,
            dependent_on D
            WHERE C1.name = '{$config}' AND C1.cid = D.child
            AND C2.cid = D.super;";
  }
  
  //Get the result of the query
  $result = pg_query($dbconnect, $query);
  
  //And record the number of fields from the query.
  $numberOfFields = pg_num_fields($result);
  
  //Print the table header with the field names.  Use bold font.
  echo "<table border=1 align=\"center\" class=\"table table-striped table-bordered\">";
  echo "<tr>";
    for ($i=0; $i<$numberOfFields; $i++) {
      $fieldName = pg_field_name($result, $i);
      $fieldType = pg_field_type($result, $i);
      echo "<th>{$fieldName} ({$fieldType})</th>";
    }
  echo "</tr>";
  
  //Then, print all the results of the query.
  while($array = pg_fetch_array($result, NULL, PGSQL_NUM)) {
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
