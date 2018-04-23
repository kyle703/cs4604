<?php


//static/adhoc query:   argv = {priv=guest/admin, query=query}

//check if admin or guest to determine connect string
$priv = $GET_['priv']
if ($priv == 'admin') {
    $database = 'Young Sailors';
    $user = 'Young Sailors';
    $password = '123456';
} else {
    $database = 'Young Sailors';
    $user = 'Young Sailors';
    $password = '123456';
}


$connectString = ' dbname=' . $database . 
    ' user=' . $user . ' password=' . $password;


$link = pg_connect ($connectString);
if (!$link)
{
    die('Error: Could not connect: ' . pg_last_error());
}

$query = $GET_['query'];
$name = $GET_['name'];
$name = pg_escape_literal($name); 
$query = str_replace('{name}', $name, $query);

$result = pg_query($query);

$i = 0;
echo '<html><body><table><tr>';
while ($i < pg_num_fields($result))
{
    $fieldName = pg_field_name($result, $i);
    echo '<td>' . $fieldName . '</td>';
    $i = $i + 1;
}
echo '</tr>';
$i = 0;

while ($row = pg_fetch_row($result)) 
{
    echo '<tr>';
    $count = count($row);
    $y = 0;
    while ($y < $count)
    {
        $c_row = current($row);
        echo '<td>' . $c_row . '</td>';
        next($row);
        $y = $y + 1;
    }
    echo '</tr>';
    $i = $i + 1;
}
pg_free_result($result);

echo '</table></body></html>';
?>
