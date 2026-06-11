<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - MedCare Hospital</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="header">
        <div class="logo"><div class="logo-icon">📊</div><div><h1>System Reports</h1><p>Analytics & Insights</p></div></div>
        <button class="btn btn-outline" onclick="window.location.href='index.php'">← Back to Dashboard</button>
    </div>

    <div class="data-card">
        <div class="card-header"><h2>💰 Revenue by Doctor</h2></div>
        <div class="table-responsive">
            <table><thead><tr><th>Doctor Name</th><th>Appointments</th><th>Total Revenue</th></tr></thead>
            <tbody>
            <?php
            $sql = "SELECT d.name, COUNT(a.appt_id) as visits, COALESCE(SUM(b.amount),0) as revenue 
                    FROM Doctor d 
                    LEFT JOIN Appointment a ON d.doctor_id = a.doctor_id AND a.status='Completed' 
                    LEFT JOIN Billing b ON a.appt_id = b.appt_id 
                    GROUP BY d.doctor_id";
            $r = $conn->query($sql);
            while($row = $r->fetch_assoc()):
            ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                <td><?php echo $row['visits']; ?></td>
                <td class="amount">₹<?php echo number_format($row['revenue'], 2); ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    </div>

    <div class="data-card">
        <div class="card-header"><h2>📅 Monthly Appointments</h2></div>
        <div class="table-responsive">
            <table><thead><tr><th>Month</th><th>Total</th><th>Completed</th><th>Cancelled</th></tr></thead>
            <tbody>
            <?php
            $sql = "SELECT DATE_FORMAT(appt_date, '%M %Y') as month, 
                           COUNT(*) as total,
                           SUM(CASE WHEN status='Completed' THEN 1 ELSE 0 END) as completed,
                           SUM(CASE WHEN status='Cancelled' THEN 1 ELSE 0 END) as cancelled 
                    FROM Appointment 
                    GROUP BY YEAR(appt_date), MONTH(appt_date) 
                    ORDER BY YEAR(appt_date) DESC, MONTH(appt_date) DESC";
            $r = $conn->query($sql);
            while($row = $r->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $row['month']; ?></td>
                <td><?php echo $row['total']; ?></td>
                <td><?php echo $row['completed']; ?></td>
                <td><?php echo $row['cancelled']; ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
</body>
</html>