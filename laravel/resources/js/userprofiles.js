// Fungsi untuk membersihkan dan membuka modal untuk user baru
window.openModal = function () {
    const modal = document.getElementById('userProfileModal');
    const form = document.getElementById('userProfileForm');
    
    form.reset(); // Membersihkan semua input
    form.action = window.routes.store;
    
    document.getElementById('modalTitle').innerText = 'Tambah User Baru';
    document.getElementById('formMethod').innerHTML = '';
    document.getElementById('password').required = true;
    document.getElementById('passwordHelp').style.display = 'none';
    document.getElementById('imagePreview').src = 'https://ui-avatars.com/api/?name=?';
    
    modal.style.display = 'block';
};

// Fungsi untuk membuka dan mengisi modal dengan data user yang akan diedit
window.editModal = function (userId) {
    const modal = document.getElementById('userProfileModal');
    const form = document.getElementById('userProfileForm');
    const user = window.usersWithProfile[userId];

    if (!user) return;

    form.reset();
    form.action = `${window.routes.update_base}/${user.id}`;
    
    document.getElementById('modalTitle').innerText = 'Edit User & Profil';
    document.getElementById('formMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('password').required = false;
    document.getElementById('passwordHelp').style.display = 'block';

    // Isi form dengan data
    document.getElementById('name').value = user.name;
    document.getElementById('email').value = user.email;
    document.getElementById('role').value = user.role;
    document.getElementById('aktif').checked = user.aktif;

    if (user.profile) {
        document.getElementById('nama_lengkap').value = user.profile.nama_lengkap || '';
        document.getElementById('jabatan').value = user.profile.jabatan || '';
        document.getElementById('telepon').value = user.profile.telepon || '';
        document.getElementById('imagePreview').src = user.profile.foto ? `${window.asset_base}${user.profile.foto}` : `https://ui-avatars.com/api/?name=${user.name}`;
    }

    modal.style.display = 'block';
};

// Fungsi untuk menutup modal
window.closeModal = function () {
    document.getElementById('userProfileModal').style.display = 'none';
};

// Fungsi untuk preview gambar
window.previewImage = function(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('imagePreview');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
};

// Event listener untuk menutup modal saat klik di luar area modal
window.onclick = function (event) {
    const modal = document.getElementById('userProfileModal');
    if (event.target == modal) {
        closeModal();
    }
};