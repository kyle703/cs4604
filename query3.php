<?php

    $database = "dbname='young sailors'";
    $user = "user='young sailors'";
    $password = "password=123456";
  $dbconnect = pg_connect ("$database $user $password");

  $title = "Query 3";
  echo "<h1>Query 2</h1>";
  echo "<h3>Updating Admin on {$_REQUEST['config']} to {$_REQUEST['admin']}</h3>";
  
  $config = $_REQUEST['config'];
  $admin = $_REQUEST['admin'];
  
  $admin = pg_escape_string($admin);
  $config = pg_escape_string($config);

  if($_REQUEST['id'] != NULL) {

    if($_REQUEST['admin-id'] != NULL) {
       //-- admin id and ci id
        $query = "UPDATE administers
                SET pid = P.pid
                from Person P, configuration_items C                     
                WHERE administers.cid = '{$config}'
                AND P.pid = '{$admin}';";
    } else {
        //-- admin name and ci id
        $query = "UPDATE administers
                SET pid = P.pid
                from Person P                 
                WHERE P.name = '{$admin}' AND
                '{$config}' = administers.cid;";
    }
  
  } else {
      if($_REQUEST['admin-id'] != NULL) {
          //-- admin id and ci name
      $query = "UPDATE administers
                SET pid = P.pid
                from configuration_items C, Person P                     
                WHERE C.cid = administers.cid and C.name = '{$config}'
                AND P.pid = '{$admin}';";
    } else {
        //-- admin name and ci name
        $query = "UPDATE administers
                SET pid = P.pid
                from Person P, configuration_items C                     
                WHERE P.name = '{$admin}' AND
                C.cid = administers.cid and C.name = '{$config}';";
    }
  }
  
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
