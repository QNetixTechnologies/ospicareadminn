<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$servername = "localhost";
$username = "root";
$password = 'Pa$$w0rd';
$dbname = "ospicare";

$conn = mysqli_connect($servername, $username, $password, $dbname);

$target_path = dirname(__FILE__).'/profilepix/';

$filename= $_FILES['file']['name'];

$result = array("success" => $_FILES["file"]["name"]);
$pp = basename( $_FILES['file']['name']);
$file_path = $target_path . basename( $_FILES['file']['name']);
if(move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
    $result = array("success" => "File successfully uploaded");
} else{
    $result = array("success" => "error uploading file");
}

$names = $_REQUEST["names"];
$phonenumber = $_REQUEST["phonenumber"];
$email = $_REQUEST["email"];
$password = $_REQUEST["password"];
$isdoctor = $_REQUEST["isdoctor"];


$query = "select * from user_table where phone_number = '$phonenumber'";
$result = mysqli_query($conn, $query);

$output = array();

if(mysqli_num_rows($result) > 0){
    $output['error'] = TRUE;
    $output['message'] = "Phone Number Already Exist";
}else{
    
    
    
    $queryInsert = "insert into user_table (full_names, phone_number, email, password, image_path, is_doctor) values('$names', '$phonenumber', '$email', '$password', '$pp', '$isdoctor')";
    $resultInsert = mysqli_query($conn, $queryInsert);
    
   if ($resultInsert){
        
        $query = "select id from user_table where phone_number = '$phonenumber'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $userID = $row['id'];
        
        $output['error'] = FALSE;
        $output['message'] = "successful";
        $output['userid'] = $userID;
        
    }else{
        $output['error'] = TRUE;
        $output['message'] = "Server Error...Please Try Again!!!";
    }
    
}


echo json_encode($output,JSON_UNESCAPED_SLASHES);



mysqli_close($conn);

?>
