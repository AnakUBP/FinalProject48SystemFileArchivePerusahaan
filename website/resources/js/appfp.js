    document.getElementById("forgot-form").addEventListener("submit", function(e) {
        e.preventDefault();
        alert("Password reset link has been sent (simulasi)!");
    });
    
    function goBack() {
        window.history.back(); // atau arahkan ke halaman login: window.location.href = "login.html";
    }
    