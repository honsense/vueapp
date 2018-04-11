<?php
//select.php
$connect = mysqli_connect("localhost", "root", "", "requestdb");
$output = array();
if(isset($_GET['a']))
{
    $nerp = mysqli_real_escape_string($connect, $_GET['a']);
    $query = "SELECT * FROM requests where Id = '$nerp'";
}
else
{
    $query = "SELECT * FROM requests";    
}

$result = mysqli_query($connect, $query);

if(mysqli_num_rows($result) > 0)
{
    while($row = mysqli_fetch_array($result))
    {
        $output[] = $row;
    }
    echo json_encode($output);
}

?>