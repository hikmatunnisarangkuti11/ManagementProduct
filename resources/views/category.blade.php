@extends('layouts.main')

@section('title', 'Manajemen Kategori')

@section('content')
<h3>Manajemen Kategori</h3>

<input type="text" id="search" class="form-control mb-3" placeholder="Cari kategori...">

<button class="btn btn-success mb-3" onclick="showAddModal()">
    + Tambah Kategori
</button>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Kategori</th>
            <th width="180">Aksi</th>
        </tr>
    </thead>
    <tbody id="categoryTable"></tbody>
</table>

<div class="modal fade" id="addModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
    <h5>Tambah Kategori</h5>
</div>
<div class="modal-body">
    <input type="text" id="add_name" class="form-control" placeholder="Nama Kategori">
</div>
<div class="modal-footer">
    <button class="btn btn-success" onclick="addCategory()">Simpan</button>
</div>
</div>
</div>
</div>

<div class="modal fade" id="editModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
    <h5>Edit Kategori</h5>
</div>
<div class="modal-body">
    <input type="hidden" id="edit_id">
    <input type="text" id="edit_name" class="form-control" placeholder="Nama Kategori">
</div>
<div class="modal-footer">
    <button class="btn btn-primary" onclick="updateCategory()">Update</button>
</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const API = "/api/categories";
let categories = [];

function loadCategories() {
    fetch(API)
        .then(res => res.json())
        .then(data => {
            categories = data;
            renderTable(data);
        });
}

function renderTable(data) {
    let html = "";
    data.forEach(c => {
        html += `
        <tr>
            <td>${c.name_category}</td>
            <td>
                <button class="btn btn-warning btn-sm"
                    data-id="${c.id}"
                    data-name="${c.name_category}"
                    onclick="showEdit(this)">
                    Edit
                </button>
                <button class="btn btn-danger btn-sm"
                    onclick="deleteCategory(${c.id})">
                    Delete
                </button>
            </td>
        </tr>`;
    });
    categoryTable.innerHTML = html;
}

function showAddModal() {
    add_name.value = '';
    new bootstrap.Modal(addModal).show();
}

function addCategory() {
    fetch(API, {
        method: "POST",
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            name_category: add_name.value
        })
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) throw data;

        Swal.fire('Berhasil', data.message, 'success');
        loadCategories();
        bootstrap.Modal.getInstance(addModal).hide();
    })
    .catch(err => {
        Swal.fire('Gagal', err.message || 'Terjadi kesalahan', 'error');
    });
}

function showEdit(btn) {
    edit_id.value = btn.dataset.id;
    edit_name.value = btn.dataset.name;
    new bootstrap.Modal(editModal).show();
}

function updateCategory() {
    fetch(`${API}/${edit_id.value}`, {
        method: "PUT",
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            name_category: edit_name.value
        })
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) throw data;

        Swal.fire('Berhasil', data.message, 'success');
        loadCategories();
        bootstrap.Modal.getInstance(editModal).hide();
    })
    .catch(err => {
        Swal.fire('Gagal', err.message || 'Terjadi kesalahan', 'error');
    });
}

function deleteCategory(id) {
    Swal.fire({
        title: 'Yakin?',
        text: 'Kategori akan dihapus',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus'
    }).then(result => {
        if (!result.isConfirmed) return;

        fetch(`${API}/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(res => {
            Swal.fire('Berhasil', res.message, 'success');
            loadCategories();
        });
    });
}

search.addEventListener('keyup', function () {
    const keyword = this.value.toLowerCase();
    renderTable(
        categories.filter(c =>
            c.name_category.toLowerCase().includes(keyword)
        )
    );
});

loadCategories();
</script>
@endsection
