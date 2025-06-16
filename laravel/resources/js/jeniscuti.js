window.openJenisCutiModal = function () {
    document.getElementById('jenisCutiModal').style.display = 'block';
    document.getElementById('jenisCutiModalTitle').innerText = 'Tambah Jenis Cuti Baru';
    document.getElementById('jenisCutiForm').action = window.jeniscutiRoutes.store;
    document.getElementById('jenisCutiFormMethod').innerHTML = '';
    document.getElementById('nama').value = '';
    document.getElementById('template_id').value = '';
    document.getElementById('keterangan').value = '';
};

window.editJenisCuti = function (jenisCuti) {
    document.getElementById('jenisCutiModal').style.display = 'block';
    document.getElementById('jenisCutiModalTitle').innerText = 'Edit Jenis Cuti';
    document.getElementById('jenisCutiForm').action = `/jeniscuti/${jenisCuti.id}`;
    document.getElementById('jenisCutiFormMethod').style.display = 'block';
    document.getElementById('jenisCutiFormMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('nama').value = jenisCuti.nama;
    document.getElementById('template_id').value = jenisCuti.template_id || '';
    document.getElementById('keterangan').value = jenisCuti.keterangan || '';
};

window.closeJenisCutiModal = function () {
    document.getElementById('jenisCutiModal').style.display = 'none';
};

    // Close modal when clicking outside
    window.onclick = function (event) {
        const modal = document.getElementById('jenisCutiModal');
        if (event.target == modal) {
            closeJenisCutiModal();
        }
    }


    // Close modal when clicking outside
    window.onclick = function (event) {
        const modal = document.getElementById('jenisCutiModal');
        if (event.target == modal) {
            closeJenisCutiModal();
        }
    }