
@extends('layouts.app')

@section('page-title', 'POS / Kasir')

@section('content')


<div class="row g-4">

    {{-- Daftar Produk --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <div class="mb-3">
                    <input
                        type="text"
                        id="searchProduct"
                        class="form-control"
                        placeholder="Cari nama produk atau SKU..."
                    >
                </div>

                <div class="row g-3" id="productList">

                    @forelse($products as $product)
                        <div
                            class="col-md-4 product-item"
                            data-search="{{ strtolower($product->name . ' ' . $product->sku) }}"
                        >
                            <div class="card h-100 border">
                                <div class="card-body d-flex flex-column">

                                    <h6 class="fw-bold">
                                        {{ $product->name }}
                                    </h6>

                                    <small class="text-muted">
                                        SKU: {{ $product->sku }}
                                    </small>

                                    <div class="mt-2">
                                        <strong class="text-primary">
                                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                        </strong>
                                    </div>

                                    <div class="small text-muted mb-3">
                                        Stok: {{ $product->stock }}
                                        {{ $product->unit }}
                                    </div>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-primary w-100 mt-auto add-to-cart"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-sku="{{ $product->sku }}"
                                        data-price="{{ $product->selling_price }}"
                                        data-stock="{{ $product->stock }}"
                                        data-unit="{{ $product->unit }}"
                                    >
                                        <i class="bi bi-cart-plus"></i>
                                        Tambah ke Keranjang
                                    </button>

                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-5">
                            Belum ada produk yang tersedia untuk dijual.
                        </div>
                    @endforelse

                    <div
                        class="col-12 text-center text-muted py-4 d-none"
                        id="noProductFound"
                    >
                        Produk tidak ditemukan.
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Keranjang --}}
    <div class="col-lg-4">

        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Keranjang Belanja</h5>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                        id="clearCart"
                    >
                        Kosongkan
                    </button>
                </div>

                <div id="cartItems">
                    <div class="text-center text-muted py-5" id="emptyCart">
                        <i class="bi bi-cart3 fs-1"></i>
                        <p class="mb-1">Keranjang masih kosong</p>
                        <small>Pilih produk untuk memulai transaksi.</small>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <span>Total item</span>
                    <strong id="totalItems">0</strong>
                </div>

                <div class="d-flex justify-content-between mt-2">
                    <span>Subtotal</span>
                    <strong id="subtotal">Rp 0</strong>
                </div>

                <div class="d-flex justify-content-between mt-2">
                    <span>Total pembayaran</span>
                    <strong class="text-primary fs-5" id="grandTotal">
                        Rp 0
                    </strong>
                </div>

                <button
                    type="button"
                    class="btn btn-success w-100 mt-3"
                    id="checkoutButton"
                    disabled
                    data-bs-toggle="modal"
                    data-bs-target="#paymentModal"
                >
                    Lanjut ke Pembayaran
                </button>

                <small class="text-muted d-block mt-2 text-center">
                    Fitur pembayaran akan diaktifkan pada tahap berikutnya.
                </small>

            </div>
        </div>

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-body">
                <small class="text-muted">Kasir yang bertugas</small>
                <div class="fw-bold">
                    {{ Auth::user()->name }}
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Modal Pembayaran --}}
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Pembayaran Transaksi</h5>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Total Belanja</label>
                    <input type="text"
                           id="paymentTotal"
                           class="form-control fw-bold fs-4 text-primary"
                           readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <input type="text"
                           class="form-control"
                           value="Tunai"
                           readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Uang Diterima</label>
                    <input type="number"
                           id="paidAmount"
                           class="form-control"
                           min="0"
                           step="1"
                           placeholder="Masukkan jumlah uang pelanggan">
                    <small class="text-muted">
                        Masukkan nominal uang yang diberikan pelanggan.
                    </small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kembalian</label>
                    <input type="text"
                           id="changeAmount"
                           class="form-control fw-bold text-success"
                           value="Rp 0"
                           readonly>
                </div>

                <div id="paymentError"
                     class="alert alert-danger d-none">
                    Uang pembayaran belum mencukupi.
                </div>

                <div id="paymentSuccess"
                     class="alert alert-success d-none">
                    Pembayaran mencukupi.
                </div>

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Batal
                </button>

                <button type="button"
                        class="btn btn-success"
                        id="confirmPayment"
                        disabled>
                    Konfirmasi Pembayaran
                </button>
            </div>

        </div>
    </div>
</div>

<style>
    .cart-item {
        border-bottom: 1px solid #eee;
        padding: 12px 0;
    }

    .cart-quantity {
        width: 65px;
        text-align: center;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const cart = {};

    const formatRupiah = (amount) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(amount);
    };

    const cartItems = document.getElementById('cartItems');
    const subtotalElement = document.getElementById('subtotal');
    const grandTotalElement = document.getElementById('grandTotal');
    const totalItemsElement = document.getElementById('totalItems');
    const checkoutButton = document.getElementById('checkoutButton');

    function renderCart() {
        cartItems.innerHTML = '';

        const products = Object.values(cart);

        if (products.length === 0) {
            cartItems.innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="bi bi-cart3 fs-1"></i>
                    <p class="mb-1">Keranjang masih kosong</p>
                    <small>Pilih produk untuk memulai transaksi.</small>
                </div>
            `;
        }

        let subtotal = 0;
        let totalQuantity = 0;

        products.forEach(product => {
            const itemSubtotal = product.price * product.quantity;

            subtotal += itemSubtotal;
            totalQuantity += product.quantity;

            const item = document.createElement('div');
            item.className = 'cart-item';

            item.innerHTML = `
                <div class="d-flex justify-content-between gap-2">
                    <div>
                        <strong class="cart-product-name"></strong>
                        <small class="d-block text-muted cart-product-sku"></small>
                    </div>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger remove-item"
                        data-id="${product.id}"
                    >
                        Hapus
                    </button>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div class="input-group input-group-sm" style="width: 125px;">
                        <button
                            type="button"
                            class="btn btn-outline-secondary decrease"
                            data-id="${product.id}"
                        >-</button>

                        <input
                            type="number"
                            class="form-control cart-quantity"
                            value="${product.quantity}"
                            min="1"
                            max="${product.stock}"
                            data-id="${product.id}"
                        >

                        <button
                            type="button"
                            class="btn btn-outline-secondary increase"
                            data-id="${product.id}"
                        >+</button>
                    </div>

                    <strong>${formatRupiah(itemSubtotal)}</strong>
                </div>

                <small class="text-muted">
                    ${formatRupiah(product.price)} / ${product.unit}
                </small>
            `;

            item.querySelector('.cart-product-name').textContent = product.name;
            item.querySelector('.cart-product-sku').textContent = product.sku;

            cartItems.appendChild(item);
        });

        subtotalElement.textContent = formatRupiah(subtotal);
        grandTotalElement.textContent = formatRupiah(subtotal);
        totalItemsElement.textContent = totalQuantity;

        checkoutButton.disabled = products.length === 0;
    }

    // Tambah produk
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function () {

            const id = this.dataset.id;
            const stock = Number(this.dataset.stock);

            if (cart[id]) {
                if (cart[id].quantity >= stock) {
                    alert('Jumlah produk sudah mencapai stok tersedia.');
                    return;
                }

                cart[id].quantity++;
            } else {
                cart[id] = {
                    id: id,
                    name: this.dataset.name,
                    sku: this.dataset.sku,
                    price: Number(this.dataset.price),
                    stock: stock,
                    unit: this.dataset.unit,
                    quantity: 1
                };
            }

            renderCart();
        });
    });

    // Tombol tambah, kurang, dan hapus
    cartItems.addEventListener('click', function (event) {

        const id = event.target.dataset.id;

        if (!id || !cart[id]) return;

        if (event.target.classList.contains('increase')) {
            if (cart[id].quantity < cart[id].stock) {
                cart[id].quantity++;
            } else {
                alert('Stok produk tidak mencukupi.');
            }
        }

        if (event.target.classList.contains('decrease')) {
            cart[id].quantity--;

            if (cart[id].quantity <= 0) {
                delete cart[id];
            }
        }

        if (event.target.classList.contains('remove-item')) {
            delete cart[id];
        }

        renderCart();
    });

    // Input jumlah secara manual
    cartItems.addEventListener('change', function (event) {

        if (!event.target.classList.contains('cart-quantity')) return;

        const id = event.target.dataset.id;
        if (!cart[id]) return;

        let quantity = parseInt(event.target.value, 10);

        if (!Number.isInteger(quantity) || quantity < 1) {
            quantity = 1;
        }

        if (quantity > cart[id].stock) {
            quantity = cart[id].stock;
            alert('Jumlah melebihi stok tersedia.');
        }

        cart[id].quantity = quantity;
        renderCart();
    });

    // Kosongkan keranjang
    document.getElementById('clearCart').addEventListener('click', function () {

        if (Object.keys(cart).length === 0) return;

        if (confirm('Yakin ingin mengosongkan seluruh keranjang?')) {
            Object.keys(cart).forEach(id => delete cart[id]);
            renderCart();
        }
    });

    // Pencarian produk
    document.getElementById('searchProduct').addEventListener('input', function () {

        const keyword = this.value.toLowerCase();
        let visibleCount = 0;

        document.querySelectorAll('.product-item').forEach(item => {

            const matched = item.dataset.search.includes(keyword);

            item.style.display = matched ? '' : 'none';

            if (matched) visibleCount++;
        });

        document.getElementById('noProductFound')
            .classList.toggle('d-none', visibleCount !== 0);
    });

        // Elemen pembayaran
    const paymentModal = document.getElementById('paymentModal');
    const paymentTotal = document.getElementById('paymentTotal');
    const paidAmount = document.getElementById('paidAmount');
    const changeAmount = document.getElementById('changeAmount');
    const paymentError = document.getElementById('paymentError');
    const paymentSuccess = document.getElementById('paymentSuccess');
    const confirmPayment = document.getElementById('confirmPayment');

    // Hitung total keranjang
    function getCartTotal() {
        return Object.values(cart).reduce((total, product) => {
            return total + (product.price * product.quantity);
        }, 0);
    }

    // Ketika modal pembayaran dibuka
    paymentModal.addEventListener('show.bs.modal', function () {
        paymentTotal.value = formatRupiah(getCartTotal());

        paidAmount.value = '';
        changeAmount.value = formatRupiah(0);

        paymentError.classList.add('d-none');
        paymentSuccess.classList.add('d-none');

        confirmPayment.disabled = true;
    });

    // Validasi pembayaran
    paidAmount.addEventListener('input', function () {
        const total = getCartTotal();
        const paid = Number(this.value);

        const isValid = Number.isSafeInteger(paid)
            && paid >= total
            && paid > 0;

        if (!this.value || paid <= 0) {
            changeAmount.value = formatRupiah(0);
            paymentError.classList.add('d-none');
            paymentSuccess.classList.add('d-none');
            confirmPayment.disabled = true;
            return;
        }

        if (isValid) {
            const change = paid - total;

            changeAmount.value = formatRupiah(change);

            paymentError.classList.add('d-none');
            paymentSuccess.classList.remove('d-none');

            confirmPayment.disabled = false;
        } else {
            changeAmount.value = formatRupiah(0);

            paymentError.classList.remove('d-none');
            paymentSuccess.classList.add('d-none');

            confirmPayment.disabled = true;
        }
    });

    // Konfirmasi pembayaran sementara
    confirmPayment.addEventListener('click', function () {
        alert(
            'Validasi pembayaran berhasil!\n\n' +
            'Total: ' + formatRupiah(getCartTotal()) + '\n' +
            'Dibayar: ' + formatRupiah(Number(paidAmount.value)) + '\n' +
            'Kembalian: ' + formatRupiah(
                Number(paidAmount.value) - getCartTotal()
            ) +
            '\n\nPenyimpanan transaksi akan diaktifkan pada tahap berikutnya.'
        );
    });

    renderCart();
});
</script>

@endsection
