@extends('layouts.main')

@section('title', 'Manajemen Produk')

@section('content')
<h3>Manajemen Produk</h3>

<input type="text" id="search" class="form-control mb-3" placeholder="Cari produk...">

<button class="btn btn-success mb-3" onclick="showAddModal()">
    + Tambah Produk
</button>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th width="200">Aksi</th>
        </tr>
    </thead>
    <tbody id="productTable"></tbody>
</table>

<div class="modal fade" id="addModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
    <h5>Tambah Produk</h5>
</div>
<div class="modal-body">
    <input id="add_name" class="form-control mb-2" placeholder="Nama Produk">
    <input id="add_price" type="number" class="form-control mb-2" placeholder="Harga">
    <input id="add_stock" type="number" class="form-control mb-2" placeholder="Stok">
    <select id="add_category" class="form-control"></select>
</div>
<div class="modal-footer">
    <button class="btn btn-success" onclick="addProduct()">Simpan</button>
</div>
</div>
</div>
</div>

<div class="modal fade" id="editModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
    <h5>Edit Produk</h5>
</div>
<div class="modal-body">
    <input type="hidden" id="edit_id">
    <input id="edit_name" class="form-control mb-2" placeholder="Nama Produk">
    <input id="edit_price" type="number" class="form-control mb-2" placeholder="Harga">
    <input id="edit_stock" type="number" class="form-control mb-2" placeholder="Stok">
    <select id="edit_category" class="form-control"></select>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" onclick="updateProduct()">Update</button>
</div>
</div>
</div>
</div>

<script>
const API_PROD = "/api/products"
const API_CAT = "/api/categories"
let products = []

function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(number)
}

function loadCategories(selectId) {
    fetch(API_CAT)
        .then(res => res.json())
        .then(data => {
            document.getElementById(selectId).innerHTML =
                data.map(c =>
                    `<option value="${c.id}">${c.name_category}</option>`
                ).join('')
        })
}

function loadProducts() {
    fetch(API_PROD)
        .then(res => res.json())
        .then(data => {
            products = data
            renderProducts(data)
        })
}

function renderProducts(data) {
    productTable.innerHTML = data.map(p => `
        <tr>
            <td>${p.name_product}</td>
            <td>${p.category.name_category}</td>
            <td>${formatRupiah(p.price)}</td>
            <td>${p.stock}</td>
            <td>
                <button class="btn btn-info btn-sm"
                    onclick="checkStock(${p.stock})">
                    Cek Status
                </button>
                <button class="btn btn-warning btn-sm"
                    onclick="showEdit(${p.id},
                        '${p.name_product}',
                        ${p.price},
                        ${p.stock},
                        ${p.category_id})">
                    Edit
                </button>
                <button class="btn btn-danger btn-sm"
                    onclick="deleteProduct(${p.id})">
                    Delete
                </button>
            </td>
        </tr>
    `).join('')
}

function showAddModal() {
    add_name.value = ''
    add_price.value = ''
    add_stock.value = ''
    loadCategories('add_category')
    new bootstrap.Modal(addModal).show()
}

function addProduct() {
    fetch(API_PROD, {
        method: "POST",
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            name_product: add_name.value,
            price: add_price.value,
            stock: add_stock.value,
            category_id: add_category.value
        })
    })
    .then(async res => {
        const data = await res.json()
        if (!res.ok) throw data
        Swal.fire('Berhasil', data.message, 'success')
        loadProducts()
        bootstrap.Modal.getInstance(addModal).hide()
    })
    .catch(err => {
        Swal.fire(
            'Gagal',
            err.errors?.name_product?.[0] || err.message,
            'error'
        )
    })
}

function showEdit(id, name, price, stock, category) {
    edit_id.value = id
    edit_name.value = name
    edit_price.value = price
    edit_stock.value = stock
    loadCategories('edit_category')
    setTimeout(() => edit_category.value = category, 300)
    new bootstrap.Modal(editModal).show()
}

function updateProduct() {
    fetch(`${API_PROD}/${edit_id.value}`, {
        method: "PUT",
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            name_product: edit_name.value,
            price: edit_price.value,
            stock: edit_stock.value,
            category_id: edit_category.value
        })
    })
    .then(async res => {
        const data = await res.json()
        if (!res.ok) throw data
        Swal.fire('Berhasil', data.message, 'success')
        loadProducts()
        bootstrap.Modal.getInstance(editModal).hide()
    })
    .catch(err => {
        Swal.fire(
            'Gagal',
            err.errors?.name_product?.[0] || err.message,
            'error'
        )
    })
}

function deleteProduct(id) {
    Swal.fire({
        title: 'Yakin?',
        text: 'Produk akan dihapus',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus'
    }).then(result => {
        if (!result.isConfirmed) return
        fetch(`${API_PROD}/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(res => {
            Swal.fire('Berhasil', res.message, 'success')
            loadProducts()
        })
    })
}

function checkStock(stock) {
    Swal.fire(
        'Status Stok',
        stock > 0 ? 'Stok Tersedia' : 'Stok Habis',
        stock > 0 ? 'success' : 'warning'
    )
}

search.addEventListener('keyup', function () {
    const keyword = this.value.toLowerCase()
    renderProducts(
        products.filter(p =>
            p.name_product.toLowerCase().includes(keyword) ||
            p.category.name_category.toLowerCase().includes(keyword)
        )
    )
})

loadProducts()
</script>
@endsection
