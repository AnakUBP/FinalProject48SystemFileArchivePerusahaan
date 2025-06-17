window.openup = function () {
    // Dapatkan elemen-elemen yang dibutuhkan dari DOM
    const modal = document.getElementById('userProfileModal');
    const form = document.getElementById('userProfileForm');
    
    // Pastikan elemen modal dan form ada sebelum melanjutkan
    if (!modal || !form) {
        console.error("Elemen modal atau form tidak ditemukan!");
        return;
    }
    
    // Atur judul, action, dan method form untuk operasi 'tambah baru'
    document.getElementById('modalTitle').innerText = 'Tambah User Baru';
    form.action = window.routes.store;
    document.getElementById('formMethod').innerHTML = ''; // Tidak perlu method PUT

    // === Membersihkan form secara manual, mirip seperti opentemplate ===
    // FIX: Tambahkan pengecekan null untuk setiap elemen sebelum mengatur nilainya
    const nameInput = document.getElementById('name');
    if (nameInput) nameInput.value = '';

    const emailInput = document.getElementById('email');
    if (emailInput) emailInput.value = '';
    
    const passwordInput = document.getElementById('password');
    if (passwordInput) passwordInput.value = '';

    const roleSelect = document.getElementById('role');
    if (roleSelect) roleSelect.value = 'karyawan';

    const namaLengkapInput = document.getElementById('nama_lengkap');
    if (namaLengkapInput) namaLengkapInput.value = '';

    const jabatanInput = document.getElementById('jabatan');
    if (jabatanInput) jabatanInput.value = '';

    const teleponInput = document.getElementById('telepon');
    if (teleponInput) teleponInput.value = '';

    const jenisKelaminSelect = document.getElementById('jenis_kelamin');
    if (jenisKelaminSelect) jenisKelaminSelect.value = 'pria';

    const aktifCheckbox = document.getElementById('aktif');
    if (aktifCheckbox) aktifCheckbox.checked = true;

    const fotoInput = document.getElementById('foto');
    if (fotoInput) fotoInput.value = '';

    // Atur field password menjadi wajib diisi dan sembunyikan teks bantuan
    if (passwordInput) passwordInput.required = true;
    
    const passwordHelp = document.getElementById('passwordHelp');
    if (passwordHelp) passwordHelp.style.display = 'none';
    
    // Setel ulang pratinjau gambar ke placeholder default
    const imagePreview = document.getElementById('imagePreview');
    if(imagePreview) imagePreview.src = 'https://ui-avatars.com/api/?name=?';
    
    // Tampilkan modal
    modal.style.display = 'block';
};
/**
 * Membuka modal untuk mengedit user yang sudah ada.
 * Fungsi ini akan mengisi form dengan data user yang dipilih.
 * @param {number} userId - ID dari user yang akan diedit.
 */
window.editup = function (userId) {
    // Dapatkan data user lengkap dari objek global yang dikirim dari Blade
    const user = window.usersWithProfile[userId];
    if (!user) {
        console.error('User dengan ID ' + userId + ' tidak ditemukan!');
        return;
    }

    const modal = document.getElementById('userProfileModal');
    const form = document.getElementById('userProfileForm');

    // Atur judul, action, dan method form untuk operasi 'edit'
    document.getElementById('modalTitle').innerText = 'Edit User & Profil';
    form.action = `${window.routes.update_base}/${user.id}`;
    document.getElementById('formMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    
    // Atur field password agar tidak wajib diisi dan tampilkan teks bantuan
    document.getElementById('password').required = false;
    document.getElementById('passwordHelp').style.display = 'block';

    // === Isi form dengan data yang ada ===
    // Bagian Akun
    document.getElementById('name').value = user.name;
    document.getElementById('email').value = user.email;
    document.getElementById('role').value = user.role;
    document.getElementById('aktif').checked = user.aktif;

    // Bagian Profil (gunakan || '' untuk fallback jika data null)
    if (user.profile) {
        document.getElementById('nama_lengkap').value = user.profile.nama_lengkap || '';
        document.getElementById('jabatan').value = user.profile.jabatan || '';
        document.getElementById('telepon').value = user.profile.telepon || '';
        document.getElementById('jenis_kelamin').value = user.profile.jenis_kelamin || 'pria';
    }

    // Tampilkan foto profil jika ada, jika tidak, gunakan UI Avatars
    const avatarUrl = user.profile?.foto 
        ? `${window.asset_base}${user.profile.foto}` 
        : `https://ui-avatars.com/api/?background=random&name=${encodeURIComponent(user.name)}`;
    document.getElementById('imagePreview').src = avatarUrl;
    
    // Tampilkan modal
    modal.style.display = 'block';
};

/**
 * Menutup modal.
 */
window.closeModal = function () {
    document.getElementById('userProfileModal').style.display = 'none';
}

/**
 * Fungsi untuk menampilkan pratinjau gambar yang dipilih oleh user.
 * @param {Event} event - Event 'onchange' dari input file.
 */
window.previewImage = function(event) {
    if (event.target.files && event.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('imagePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
};

// Menambahkan event listener untuk menutup modal saat user mengklik di luar area modal.
window.onclick = function (event) {
    const modal = document.getElementById('userProfileModal');
    if (event.target == modal) {
        closeModal();
    }
}
