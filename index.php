<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedCare | Hospital Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="header">
        <div class="logo">
            <div class="logo-icon">🏥</div>
            <div>
                <h1>MedCare Hospital</h1>
                <p>Complete Patient Management System</p>
            </div>
        </div>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <button class="btn btn-primary" onclick="openModal('addPatientModal')">➕ New Patient</button>
            <button class="btn btn-success" onclick="openModal('addAppointmentModal')">📅 Book Appointment</button>
            <button class="btn btn-outline" onclick="window.location.href='reports.php'">📊 Reports</button>
        </div>
    </div>

    <?php
    $patient_count = $conn->query("SELECT COUNT(*) as count FROM Patient")->fetch_assoc()['count'];
    $doctor_count = $conn->query("SELECT COUNT(*) as count FROM Doctor")->fetch_assoc()['count'];
    $appointment_count = $conn->query("SELECT COUNT(*) as count FROM Appointment")->fetch_assoc()['count'];
    $today_appointments = $conn->query("SELECT COUNT(*) as count FROM Appointment WHERE DATE(appt_date) = CURDATE()")->fetch_assoc()['count'];
    $pending_bills = $conn->query("SELECT COUNT(*) as count FROM Billing WHERE payment_status='Pending'")->fetch_assoc()['count'];
    ?>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card"><div class="stat-icon">👥</div><div class="stat-number"><?php echo $patient_count; ?></div><div class="stat-label">Total Patients</div></div>
        <div class="stat-card"><div class="stat-icon">👨‍⚕️</div><div class="stat-number"><?php echo $doctor_count; ?></div><div class="stat-label">Medical Experts</div></div>
        <div class="stat-card"><div class="stat-icon">📅</div><div class="stat-number"><?php echo $appointment_count; ?></div><div class="stat-label">Total Appointments</div></div>
        <div class="stat-card"><div class="stat-icon">⏰</div><div class="stat-number"><?php echo $today_appointments; ?></div><div class="stat-label">Today's Appointments</div></div>
    </div>

    <!-- Search -->
    <div class="search-bar"><span>🔍</span><input type="text" id="searchInput" placeholder="Search patients, doctors, or appointments..." onkeyup="searchTable()"></div>

    <!-- Patients -->
    <div class="data-card">
        <div class="card-header"><h2>📋 Registered Patients</h2><span class="badge-count"><?php echo $patient_count; ?> total</span></div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr><th>ID</th><th>Name</th><th>Age</th><th>Gender</th><th>Phone</th><th>Blood Group</th><th>Medical History</th><th></th></tr>
                </thead>
                <tbody>
                    <?php $r = $conn->query("SELECT * FROM Patient ORDER BY patient_id DESC"); while($row = $r->fetch_assoc()): ?>
                    <tr>
                        <td><strong>#<?php echo $row['patient_id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo $row['age']; ?></td>
                        <td><?php echo $row['gender']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><span class="blood-badge"><?php echo $row['blood_group']; ?></span></td>
                        <td><small><?php echo htmlspecialchars(substr($row['medical_history'] ?: 'None', 0, 30)); ?></small></td>
                        <td class="action-buttons"><button class="icon-btn btn-delete" onclick="deleteRecord('Patient', <?php echo $row['patient_id']; ?>)">🗑️</button></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Doctors -->
    <div class="data-card">
        <div class="card-header"><h2>👨‍⚕️ Our Medical Team</h2><span class="badge-count"><?php echo $doctor_count; ?> specialists</span></div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr><th>ID</th><th>Doctor Name</th><th>Specialization</th><th>Phone</th><th>Email</th><th>Fee</th></tr>
                </thead>
                <tbody>
                    <?php $r = $conn->query("SELECT * FROM Doctor"); while($row = $r->fetch_assoc()): ?>
                    <tr>
                        <td><strong>#<?php echo $row['doctor_id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['specialization']); ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><small><?php echo htmlspecialchars($row['email']); ?></small></td>
                        <td class="amount">₹<?php echo number_format($row['consultation_fee'], 0); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Appointments -->
    <div class="data-card">
        <div class="card-header"><h2>📅 Appointment Schedule</h2><span class="badge-count">Upcoming: <?php echo $conn->query("SELECT COUNT(*) as count FROM Appointment WHERE status='Scheduled' AND DATE(appt_date) >= CURDATE()")->fetch_assoc()['count']; ?></span></div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr><th>ID</th><th>Patient</th><th>Doctor</th><th>Date & Time</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    <?php $sql = "SELECT a.appt_id, p.name as patient_name, d.name as doctor_name, a.appt_date, a.status FROM Appointment a JOIN Patient p ON a.patient_id = p.patient_id JOIN Doctor d ON a.doctor_id = d.doctor_id ORDER BY a.appt_date ASC"; $r = $conn->query($sql); while($row = $r->fetch_assoc()): ?>
                    <tr>
                        <td><strong>#<?php echo $row['appt_id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                        <td><?php echo date('d M Y, h:i A', strtotime($row['appt_date'])); ?></td>
                        <td>
                            <select class="status-select" onchange="updateStatus(<?php echo $row['appt_id']; ?>, this.value)">
                                <option value="Scheduled" <?php echo $row['status']=='Scheduled'?'selected':''; ?>>📋 Scheduled</option>
                                <option value="Completed" <?php echo $row['status']=='Completed'?'selected':''; ?>>✅ Completed</option>
                                <option value="Cancelled" <?php echo $row['status']=='Cancelled'?'selected':''; ?>>❌ Cancelled</option>
                            </select>
                        </td>
                        <td class="action-buttons"><button class="icon-btn btn-delete" onclick="deleteRecord('Appointment', <?php echo $row['appt_id']; ?>)">🗑️</button></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Billing -->
    <div class="data-card">
        <div class="card-header"><h2>💰 Billing Records</h2><span class="badge-count">Pending: <?php echo $pending_bills; ?></span></div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr><th>Bill ID</th><th>Patient Name</th><th>Amount</th><th>Status</th><th>Date</th></tr>
                </thead>
                <tbody>
                    <?php $sql = "SELECT b.bill_id, p.name as patient_name, b.amount, b.payment_status, b.bill_date FROM Billing b JOIN Patient p ON b.patient_id = p.patient_id ORDER BY b.bill_date DESC"; $r = $conn->query($sql); while($row = $r->fetch_assoc()): ?>
                    <tr>
                        <td><strong>#<?php echo $row['bill_id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                        <td class="amount">₹<?php echo number_format($row['amount'], 2); ?></td>
                        <td><span class="<?php echo $row['payment_status']=='Paid'?'payment-paid':'payment-pending'; ?>"><?php echo $row['payment_status']; ?></span></td>
                        <td><?php echo date('d M Y', strtotime($row['bill_date'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modals -->
<div id="addPatientModal" class="modal">
    <div class="modal-content">
        <div class="modal-header"><h3>➕ Register New Patient</h3></div>
        <form id="addPatientForm">
            <div class="modal-body">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="number" name="age" placeholder="Age" required>
                <select name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                    <option value="O">Other</option>
                </select>
                <input type="tel" name="phone" placeholder="Phone Number" required>
                <textarea name="address" placeholder="Address" rows="2"></textarea>
                <input type="text" name="blood_group" placeholder="Blood Group (e.g., O+)" required>
                <textarea name="medical_history" placeholder="Medical History" rows="2"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('addPatientModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Register Patient</button>
            </div>
        </form>
    </div>
</div>

<div id="addAppointmentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header"><h3>📅 Schedule Appointment</h3></div>
        <form id="addAppointmentForm">
            <div class="modal-body">
                <select name="patient_id" required>
                    <option value="">Select Patient</option>
                    <?php $pats = $conn->query("SELECT patient_id, name FROM Patient"); while($p = $pats->fetch_assoc()): ?>
                    <option value="<?php echo $p['patient_id']; ?>"><?php echo htmlspecialchars($p['name']); ?></option>
                    <?php endwhile; ?>
                </select>
                <select name="doctor_id" required>
                    <option value="">Select Doctor</option>
                    <?php $docs = $conn->query("SELECT doctor_id, name, specialization FROM Doctor"); while($d = $docs->fetch_assoc()): ?>
                    <option value="<?php echo $d['doctor_id']; ?>"><?php echo htmlspecialchars($d['name']) . " (" . $d['specialization'] . ")"; ?></option>
                    <?php endwhile; ?>
                </select>
                <input type="datetime-local" name="appt_date" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('addAppointmentModal')">Cancel</button>
                <button type="submit" class="btn btn-success">Book Appointment</button>
            </div>
        </form>
    </div>
</div>

<div id="toast" class="toast"></div>

<script src="script.js"></script>
</body>
</html>