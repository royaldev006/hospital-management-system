<?php
include 'config.php';

$response = ['success' => false, 'error' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $blood_group = $_POST['blood_group'];
    $medical_history = $_POST['medical_history'];
    
    $sql = "INSERT INTO Patient (name, age, gender, phone, address, blood_group, medical_history) 
            VALUES ('$name', '$age', '$gender', '$phone', '$address', '$blood_group', '$medical_history')";
    
    if ($conn->query($sql)) {
        $response['success'] = true;
    } else {
        $response['error'] = $conn->error;
    }
}

header('Content-Type: application/json');
echo json_encode($response);