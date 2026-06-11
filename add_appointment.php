<?php
include 'config.php';

$response = ['success' => false, 'error' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $appt_date = $_POST['appt_date'];
    $status = 'Scheduled';
    
    $sql = "INSERT INTO Appointment (patient_id, doctor_id, appt_date, status) 
            VALUES ('$patient_id', '$doctor_id', '$appt_date', '$status')";
    
    if ($conn->query($sql)) {
        $response['success'] = true;
    } else {
        $response['error'] = $conn->error;
    }
}

header('Content-Type: application/json');
echo json_encode($response);