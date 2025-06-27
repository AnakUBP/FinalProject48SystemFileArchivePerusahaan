<?php

updateReturnDateTime() {
    // 1. Buat object Date dari waktu pickup
    const pickupDate = new Date(this.pickupDateTime);

    // 2. Hitung durasi sewa dalam milidetik
    const durationInMs = this.rentalDurationInHours * 60 * 60 * 1000;

    // 3. Buat tanggal kembali awal dengan menambahkan durasi
    // Pada titik ini, tanggalnya sudah benar, tapi jamnya mungkin belum sesuai
    const newReturnDate = new Date(pickupDate.getTime() + durationInMs);

    // 4. INILAH LOGIKA KUNCINYA:
    // Ambil jam dan menit dari tanggal pickup, lalu set ke tanggal kembali.
    // Metode .setHours() bekerja dalam zona waktu LOKAL browser,
    // sehingga secara otomatis menangani konversi UTC dengan benar.
    newReturnDate.setHours(
      pickupDate.getHours(),      // Ambil jam dari pickupDate (misal: 14)
      pickupDate.getMinutes(),    // Ambil menit dari pickupDate (misal: 00)
      0,                          // Set detik ke 0
      0                           // Set milidetik ke 0
    );

    // 5. Setelah jamnya disesuaikan, simpan hasilnya
    this.returnDateTime = newReturnDate.toISOString();

    // 6. Perbarui juga properti untuk tampilan di layar
    this.displayReturnDate = newReturnDate.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
    this.displayReturnTime = newReturnDate.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', hour12: false });
}

?>