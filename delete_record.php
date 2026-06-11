<?php
include 'config.php';

$table = $_GET['table'];
$id = $_GET['id'];

$id_column = '';
switch($table) {
    case 'Patient': $id_column = 'patient_id'; break;
    case 'Doctor': $id_column = 'doctor_id'; break;
    case 'Appointment': $id_column = 'appt_id'; break;
    case 'Billing': $id_column = 'bill_id'; break;
}

if ($id_column) {
    $conn->query("DELETE FROM $table WHERE $id_column = $id");
}

header('Location: index.php');