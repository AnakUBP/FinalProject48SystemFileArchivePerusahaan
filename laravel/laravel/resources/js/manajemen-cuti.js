// Jalankan kode hanya setelah seluruh halaman HTML selesai dimuat
document.addEventListener('DOMContentLoaded', () => {

    console.log('[OK] Script manajemen-cuti.js telah dimuat.');

    // --- Definisi Elemen Global ---
    const tabsContainer = document.querySelector('.cuti-nav-tabs');
    const contentPanes = document.querySelectorAll('.cuti-content-pane');
    const createModal = document.getElementById('createCutiModal');
    const prosesModal = document.getElementById('prosesPengajuanModal');
    
    // --- Logika untuk Tanda Tangan ---
    const canvas = document.getElementById('signature-pad');
    const clearButton = document.getElementById('clear-signature');
    const signatureForm = document.getElementById('createCutiForm');
    const signatureInput = document.getElementById('tanda_tangan_input');
    let signaturePad; // Definisikan di sini agar bisa diakses di seluruh scope

    if (canvas && clearButton && signatureForm && signatureInput) {
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)'
        });

        function resizeCanvas() {
            const ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }
        
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        clearButton.addEventListener('click', (event) => {
            event.preventDefault();
            signaturePad.clear();
        });

        signatureForm.addEventListener('submit', (event) => {
            // Cek apakah canvas tanda tangan kosong
            if (signaturePad.isEmpty()) {
                // Hentikan pengiriman form
                event.preventDefault(); 
                // Beri peringatan kepada pengguna
                alert('Tanda tangan wajib diisi sebelum mengirim pengajuan.');
                return; // Hentikan eksekusi lebih lanjut
            }
            
            // Jika tidak kosong, baru simpan data ke input tersembunyi
            signatureInput.value = signaturePad.toDataURL('image/png');
        });
    }
    
    // --- LOGIKA UNTUK TABulasi ---
    if (tabsContainer) {
        tabsContainer.addEventListener('click', (event) => {
            const clickedButton = event.target.closest('.tab-button');
            if (!clickedButton) return;

            tabsContainer.querySelectorAll('.tab-button').forEach(button => button.classList.remove('active'));
            contentPanes.forEach(pane => pane.classList.remove('active'));

            clickedButton.classList.add('active');
            const paneIdToShow = clickedButton.dataset.pane;
            const selectedPane = document.getElementById(paneIdToShow);
            if (selectedPane) {
                selectedPane.classList.add('active');
            }
        });
    }

    // --- LOGIKA UNTUK MODAL ---
    window.openCreateModal = function() {
        if (createModal) {
            const form = document.getElementById('createCutiForm');
            if(form) form.reset();
            // Bersihkan canvas tanda tangan saat modal dibuka
            if (signaturePad) {
                signaturePad.clear();
            }
            createModal.style.display = 'block';
        }
    }
    
    window.openProsesModal = async function(pengajuanId) {
        if (!prosesModal) return;

        const modalBody = document.getElementById('prosesModalBody');
        const prosesForm = document.getElementById('prosesForm');
        
        modalBody.innerHTML = '<p>Memuat detail...</p>';
        prosesModal.style.display = 'block';

        try {
            const response = await fetch(`/pengajuan-cuti/${pengajuanId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            if (!response.ok) {
                throw new Error(`Server merespons dengan status ${response.status}`);
            }

            const pengajuan = await response.json();
            
            const formatDate = (dateString) => new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }).format(new Date(dateString));

            // --- PERSIAPAN HTML DINAMIS UNTUK FILE ---

            // 1. Buat link untuk Draf Surat
            let drafSuratHtml = '';
            if (pengajuan.surat_cuti_resmi && pengajuan.surat_cuti_resmi.file_hasil_path) {
                const fileUrl = `/storage/${pengajuan.surat_cuti_resmi.file_hasil_path}`;
                drafSuratHtml = `
                    <div class="detail-group">
                        <strong>Draf Surat:</strong>
                        <p>
                            <a href="${fileUrl}" target="_blank" class="file-download-link">
                                <i class="fas fa-file-word"></i> Lihat/Unduh Surat Draf
                            </a>
                        </p>
                    </div>
                `;
            }

            // 2. Buat tampilan untuk Tanda Tangan
            let tandaTanganHtml = '';
            if (pengajuan.tanda_tangan_path) {
                const signatureUrl = `/storage/${pengajuan.tanda_tangan_path}`;
                tandaTanganHtml = `
                    <div class="detail-group">
                        <strong>Tanda Tangan Pengaju:</strong>~
                        <p><img src="${signatureUrl}" alt="Tanda Tangan" class="signature-preview-modal" style="widht:20%; height:5%"></p>
                    </div>
                `;
            }
            
            // 3. Buat link untuk Lampiran
            let lampiranHtml = '';
            if (pengajuan.lampiran_path) {
                const lampiranUrl = `/storage/${pengajuan.lampiran_path}`;
                lampiranHtml = `
                    <div class="detail-group">
                        <strong>Lampiran Pendukung:</strong>
                        <p>
                            <a href="${lampiranUrl}" target="_blank" class="file-download-link">
                                <i class="fas fa-paperclip"></i> Lihat/Unduh Lampiran
                            </a>
                        </p>
                    </div>
                `;
            }

            // Gabungkan semua detail menjadi satu blok HTML
            modalBody.innerHTML = `
                <div class="detail-group">
                    <strong>Nama Karyawan:</strong>
                    <p>${pengajuan.user ? pengajuan.user.name : 'N/A'}</p>
                </div>
                <div class="detail-group">
                    <strong>Jenis Cuti:</strong>
                    <p>${pengajuan.jenis_cuti ? pengajuan.jenis_cuti.nama : 'Tidak diketahui'}</p>
                </div>
                <div class="detail-group">
                    <strong>Tanggal:</strong>
                    <p>${formatDate(pengajuan.tanggal_mulai)} - ${formatDate(pengajuan.tanggal_selesai)}</p>
                </div>
                 <div class="detail-group">
                    <strong>Alasan:</strong>
                    <p>${pengajuan.alasan || '-'}</p>
                </div>
                ${drafSuratHtml}
                ${lampiranHtml}
                ${tandaTanganHtml}
            `;

            // Atur action form untuk menargetkan route update yang benar
            if (prosesForm) {
                prosesForm.action = `/pengajuan-cuti/${pengajuan.id}`;
            }
            
        } catch (error) {
            console.error('[FETCH ERROR]', error);
            modalBody.innerHTML = `<p style="color: red;">Gagal memuat detail. Periksa console untuk info lebih lanjut.</p>`;
        }
    }
    window.closeModal = function(modalId) {
        const modalToClose = document.getElementById(modalId);
        if (modalToClose) {
            modalToClose.style.display = 'none';
        }
    }

    window.onclick = function (event) {
        if (event.target == createModal) {
            closeModal('createCutiModal');
        }
        if (event.target == prosesModal) {
            closeModal('prosesPengajuanModal');
        }
    }
});
