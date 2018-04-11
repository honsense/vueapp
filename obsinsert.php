<?php
//insert.php

$connect = mysqli_connect("localhost", "root", "", "requestdb");
$data = json_decode(file_get_contents("php://input"));
if(count($data) > 0)
{
    $ref = mysqli_real_escape_string($connect, $data->reference);
    $obs = mysqli_real_escape_string($connect, $data->observation);
    $action = mysqli_real_escape_string($connect, $data->action);

    $query = "INSERT INTO observations(REQUEST_ID, REFERENCE, OBSERVATION, ACTIONS) VALUES ((SELECT Id FROM requests WHERE REFERENCE = '$ref'), '$ref', '$obs', '$action')";
    if(mysqli_query($connect, $query))
    {
        echo "Data Inserted...";
    }
    else
    {
        echo "$ref, $obs, $action";
    }
}
?>