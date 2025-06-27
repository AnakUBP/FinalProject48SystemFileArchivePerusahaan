// Fungsi untuk membuka modal detail dan mengambil datanya
window.openDetailModal = async function(pengajuanId) {
    const modal = document.getElementById('detailRiwayatModal');
    const modalBody = document.getElementById('detailModalBody');
    if (!modal || !modalBody) return;

    // Tampilkan modal dengan pesan loading
    modalBody.innerHTML = '<p>Memuat detail...</p>';
    modal.style.display = 'block';

    try {
        // Panggil API untuk mendapatkan detail
        const response = await fetch(`/riwayat-surat/${pengajuanId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        });

        if (!response.ok) {
            throw new Error('Gagal memuat data riwayat.');
        }

        const data = await response.json();
        
        // Format tanggal agar mudah dibaca
        const formatDate = (dateStr) => dateStr ? new Date(dateStr).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' }) : '-';

        // Bangun HTML untuk ditampilkan di modal
        let detailHtml = `
            <div class="detail-grid">
                <div class="detail-item"><strong>Nama:</strong> <span>${data.user.name}</span></div>
                <div class="detail-item"><strong>Jenis Cuti:</strong> <span>${data.jenis_cuti?.nama || 'N/A'}</span></div>
                <div class="detail-item"><strong>Tanggal Mulai:</strong> <span>${formatDate(data.tanggal_mulai)}</span></div>
                <div class="detail-item"><strong>Tanggal Selesai:</strong> <span>${formatDate(data.tanggal_selesai)}</span></div>
                <div class="detail-item full-width"><strong>Alasan:</strong> <p>${data.alasan}</p></div>
                <hr class="full-width">
                <div class="detail-item"><strong>Status:</strong> <span>${data.surat_cuti_resmi?.status || 'Diajukan'}</span></div>
                <div class="detail-item"><strong>Diproses Oleh:</strong> <span>${data.surat_cuti_resmi?.approver?.name || '-'}</span></div>
                <div class="detail-item"><strong>Tanggal Proses:</strong> <span>${formatDate(data.surat_cuti_resmi?.approved_at)}</span></div>
                <div class="detail-item full-width"><strong>Catatan Admin:</strong> <p>${data.surat_cuti_resmi?.catatan_approval || '-'}</p></div>
            </div>
        `;
        
        // Tampilkan link unduhan jika ada
        if (data.surat_cuti_resmi?.file_hasil_path) {
            detailHtml += `<a href="/storage/${data.surat_cuti_resmi.file_hasil_path}" class="btn btn-primary mt-3" target="_blank">Unduh Surat</a>`;
        }

        modalBody.innerHTML = detailHtml;

    } catch (error) {
        modalBody.innerHTML = `<p class="text-danger">${error.message}</p>`;
    }
}

// Fungsi generik untuk menutup modal
window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Menutup modal jika user mengklik di luar area modal
window.onclick = function(event) {
    const modal = document.getElementById('detailRiwayatModal');
    if (event.target == modal) {
        closeModal('detailRiwayatModal');
    }
}
