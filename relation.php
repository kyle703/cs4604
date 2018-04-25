<?php

    $database = "dbname='young sailors'";
    $user = "user='young sailors'";
    $password = "password=123456";
    $dbconnect = pg_connect ("$database $user $password");

    $queryNumber = $_REQUEST['title'][strlen($_REQUEST['title']) - 1];
    $title = " | Relation #".$queryNumber;

    echo "<h1>Query #{$queryNumber}</h1>";
    $requestedQuery = $_REQUEST['title'];
    $query = "";
    switch ($requestedQuery) {
        case 'Relation1':
            $query = "SELECT P.name,  count(P.name)
                        FROM administers A, person P
                        WHERE P.pid = A.pid
                        GROUP BY P.name;";
            break;
        case 'Relation2':
            $query = "SELECT C2.*
                        FROM configuration_items C1, configuration_items C2,
                        dependent_on D
                        WHERE C1.name = 'super server' AND C1.cid = D.super
                        AND C2.cid = D.child;";
            break;
        case 'Relation3':
            $query = "SELECT dep.name, dep.last_modified, P.name
                        FROM person P, administers A, 
                          (
                          SELECT C2.*
                          FROM configuration_items C1, configuration_items C2,
                          dependent_on D
                          WHERE C1.name = 'down-app' AND C1.cid = D.child
                          AND C2.cid = D.super 
                          ) as dep
                        WHERE A.pid = P.pid AND A.cid = dep.cid;";
            break;
        case 'Relation4':
            $query = "SELECT * 
                        FROM configuration_items
                        ORDER BY type DESC;";
            break;

        case 'Relation5':
        $query = "SELECT action_tstamp, original_data, new_data
                from audit.logged_actions
                where table_name = 'configuration_items'
                order by action_tstamp DESC;";
        break;
        
        default:
            break;
    }
    
    if ($query != '') {
		echo "<h3>{$query}</h3>";
        //Get the result of the query
        $result = pg_query($dbconnect, $query);
        //And record the number of fields from the query.
        $numberOfFields = pg_num_fields($result);
        
        //Print the table header with the field names.  Use bold font.
        echo '<table border=1 align="center" class="table table-striped table-bordered">';
        echo '<tr>';
            for ($i = 0; $i < $numberOfFields; $i++) {
                $fieldName = pg_field_name($result, $i);
                $fieldType = pg_field_type($result, $i);
                echo '<th>'.$fieldName.' ('.$fieldType.')</th>';
            }
        echo '</tr>';
        
        //Then, print all the results of the query.
        while ($array = pg_fetch_array($result, NULL, PGSQL_NUM)) {
            echo '<tr>';
            foreach ($array as $elem) {
                echo '<td>'.$elem.'</td>';
            }
            echo '</tr>';   
        }
        echo '</table>';
    }
                
    //Close the database when done.
    pg_close($dbconnect);
?>
