/**
 * Membuka modal untuk menambah template baru.
 */
window.opentemplate = function () {
    document.getElementById('templateModal').style.display = 'block';
    document.getElementById('modalTitle').innerText = 'Tambah Template Baru';
    
    const form = document.getElementById('templateForm');
    if (form) {
        form.action = window.templateRoutes.store;
        form.reset(); // Membersihkan semua input form
    }
    
    document.getElementById('formMethod').innerHTML = '';
    
    // Set nilai default setelah reset
    document.getElementById('kategori').value = '';
    document.getElementById('versi').value = '1.0.0';
    document.getElementById('template_file').required = true;
    document.getElementById('active').checked = true;
}

/**
 * Membuka modal untuk mengedit template yang sudah ada.
 */
window.edittemplate = function (template) {
    document.getElementById('templateModal').style.display = 'block';
    document.getElementById('modalTitle').innerText = 'Edit Template';
    document.getElementById('templateForm').action = `/templates/${template.id}`;
    document.getElementById('formMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    
    // Isi form dengan data yang ada
    document.getElementById('nama_template').value = template.nama_template;
    document.getElementById('kategori').value = template.kategori || ''; // Mengisi kategori
    document.getElementById('versi').value = template.versi;
    document.getElementById('template_file').required = false; // Tidak wajib saat edit
    document.getElementById('active').checked = template.active;
}

/**
 * Menutup modal.
 */
window.closeModal = function () {
    document.getElementById('templateModal').style.display = 'none';
}

// Menambahkan event listener untuk menutup modal saat user mengklik di luar area modal.
window.onclick = function (event) {
    const modal = document.getElementById('templateModal');
    if (event.target == modal) {
        closeModal();
    }
}
