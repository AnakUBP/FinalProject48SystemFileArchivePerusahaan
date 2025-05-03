    function goBack() {
        // Kembali ke halaman sebelumnya
        window.history.back();
    }
    
    function handleSubmit(event) {
        event.preventDefault();
    
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
    
        if (newPassword !== confirmPassword) {
        alert('Passwords do not match!');
        return false;
        }
    
        alert('Password has been successfully reset!');
        // Tambahkan logika kirim ke backend di sini
        return true;
    }
    