
    window.openModal = function () {
        document.getElementById('templateModal').style.display = 'block';
        document.getElementById('modalTitle').innerText = 'Tambah Template Baru';
        document.getElementById('templateForm').action = window.templateRoutes.store;
        document.getElementById('formMethod').innerHTML = '';
        document.getElementById('nama_template').value = '';
        document.getElementById('kategori').value = '';
        document.getElementById('versi').value = '1.0.0';
        document.getElementById('template_file').required = true;
        document.getElementById('active').checked = true;
    }

    window.editTemplate = function (template) {
        document.getElementById('templateModal').style.display = 'block';
        document.getElementById('modalTitle').innerText = 'Edit Template';
        document.getElementById('templateForm').action = `/templates/${template.id}`;
        document.getElementById('formMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('nama_template').value = template.nama_template;
        document.getElementById('kategori').value = template.kategori;
        document.getElementById('versi').value = template.versi;
        document.getElementById('template_file').required = false;
        document.getElementById('active').checked = template.active;
    }

    window.closeModal = function () {
        document.getElementById('templateModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function (event) {
        const modal = document.getElementById('templateModal');
        if (event.target == modal) {
            closeModal();
        }
    }