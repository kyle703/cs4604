<?php

    $database = "dbname='young sailors'";
    $user = "user='young sailors'";
    $password = "password=123456";
  $dbconnect = pg_connect ("$database $user $password");

  $title = "Adhoc Query";
  echo "<h1>Adhoc Query</h1>";
  echo "<h4>{$_REQUEST['query']}</h4>";
  //Specify a query for the database.
  $query = $_REQUEST['query'];
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
