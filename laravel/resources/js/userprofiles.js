/**
 * Membuka modal untuk menambah user baru.
 */
window.openup = function () {
    const modal = document.getElementById('userProfileModal');
    const form = document.getElementById('userProfileForm');
    
    if (!modal || !form) {
        console.error("Elemen modal atau form tidak ditemukan!");
        return;
    }
    
    document.getElementById('modalTitle').innerText = 'Tambah User Baru';
    form.action = window.routes.store;
    document.getElementById('formMethod').innerHTML = '';

    // === Membersihkan form secara manual ===
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
    
    const alamatInput = document.getElementById('alamat');
    if (alamatInput) alamatInput.value = '';

    const jenisKelaminSelect = document.getElementById('jenis_kelamin');
    if (jenisKelaminSelect) jenisKelaminSelect.value = 'pria';

    // FIX: Menggunakan getElementById('active') sesuai perintah
    const activeCheckbox = document.getElementById('active');
    if (activeCheckbox) activeCheckbox.checked = true;

    const fotoInput = document.getElementById('foto');
    if (fotoInput) fotoInput.value = '';

    if (passwordInput) passwordInput.required = true;
    
    const passwordHelp = document.getElementById('passwordHelp');
    if (passwordHelp) passwordHelp.style.display = 'none';
    
    const imagePreview = document.getElementById('imagePreview');
    if(imagePreview) imagePreview.src = 'https://ui-avatars.com/api/?name=?';
    
    modal.style.display = 'block';
};

/**
 * Membuka modal untuk mengedit user.
 */
window.editup = function (userId) {
    const user = window.usersWithProfile[userId];
    if (!user) {
        console.error('User dengan ID ' + userId + ' tidak ditemukan!');
        return;
    }

    const modal = document.getElementById('userProfileModal');
    const form = document.getElementById('userProfileForm');

    document.getElementById('modalTitle').innerText = 'Edit User & Profil';
    form.action = `${window.routes.update_base}/${user.id}`;
    document.getElementById('formMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    
    document.getElementById('password').required = false;
    document.getElementById('passwordHelp').style.display = 'block';

    // === Isi form dengan data yang ada ===
    document.getElementById('name').value = user.name;
    document.getElementById('email').value = user.email;
    document.getElementById('role').value = user.role;
    // FIX: Menggunakan getElementById('active') dan properti 'user.active'
    document.getElementById('active').checked = user.active; 

    if (user.profile) {
        document.getElementById('nama_lengkap').value = user.profile.nama_lengkap || '';
        document.getElementById('jabatan').value = user.profile.jabatan || '';
        document.getElementById('telepon').value = user.profile.telepon || '';
        document.getElementById('alamat').value = user.profile.alamat || '';
        document.getElementById('jenis_kelamin').value = user.profile.jenis_kelamin || 'pria';
        document.getElementById('current_quota_display').innerText = user.profile.sisa_kuota_cuti;
        document.getElementById('kuota_tambahan').value = '';
    }

    const avatarUrl = user.profile?.foto 
        ? `${window.asset_base}${user.profile.foto}` 
        : `https://ui-avatars.com/api/?background=random&name=${encodeURIComponent(user.name)}`;
    document.getElementById('imagePreview').src = avatarUrl;
    
    modal.style.display = 'block';
};

/**
 * Menutup modal.
 */
window.closeModal = function () {
    const modal = document.getElementById('userProfileModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

/**
 * Menampilkan pratinjau gambar yang dipilih.
 */
window.previewImage = function(event) {
    if (event.target.files && event.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('imagePreview');
            if (output) {
                output.src = reader.result;
            }
        };
        reader.readAsDataURL(event.target.files[0]);
    }
};

// Event listener untuk menutup modal.
window.onclick = function (event) {
    const modal = document.getElementById('userProfileModal');
    if (event.target == modal) {
        closeModal();
    }
}
