@extends('layouts.main')

@section('title', 'Manajemen Produk')

@section('content')
<h3>Manajemen Produk</h3>

<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modal">
    Tambah Produk
</button>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody id="productTable"></tbody>
</table>

<!-- MODAL -->
<div class="modal fade" id="modal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
    <h5>Tambah Produk</h5>
</div>
<div class="modal-body">
    <input id="name" class="form-control mb-2" placeholder="Nama Produk">
    <input id="price" type="number" class="form-control mb-2" placeholder="Harga">
    <input id="stock" type="number" class="form-control mb-2" placeholder="Stok">
    <select id="category" class="form-control"></select>
</div>
<div class="modal-footer">
    <button class="btn btn-success" onclick="addProduct()">Simpan</button>
</div>
</div>
</div>
</div>

<script>
const API_CAT = "/api/categories";
const API_PROD = "/api/products";

function loadCategoryOption() {
    fetch(API_CAT)
    .then(res => res.json())
    .then(data => {
        document.getElementById('category').innerHTML = data.map(c =>
            `<option value="${c.id}">${c.name_category}</option>`
        ).join('');
    });
}

function loadProducts() {
    fetch(API_PROD)
    .then(res => res.json())
    .then(data => {
        document.getElementById('productTable').innerHTML = data.map(p => `
            <tr>
                <td>${p.name_product}</td>
                <td>${p.category.name_category}</td>
                <td>${p.price}</td>
                <td>${p.stock}</td>
                <td>
                    <button class="btn btn-info btn-sm"
                        onclick="alert('${p.stock > 0 ? 'Stok Tersedia' : 'Stok Habis'}')">
                        Cek Status
                    </button>
                </td>
            </tr>
        `).join('');
    });
}

function addProduct() {
    fetch(API_PROD, {
        method: "POST",
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            name_product: document.getElementById('name').value,
            price: document.getElementById('price').value,
            stock: document.getElementById('stock').value,
            category_id: document.getElementById('category').value
        })
    })
    .then(res => res.json())
    .then(res => {
        alert(res.message);
        loadProducts();
    });
}

loadCategoryOption();
loadProducts();
</script>
@endsection
