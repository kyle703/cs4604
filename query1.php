<?php



//check if admin or guest to determine connect string

$database = "dbname='young sailors'";
$user = "user='young sailors'";
$password = "password=123456";


$link = pg_connect ("$database $user $password");
if (!$link)
{
    die('Error: Could not connect: ' . pg_last_error());
}

$query = "SELECT P.name,  count(P.name)
FROM ps_administered_by PS, vs_administered_by VS, person P
WHERE P.pid = PS.hid OR P.pid = VS.pid
GROUP BY P.name;";

$result = pg_query($query) or die('query failed:'.pg_last_error());

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
