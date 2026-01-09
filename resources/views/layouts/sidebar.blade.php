<div class="sidebar p-3">
    <h4 class="text-center">ERP</h4>
    <hr>
    <a href="{{ route('product') }}">📦 Product</a>
    <a href="{{ route('category') }}">📂 Category</a>
</div>

<style>
.sidebar{
    width:220px;
    height:100vh;
    background:#1778ff;
    color:white;
}
.sidebar a{
    color:white;
    text-decoration:none;
    display:block;
    padding:10px;
    border-radius:6px;
}
.sidebar a:hover{
    background:#40739e;
}
</style>
