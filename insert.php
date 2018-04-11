<?php
//insert.php

$connect = mysqli_connect("localhost", "root", "", "requestdb");
$data = json_decode(file_get_contents("php://input"));

if(count($data) > 0)
{
    $mode = mysqli_real_escape_string($connect, $data->mode);
    //$Id = mysqli_real_escape_string($connect, $data->Id);
    $ref = mysqli_real_escape_string($connect, $data->reference);
    $requester = mysqli_real_escape_string($connect, $data->requester);
    $comments = mysqli_real_escape_string($connect, $data->comments);

if($mode == 'add')
{
    $query = "INSERT INTO requests(REFERENCE, REQUESTER, COMMENTS) VALUES ('$ref', '$requester', '$comments')";
}

elseif($mode == 'update')
{
    $query = "UPDATE requests SET REFERENCE='$ref', REQUESTER='$requester', COMMENTS='$comments' WHERE Id = '$Id'";
}

    if(mysqli_query($connect, $query))
    {
        echo "Data Inserted...";
    }
    else
    {
        echo $mode;
    }
}

?>