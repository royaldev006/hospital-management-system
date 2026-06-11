<?php
include 'config.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];
    
    $sql = "UPDATE Appointment SET status = '$status' WHERE appt_id = '$appointment_id'";
    
    if ($conn->query($sql)) {
        $response['success'] = true;
        
        if ($status == 'Completed') {
            $check = $conn->query("SELECT * FROM Billing WHERE appt_id = '$appointment_id'");
            if ($check->num_rows == 0) {
                $appt = $conn->query("SELECT patient_id, doctor_id FROM Appointment WHERE appt_id = '$appointment_id'")->fetch_assoc();
                $doctor = $conn->query("SELECT consultation_fee FROM Doctor WHERE doctor_id = '{$appt['doctor_id']}'")->fetch_assoc();
                $amount = $doctor['consultation_fee'];
                
                $conn->query("INSERT INTO Billing (patient_id, appt_id, amount) VALUES ('{$appt['patient_id']}', '$appointment_id', '$amount')");
            }
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);