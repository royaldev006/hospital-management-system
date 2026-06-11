// Toast Notification
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    if (!toast) return;
    
    toast.textContent = message;
    toast.style.display = 'block';
    toast.style.background = type === 'success' ? '#10b981' : '#ef4444';
    
    setTimeout(() => {
        toast.style.display = 'none';
    }, 3000);
}

// Open Modal
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
    }
}

// Close Modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}

// Search functionality
function searchTable() {
    const input = document.getElementById('searchInput');
    if (!input) return;
    
    const filter = input.value.toLowerCase();
    const tables = document.querySelectorAll('table tbody');
    
    tables.forEach(tbody => {
        const rows = tbody.getElementsByTagName('tr');
        for (let i = 0; i < rows.length; i++) {
            const text = rows[i].innerText.toLowerCase();
            rows[i].style.display = text.includes(filter) ? '' : 'none';
        }
    });
}

// Delete record with confirmation
function deleteRecord(table, id) {
    if (confirm('⚠️ Are you sure you want to delete this record?\n\nThis action cannot be undone!')) {
        window.location.href = `delete_record.php?table=${table}&id=${id}`;
    }
}

// Update appointment status
function updateStatus(appointmentId, status) {
    fetch('update_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `appointment_id=${appointmentId}&status=${status}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('✅ Status updated successfully!', 'success');
            setTimeout(() => location.reload(), 800);
        } else {
            showToast('❌ Error updating status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('❌ Network error', 'error');
    });
}

// Add Patient Form Submission
document.addEventListener('DOMContentLoaded', function() {
    const addPatientForm = document.getElementById('addPatientForm');
    if (addPatientForm) {
        addPatientForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('add_patient.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('✅ Patient registered successfully!', 'success');
                    closeModal('addPatientModal');
                    setTimeout(() => location.reload(), 800);
                } else {
                    showToast('❌ Error: ' + data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('❌ Network error', 'error');
            });
        });
    }
    
    // Add Appointment Form Submission
    const addAppointmentForm = document.getElementById('addAppointmentForm');
    if (addAppointmentForm) {
        addAppointmentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('add_appointment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('✅ Appointment booked successfully!', 'success');
                    closeModal('addAppointmentModal');
                    setTimeout(() => location.reload(), 800);
                } else {
                    showToast('❌ Error: ' + data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('❌ Network error', 'error');
            });
        });
    }
});

// Keyboard shortcut: Ctrl + F for search
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'f') {
        e.preventDefault();
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.focus();
        }
    }
});

// Auto-refresh statistics every 30 seconds (optional)
function refreshStats() {
    fetch('get_stats.php')
        .then(response => response.json())
        .then(data => {
            const patientCount = document.getElementById('patientCount');
            const doctorCount = document.getElementById('doctorCount');
            const appointmentCount = document.getElementById('appointmentCount');
            const pendingBills = document.getElementById('pendingBills');
            
            if (patientCount) patientCount.textContent = data.patients;
            if (doctorCount) doctorCount.textContent = data.doctors;
            if (appointmentCount) appointmentCount.textContent = data.appointments;
            if (pendingBills) pendingBills.textContent = data.pending_bills;
        })
        .catch(error => console.error('Stats refresh error:', error));
}

// Uncomment to enable auto-refresh (every 30 seconds)
// setInterval(refreshStats, 30000);

// Print functionality (optional)
function printReport() {
    window.print();
}

// Export to CSV (optional)
function exportToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    const rows = table.querySelectorAll('tr');
    let csv = [];
    
    rows.forEach(row => {
        const cells = row.querySelectorAll('th, td');
        const rowData = Array.from(cells).map(cell => cell.innerText);
        csv.push(rowData.join(','));
    });
    
    const blob = new Blob([csv.join('\n')], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename + '.csv';
    a.click();
    URL.revokeObjectURL(url);
}