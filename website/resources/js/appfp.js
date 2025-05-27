document.getElementById("forgot-form").addEventListener("submit", function(e) {
    e.preventDefault();

    const email = document.querySelector('#forgot-form input[type="email"]').value;

    fetch("http://localhost:8000/api/forgot-password", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: JSON.stringify({ email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success" || data.message.includes("We have emailed")) {
            alert("Link reset password telah dikirim ke email Anda.");
            window.location.href = "checkyour_email.php";
        } else {
            alert("Gagal mengirim link reset password. " + (data.message || "Silakan coba lagi."));
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Terjadi kesalahan saat mengirim permintaan.");
    });
});

function goBack() {
    window.history.back(); // atau arahkan langsung: window.location.href = "welcome.blade.php";
}
