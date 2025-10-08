<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="description" content="Aplikasi Kasir Point of Sale">
    <meta name="keywords" content="POS, Kasir, Transaksi">
    <meta name="author" content="Your Company">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aplikasi Kasir POS</title>
    
    <!-- Bootstrap 5 CSS -->
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <!-- Bootstrap Icons -->
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css"
      rel="stylesheet"
    />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

    <style>
      :root {
        --primary-color: #0d46b4;
        --primary-light: #1e5bc7;
        --primary-dark: #0a3a96;
      }

      body {
        background-color: #f8f9fa;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      }

      .primary-bg {
        background-color: var(--primary-color) !important;
      }

      .primary-text {
        color: var(--primary-color) !important;
      }

      .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
      }

      .btn-outline-primary {
        border-color: var(--primary-color);
        color:  var(--primary-color);
      }

      .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color:  '#f8f9fa';
      }

      .btn-primary:hover {
        background-color: var(--primary-light);
        border-color: var(--primary-light);
      }

      .category-nav {
        justify-content: center;
        position: sticky;
        width: 100%;
        bottom: 0;
        z-index: 100;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin: 0 auto;
      }

      .category-btn {
        border: none;
        background: transparent;
        padding: 10px 20px;
        border-radius: 12px;
        margin: 5px;
        transition: all 0.3s ease;
        color: var(--primary-color);
        font-weight: 500;
      }

      .category-btn.active {
        background-color: var(--primary-color);
        color: white;
      }

      .category-btn:hover {
        background-color: var(--primary-light);
        color: white;
      }

      .product-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border-radius: 12px;
        overflow: hidden;
      }

      .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(13, 70, 180, 0.15);
      }

      .cart-item {
        border-radius: 12px;
        margin-bottom: 10px;
        padding: 15px;
        background: white;
        border: 1px solid #0a3a96;
      }

      .search-box {
        border-radius: 16px;
        border: 2px solid var(--primary-color);
        padding: 12px 20px;
      }

      .search-box:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 70, 180, 0.25);
        border-color: var(--primary-color);
      }

      .calculator {
        background: white;
        border-radius: 16px;
        padding: 20px;
      }

      .calc-display {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 15px;
        text-align: right;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 15px;
      }

      .calc-btn {
        width: 100%;
        height: 50px;
        border-radius: 12px;
        border: none;
        margin: 5px 0;
        font-size: 18px;
        font-weight: bold;
        transition: all 0.3s ease;
      }

      .calc-btn:hover {
        transform: scale(1.05);
      }

      .calc-btn.number {
        background: #e9ecef;
        color: #495057;
      }

      .calc-btn.operator {
        background: var(--primary-color);
        color: white;
      }

      .receipt-section {
        background: white;
        border-radius: 16px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      }

      .receipt-paper {
        background: white;
        padding: 20px;
        font-family: "Courier New", monospace;
        border: 1px dashed #ccc;
        max-height: 400px;
        overflow-y: auto;
      }

      .sync-status {
        position: fixed;
        bottom: 10px;
        right: 10px;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 12px;
        z-index: 1000;
      }

      .printer-status {
        position: fixed;
        bottom: 50px;
        right: 10px;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 12px;
        z-index: 1000;
      }

      @media print {
        body * {
          visibility: hidden;
        }
        .receipt-paper,
        .receipt-paper * {
          visibility: visible;
        }
        .receipt-paper {
          position: absolute;
          left: 0;
          top: 0;
          width: 100%;
        }
      }

      .sidebar {
        height: 100vh;
        overflow-y: auto;
        background: white;
        border-radius: 16px 0 0 16px;
      }

      .main-content {
        height: 100vh;
        overflow-y: auto;
        padding: 20px;
      }

      .toast-container {
        z-index: 9999;
      }

      /* Styling untuk modal pembayaran */
      .info-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 15px;
        text-align: center;
        margin-bottom: 15px;
      }

      .nominal-btn {
        background: #e9ecef;
        border: none;
        border-radius: 8px;
        padding: 10px;
        transition: all 0.3s ease;
      }

      .nominal-btn:hover {
        background: var(--primary-color);
        color: white;
      }

      .btn-batal {
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px;
      }

      .btn-bayar {
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px;
      }

      .error-text {
        color: #dc3545;
        font-size: 14px;
        margin-top: 5px;
      }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <!-- Kolom Produk -->
        <div class="col-lg-8 col-md-8 main-content">
          <div class="d-flex justify-content-between align-items-center mb-4">
                    <!-- Fullscreen Toggle Button -->
        {{-- <button class="btn btn-primary" id="fullscreenToggle" onclick="toggleFullscreen()">
            <i class="fas fa-expand" id="fullscreenIcon"></i>
            <div class="tooltip" id="fullscreenTooltip">
                Mode Layar Penuh
            </div>
        </button> --}}
            <button class="btn btn-primary btn-lg" id="fullscreenToggle" onclick="toggleFullscreen()">
              <i class="bi bi-arrows-fullscreen"></i>
            </button>
            <div class="d-flex gap-2">
              <button 
                id="connectPrinterBtn" 
                class="btn btn-outline-primary w-100"
                title="Sambungkan ke Printer Thermal"
              >
                <i class="bi bi-printer me-2"></i>Connect Printer
              </button>
              <div class="input-group">
                <input
                  type="text"
                  id="searchInput"
                  class="form-control search-box border-end-0"
                  placeholder="Cari produk..."
                />
                <button
                  class="btn btn-outline-secondary border-start-0 bg-white"
                  type="button"
                >
                  <i class="bi bi-upc-scan fs-5 primary-text"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Kategori Produk -->
          <div class="d-flex justify-content-center mb-4">
            <div class="category-nav d-flex">
              <button class="category-btn active" onclick="filterProducts('all')">Semua</button>
              <button class="category-btn" onclick="filterProducts('makanan')">Makanan</button>
              <button class="category-btn" onclick="filterProducts('minuman')">Minuman</button>
              <button class="category-btn" onclick="filterProducts('snack')">Snack</button>
            </div>
          </div>

          <!-- Grid Produk -->
          <div class="card">
            <div class="card-body">
              <div class="row" id="productGrid">
                <!-- Products will be generated by JavaScript -->
              </div>
            </div>
          </div>
        </div>

        <!-- Kolom Keranjang -->
        <div class="col-lg-4 col-md-4 sidebar p-0">
          <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="primary-text fw-bold mb-0">Keranjang</h5>
              <button class="btn btn-danger" onclick="clearCart()">Reset</button>
            </div>

            <!-- Cart Items -->
            <div
              id="cartItems"
              class="mb-4"
              style="max-height: 450px; overflow-y: auto"
            >
              {{-- <p class="text-muted text-center">Keranjang kosong</p> --}}
            </div>

            <!-- Total sticky -->
            <div
              class="border-top pt-2 mb-3 bg-white p-2"
              style="position: sticky; top: 0; z-index: 10"
            >
              <div
                class="d-flex justify-content-between fw-bold fs-5 primary-text"
              >
                <span>Total:</span>
                <span id="totalPrice">Rp 0</span>
              </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-3">
              <button 
                class="btn btn-lg btn-primary w-100 mb-2 fw-bold"
                onclick="processPayment()"
              >
               Proses Pembayaran
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Status Sinkronisasi -->
    <div id="syncStatus" class="sync-status bg-secondary text-white d-none">
      <i class="bi bi-arrow-repeat me-1"></i> Menyinkronkan...
    </div>

    <!-- Status Printer -->
    <div id="printerStatus" class="printer-status bg-secondary text-white d-none">
      <i class="bi bi-printer me-1"></i> Printer Disconnected
    </div>

    <!-- Receipt Modal -->
    <div class="modal fade" id="receiptModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Struk Pembayaran</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body">
            <div class="receipt-paper" id="receiptContent">
              <!-- Receipt content will be generated here -->
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary"
              onclick="printReceiptContent()"
            >
              <i class="bi bi-printer me-2"></i>Print
            </button>
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Pembayaran -->
    <div
      class="modal fade"
      id="modalPembayaran"
      tabindex="-1"
      aria-labelledby="modalPembayaranLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalPembayaranLabel">Pembayaran</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-3">
            <!-- Info Cards -->
            <div class="row">
              <div class="col-md-6">
                <div class="info-card total-belanja">
                  <h5>Total Belanja</h5>
                  <h3 id="totalBelanjaDisplay">Rp 0</h3>
                </div>
              </div>
              <div class="col-md-6">
                <div class="info-card kembalian">
                  <h5>Kembalian</h5>
                  <h3 id="kembalianAmount">Rp 0</h3>
                </div>
              </div>
            </div>
            <!-- Nama Customer & Nominal Bayar -->
            <div class="row">
              <div class="col-md-6">
                <label class="form-label">Nama Customer</label>
                <input
                  type="text"
                  class="form-control"
                  id="customerName"
                  value="Umum"
                  placeholder="Masukkan nama customer"
                />
              </div>
              <div class="col-md-6">
                <label class="form-label">Nominal Bayar</label>
                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">Rp</span>
                  <input
                    type="number"
                    class="form-control"
                    id="nominalBayar"
                    placeholder="0"
                    min="0"
                  />
                </div>
                <div id="errorMessage" class="error-text" style="display: none">
                  Please fill out this field.
                </div>
              </div>
            </div>

            <!-- Quick Amount Buttons -->
            <div class="row g-2 mb-3">
              <div class="col-4">
                <button class="nominal-btn w-100" onclick="setNominal(2000)">
                  2.000
                </button>
              </div>
              <div class="col-4">
                <button class="nominal-btn w-100" onclick="setNominal(5000)">
                  5.000
                </button>
              </div>
              <div class="col-4">
                <button class="nominal-btn w-100" onclick="setNominal(10000)">
                  10.000
                </button>
              </div>
              <div class="col-4">
                <button class="nominal-btn w-100" onclick="setNominal(20000)">
                  20.000
                </button>
              </div>
              <div class="col-4">
                <button class="nominal-btn w-100" onclick="setNominal(50000)">
                  50.000
                </button>
              </div>
              <div class="col-4">
                <button class="nominal-btn w-100" onclick="setNominal(100000)">
                  100.000
                </button>
              </div>
            </div>

            <!-- Metode Pembayaran -->
            <div class="mb-4">
              <label class="form-label">Metode Pembayaran</label>
              <select class="form-select" id="paymentMethod">
                <option selected>Pilih metode pembayaran</option>
                <option value="tunai">Tunai</option>
                <option value="debit">Kartu Debit</option>
                <option value="kredit">Kartu Kredit</option>
                <option value="ewallet">E-Wallet</option>
                <option value="transfer">Transfer Bank</option>
              </select>
            </div>

            <!-- Action Buttons -->
            <div class="row g-3">
              <div class="col-md-6">
                <button
                  type="button"
                  class="btn btn-batal w-100"
                  data-bs-dismiss="modal"
                >
                  Batal
                </button>
              </div>
              <div class="col-md-6">
                <button
                  type="button"
                  class="btn btn-bayar w-100"
                  onclick="prosesPembayaran()"
                >
                  Bayar
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Konfirmasi Transaksi -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <div class="modal-body text-center p-4">
            <div class="mb-3">
              <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
            </div>
            <h5 class="modal-title fw-bold mb-2" id="successModalLabel">
              Transaksi Berhasil
            </h5>
            <p class="text-muted mb-4">
              Pembayaran telah berhasil diproses. Apakah Anda ingin mencetak struk pembayaran?
            </p>
            <div class="row g-2">
              <div class="col-6">
                <button type="button" class="btn btn-lg btn-outline-secondary w-100" data-bs-dismiss="modal">
                  <i class="bi bi-x-circle me-2"></i>Tidak
                </button>
              </div>
              <div class="col-6">
                <button type="button" class="btn btn-lg btn-primary w-100" onclick="printThermalReceipt()">
                  <i class="bi bi-printer me-2"></i>Print Struk
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>

    <script type="text/javascript">
      // ==================== VARIABEL GLOBAL ====================
      let products = [];
      let cart = [];
      let currentCategory = "all";
      let calculatorValue = "0";
      let paidAmount = 0;
      let db = null;
      let syncInterval = null;
      let dbInitialized = false;
      let connectedPrinter = null;
      let currentTransactionData = null;

      // ==================== INISIALISASI APLIKASI ====================
      window.onload = function () {
          initDatabase();
          getProducts();
          updateCartDisplay();
          updatePaymentCalculation();
          initPrinterConnection();
      };

      // ==================== FUNGSI PRINTER ====================
      /**
       * Inisialisasi koneksi printer
       */
      function initPrinterConnection() {
          const connectButton = document.getElementById('connectPrinterBtn');
          if (connectButton) {
              connectButton.addEventListener('click', async () => {
                  await connectToPrinter();
              });
          }
          updatePrinterStatus();
      }

      /**
       * Menghubungkan ke printer Bluetooth
       */
      async function connectToPrinter() {
          try {
              updatePrinterStatus('Menghubungkan printer...', 'warning');
              connectedPrinter = await getPrinter();
              if (connectedPrinter) {
                  updatePrinterStatus('Printer Terhubung', 'success');
                  showToast('Printer berhasil tersambung', 'success');
              } else {
                  updatePrinterStatus('Printer Gagal', 'danger');
              }
          } catch (error) {
              console.error('Error connecting printer:', error);
              updatePrinterStatus('Printer Error', 'danger');
          }
      }

      /**
       * Mendapatkan printer Bluetooth
       */
      async function getPrinter() {
          try {
              if (!navigator.bluetooth) {
                  throw new Error('Browser tidak mendukung Bluetooth');
              }

              const device = await navigator.bluetooth.requestDevice({
                  filters: [
                      { namePrefix: "RPP" },
                      { namePrefix: "Thermal" },
                      { namePrefix: "POS" }
                  ],
                  optionalServices: ["000018f0-0000-1000-8000-00805f9b34fb"]
              });

              if (!device) {
                  throw new Error('Tidak ada perangkat yang dipilih');
              }

              alert("Perangkat berhasil tersambung: " + device.name);
              return device;

          } catch (error) {
              if (error.name === 'NotFoundError') {
                  alert("Tidak ada printer Bluetooth yang ditemukan");
              } else if (error.name === 'SecurityError') {
                  alert("Izin Bluetooth diperlukan untuk menyambungkan printer");
              } else {
                  alert("Perangkat gagal tersambung: " + error.message);
              }
              console.error("Perangkat gagal tersambung, Error:", error);
              return null;
          }
      }

      /**
       * Memeriksa koneksi printer
       */
      async function checkPrinterConnection() {
          if (!connectedPrinter) return false;
          
          try {
              await connectedPrinter.gatt.connect();
              return true;
          } catch (e) {
              console.warn("Printer terputus, mencoba reconnect...");
              connectedPrinter = null;
              return false;
          }
      }

      /**
       * Memperbarui status printer di UI
       */
      function updatePrinterStatus(message = '', type = 'secondary') {
          const printerStatus = document.getElementById("printerStatus");
          const connectBtn = document.getElementById("connectPrinterBtn");
          
          if (!printerStatus) return;

          if (connectedPrinter) {
              printerStatus.innerHTML = `<i class="bi bi-printer me-1"></i> Printer Connected`;
              printerStatus.className = `printer-status bg-primary text-white`;
              if (connectBtn) {
                  connectBtn.innerHTML = `<i class="bi bi-printer-check me-2"></i>Printer Ready`;
                  connectBtn.classList.remove('btn-outline-primary');
                  connectBtn.classList.add('btn-success');
              }
          } else {
              printerStatus.innerHTML = `<i class="bi bi-printer me-1"></i> ${message || 'Printer Disconnected'}`;
              printerStatus.className = `printer-status bg-${type} text-white`;
              if (connectBtn) {
                  connectBtn.innerHTML = `<i class="bi bi-printer me-2"></i>Connect Printer`;
                  connectBtn.classList.remove('btn-success');
                  connectBtn.classList.add('btn-outline-primary');
              }
          }
          
          if (message) {
              printerStatus.classList.remove("d-none");
          }
      }

      /**
       * Mencetak struk ke printer thermal
       */
      async function printThermalReceipt() {
          if (!currentTransactionData) {
              showToast('Tidak ada data transaksi untuk dicetak', 'warning');
              return;
          }

          try {
              if (!connectedPrinter || !await checkPrinterConnection()) {
                  const shouldConnect = confirm('Printer belum terhubung. Sambungkan sekarang?');
                  if (shouldConnect) {
                      await connectToPrinter();
                      if (!connectedPrinter) {
                          showToast('Gagal menghubungkan printer', 'error');
                          return;
                      }
                  } else {
                      return;
                  }
              }

              await printReceiptToThermalPrinter(currentTransactionData);
              showToast('Struk berhasil dicetak', 'success');
              
              // Tutup modal setelah print berhasil
              const modal = bootstrap.Modal.getInstance(document.getElementById('successModal'));
              if (modal) modal.hide();
              
          } catch (error) {
              console.error('Gagal mencetak struk:', error);
              showToast('Gagal mencetak struk: ' + error.message, 'error');
          }
      }

      /**
       * Mengirim data struk ke printer thermal
       */
      async function printReceiptToThermalPrinter(data) {
          try {
              console.log("Menyambungkan ke printer...");
              const server = await connectedPrinter.gatt.connect();
              const service = await server.getPrimaryService("000018f0-0000-1000-8000-00805f9b34fb");
              const characteristic = await service.getCharacteristic("00002af1-0000-1000-8000-00805f9b34fb");
              
              console.log("Printer siap, mengirim struk...");
              const encoder = new TextEncoder();
              
              // Generate receipt content dengan format ESC/POS
              const receiptContent = generateThermalReceiptContent(data);
              await sendChunks(characteristic, encoder.encode(receiptContent));
              
              console.log("Sukses mencetak struk thermal");
              
          } catch (e) {
              console.error("Failed to print thermal", e);
              // Reset connection on error
              connectedPrinter = null;
              updatePrinterStatus();
              throw e;
          }
      }

      /**
       * Membuat konten struk untuk printer thermal
       */
      function generateThermalReceiptContent(data) {
          let receipt = "\x1B\x40"; // Reset printer
          receipt += "\x1B\x61\x01"; // Perataan Tengah
          receipt += "\x1B\x21\x10"; // Text tebal dan besar
          receipt += (data.store?.name || "BAKSO RUDY") + "\n";
          receipt += "\x1B\x21\x00"; // Normal text
          receipt += (data.store?.address || "Depan Terminal Induk Kota Menggala") + "\n";
          receipt += "WhatsApp: " + (data.store?.phone || "081369970183") + "\n";
          receipt += "================================\n";
          receipt += "\x1B\x61\x00"; // Kembalikan ke rata kiri

          // Detail Transaksi
          receipt += "Kode Transaksi: " + (data.order?.transaction_number || data.transactionId || 'N/A') + "\n";
          receipt += "Nama pemesan: " + (data?.customer || 'N/A') + "\n";
          receipt += "Pembayaran: " + (data.order?.payment_method?.name || "TUNAI") + "\n";
          receipt += "Tanggal: " + (data.date || new Date().toLocaleString("id-ID")) + "\n";
          receipt += "================================\n";
          receipt += formatRow("Nama Barang", "Qty", "Harga") + "\n";
          receipt += "--------------------------------\n";

          let total = 0;
          data.items.forEach(item => {
              receipt += formatRow(
                  item.product?.name || item.name, 
                  item.quantity, 
                  formatRibuan(item.product?.price || item.price)
              ) + "\n";
              total += item.quantity * (item.product?.price || item.price);
          });

          receipt += "--------------------------------\n";
          receipt += formatRow("Total", "", formatRibuan(total)) + "\n";
          receipt += formatRow("Nominal Bayar", "", formatRibuan(data.order?.cash_received || data.paid)) + "\n";
          receipt += formatRow("Kembalian", "", formatRibuan(data.order?.change || data.change)) + "\n";
          receipt += "================================\n";
          receipt += "\x1B\x61\x01"; // Perataan Tengah
          receipt += "Terima Kasih!\n";
          receipt += "\n";
          receipt += "================================\n";
          receipt += "\x1B\x61\x00"; // Kembalikan ke rata kiri
          receipt += "\x1D\x56\x00"; // ESC/POS cut paper

          return receipt;
      }

      /**
       * Mengirim data ke printer dalam bentuk chunk
       */
      async function sendChunks(characteristic, data) {
          const chunkSize = 180; // BLE limit
          let offset = 0;
          while (offset < data.length) {
              let chunk = data.slice(offset, offset + chunkSize);
              await characteristic.writeValue(chunk);
              offset += chunkSize;
          }
      }

      // ==================== FUNGSI UTILITAS ====================
      /**
       * Format angka ke format ribuan
       */
      function formatRibuan(number) {
          let n = typeof number === 'string' ? parseFloat(number) : number;
          return n.toLocaleString("id-ID");
      }

      /**
       * Format baris untuk struk
       */
      function formatRow(name, qty, price) {
          const nameWidth = 16, qtyWidth = 6, priceWidth = 10;
          let nameLines = (name || '').match(new RegExp('.{1,' + nameWidth + '}', 'g')) || [name || ''];
          let output = '';
          
          for (let i = 0; i < nameLines.length - 1; i++) {
              output += nameLines[i].padEnd(32) + "\n";
          }
          
          let lastLine = nameLines[nameLines.length - 1].padEnd(nameWidth);
          let qtyStr = (qty || '').toString().padStart(qtyWidth);
          let priceStr = (price || '').toString().padStart(priceWidth);
          output += lastLine + qtyStr + priceStr;
          return output;
      }

      /**
       * Fungsi AJAX untuk komunikasi dengan server
       */
      async function transAjax(data) {
          let html = null;
          data.headers = {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
              'Content-Type': 'application/json',
              'Accept': 'application/json'
          };
          
          try {
              await $.ajax(data).done(function(res) {
                  html = res;
              });
              return html;
          } catch (error) {
              console.error('Ajax error:', error);
              throw error;
          }
      }

      // ==================== FUNGSI PRODUK ====================
      /**
       * Mendapatkan data produk dari server
       */
      async function getProducts() {
          const param = {
              url: '{{ url()->current() }}',
              method: 'GET',
          }; 

          try {
              const result = await transAjax(param);
              products = result.data.data;
              displayProducts();
          } catch (error) {
              console.error('Error loading products:', error);
              alert('Gagal memuat data produk');
          }
      }
      
      /**
       * Menampilkan produk di grid
       */
      function displayProducts() {
          const productGrid = document.getElementById("productGrid");
          if (!productGrid) return;

          const searchTerm = document.getElementById("searchInput")?.value.toLowerCase() || '';

          const filteredProducts = products.filter((product) => {
              const matchesCategory = currentCategory === "all" || product.category === currentCategory;
              const matchesSearch = product.name.toLowerCase().includes(searchTerm);
              return matchesCategory && matchesSearch;
          });

          productGrid.innerHTML = filteredProducts.map((product) => `
              <div class="col-md-4 col-lg-3 mb-4">
                  <div class="card product-card h-100" onclick="addToCart('${product.id}')">
                      <img src="/assets/images/${product.image || 'default-product.jpg'}" class="card-img-top" alt="${product.name}" style="height: 150px; object-fit: cover;">
                      <div class="card-body d-flex flex-column">
                          <h6 class="card-title">${product.name}</h6>
                          <p class="card-text text-muted small">${product.category}</p>
                          <div class="mt-auto">
                              <span class="fw-bold primary-text fs-5">${formatCurrency(product.price)}</span>
                              ${product.stock <= product.min_stock ? '<span class="badge bg-warning ms-1">Stok Rendah</span>' : ''}
                              ${product.stock === 0 ? '<span class="badge bg-danger ms-1">Habis</span>' : ''}
                          </div>
                      </div>
                  </div>
              </div>
          `).join("");
      }

      /**
       * Filter produk berdasarkan kategori
       */
      function filterProducts(category) {
          currentCategory = category;
          document.querySelectorAll(".category-btn").forEach((btn) => btn.classList.remove("active"));
          event.target.classList.add("active");
          displayProducts();
      }

      // ==================== FUNGSI KERANJANG ====================
      /**
       * Menambahkan produk ke keranjang
       */
      function addToCart(productId) {
          const product = products.find((p) => p.id === productId);
          if (!product) {
              showToast('Produk tidak ditemukan', 'error');
              return;
          }

          if (product.stock <= 0) {
              showToast('Stok produk habis', 'warning');
              return;
          }

          const existingItem = cart.find((item) => item.id === productId);

          if (existingItem) {
              if (existingItem.quantity >= product.stock) {
                  showToast('Stok tidak mencukupi', 'warning');
                  return;
              }
              existingItem.quantity += 1;
          } else {
              cart.push({ 
                  ...product, 
                  quantity: 1 
              });
          }

          updateCartDisplay();
          showToast(`${product.name} ditambahkan ke keranjang`, 'success');
      }

      /**
       * Memperbarui tampilan keranjang
       */
      function updateCartDisplay() {
          const cartItems = document.getElementById("cartItems");
          const totalPrice = document.getElementById("totalPrice");

          if (!cartItems || !totalPrice) return;

          if (cart.length === 0) {
              cartItems.innerHTML = '<p class="text-muted text-center">Keranjang kosong</p>';
              totalPrice.textContent = "Rp 0";
              updatePaymentCalculation();
              return;
          }

          cartItems.innerHTML = cart.map((item) => `
              <div class="cart-item">
                  <div class="d-flex justify-content-between align-items-center">
                      <div>
                          <h6 class="mb-1">${item.name}</h6>
                          <small class="text-muted">${formatCurrency(item.price)} each</small>
                      </div>
                      <div class="d-flex align-items-center gap-2">
                          <button class="btn btn-sm btn-outline-danger" onclick="updateQuantity('${item.id}', -1)">-</button>
                          <span class="fw-bold">${item.quantity}</span>
                          <button class="btn btn-sm btn-outline-success" onclick="updateQuantity('${item.id}', 1)">+</button>
                      </div>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mt-2">
                      <span class="fw-bold">${formatCurrency(item.price * item.quantity)}</span>
                      <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart('${item.id}')">
                          <i class="bi bi-trash"></i>
                      </button>
                  </div>
              </div>
          `).join("");

          const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
          totalPrice.textContent = formatCurrency(total);
          updatePaymentCalculation();
      }

      /**
       * Memperbarui jumlah produk di keranjang
       */
      function updateQuantity(productId, change) {
          const item = cart.find((item) => item.id === productId);
          if (item) {
              const product = products.find(p => p.id === productId);
              const newQuantity = item.quantity + change;
              
              if (change > 0 && newQuantity > product.stock) {
                  showToast('Stok tidak mencukupi', 'warning');
                  return;
              }
              
              item.quantity = newQuantity;
              if (item.quantity <= 0) {
                  removeFromCart(productId);
              } else {
                  updateCartDisplay();
              }
          }
      }

      /**
       * Menghapus produk dari keranjang
       */
      function removeFromCart(productId) {
          const item = cart.find(item => item.id === productId);
          cart = cart.filter((item) => item.id !== productId);
          updateCartDisplay();
          if (item) {
              showToast(`${item.name} dihapus dari keranjang`, 'info');
          }
      }

      /**
       * Mengosongkan keranjang
       */
      function clearCart() {
          cart = [];
          paidAmount = 0;
          updateCartDisplay();
          showToast('Keranjang dikosongkan', 'info');
      }

      // ==================== FUNGSI DATABASE OFFLINE ====================
      /**
       * Inisialisasi IndexedDB untuk penyimpanan offline
       */
      function initDatabase() {
          const request = indexedDB.open("POSDatabase", 2);

          request.onerror = function (event) {
              console.error("Database error: " + event.target.errorCode);
          };

          request.onsuccess = function (event) {
              db = event.target.result;
              dbInitialized = true;
              console.log("Database initialized successfully");
              startSyncInterval();
          };

          request.onupgradeneeded = function (event) {
              db = event.target.result;
              
              if (db.objectStoreNames.contains('transactions')) {
                  db.deleteObjectStore('transactions');
              }
              
              const objectStore = db.createObjectStore("transactions", {
                  keyPath: "id",
                  autoIncrement: true,
              });
              objectStore.createIndex("timestamp", "timestamp", { unique: false });
              objectStore.createIndex("synced", "synced", { unique: false });
              console.log("Database setup complete");
          };
      }

      /**
       * Menyimpan transaksi ke IndexedDB
       */
      function saveTransactionToDB(transaction) {
          return new Promise((resolve, reject) => {
              if (!dbInitialized) {
                  reject("Database not initialized");
                  return;
              }

              const transactionDB = db.transaction(["transactions"], "readwrite");
              const objectStore = transactionDB.objectStore("transactions");
              const request = objectStore.add({
                  ...transaction,
                  timestamp: new Date().toISOString(),
                  synced: false,
              });

              request.onsuccess = function () {
                  console.log("Transaction saved to IndexedDB:", transaction.id);
                  resolve();
              };

              request.onerror = function (event) {
                  console.error("Error saving transaction: " + event.target.errorCode);
                  reject(event.target.errorCode);
              };
          });
      }

      /**
       * Mendapatkan transaksi yang belum tersinkronisasi
       */
      function getUnsyncedTransactions() {
          return new Promise((resolve, reject) => {
              if (!dbInitialized) {
                  reject("Database not initialized");
                  return;
              }

              try {
                  const transaction = db.transaction(["transactions"], "readonly");
                  const objectStore = transaction.objectStore("transactions");
                  
                  const request = objectStore.getAll();

                  request.onsuccess = function () {
                      const allTransactions = request.result;
                      const unsynced = allTransactions.filter(trans => trans.synced === false);
                      console.log(`Found ${unsynced.length} unsynced transactions`);
                      resolve(unsynced);
                  };

                  request.onerror = function (event) {
                      reject("Error getting transactions: " + event.target.errorCode);
                  };
              } catch (error) {
                  reject("Error accessing database: " + error.message);
              }
          });
      }

      /**
       * Menandai transaksi sebagai tersinkronisasi
       */
      function markTransactionAsSynced(id) {
          return new Promise((resolve, reject) => {
              if (!dbInitialized) {
                  reject("Database not initialized");
                  return;
              }

              const transaction = db.transaction(["transactions"], "readwrite");
              const objectStore = transaction.objectStore("transactions");
              const request = objectStore.get(id);

              request.onsuccess = function () {
                  const data = request.result;
                  if (data) {
                      data.synced = true;
                      const updateRequest = objectStore.put(data);

                      updateRequest.onsuccess = function () {
                          console.log("Transaction marked as synced:", id);
                          resolve();
                      };

                      updateRequest.onerror = function (event) {
                          reject("Error updating transaction: " + event.target.errorCode);
                      };
                  } else {
                      reject("Transaction not found");
                  }
              };

              request.onerror = function (event) {
                  reject("Error getting transaction: " + event.target.errorCode);
              };
          });
      }

      /**
       * Sinkronisasi transaksi ke server
       */
      async function syncToServer() {
          if (!dbInitialized) {
              console.log("Database not ready, skipping sync");
              return;
          }

          try {
              const unsyncedTransactions = await getUnsyncedTransactions();

              if (unsyncedTransactions.length === 0) {
                  updateSyncStatus("Tersinkron", "success");
                  return;
              }

              updateSyncStatus(`Menyinkronkan ${unsyncedTransactions.length} data...`, "warning");

              const transactionsToSync = unsyncedTransactions.map(trans => ({
                  transaction_id: trans.id,
                  total_amount: trans.total,
                  paid_amount: trans.paid,
                  change_amount: trans.change,
                  items: trans.items,
                  transaction_date: trans.timestamp
              }));

              console.log("Sending transactions to server:", transactionsToSync);

              const param = {
                  url: '/transactions/batch',
                  method: 'POST',
                  data: JSON.stringify({
                      transactions: transactionsToSync
                  })
              };

              const result = await transAjax(param);
              
              if (result && result.success) {
                  for (const transaction of unsyncedTransactions) {
                      await markTransactionAsSynced(transaction.id);
                  }
                  
                  updateSyncStatus(`Tersinkron (${result.saved_count} data)`, "success");
                  console.log("All transactions synced successfully");
                  
                  if (result.saved_count > 0) {
                      setTimeout(() => {
                          showToast(`Berhasil sinkron ${result.saved_count} transaksi`, 'success');
                      }, 1000);
                  }
                  
              } else {
                  throw new Error(result?.message || 'Sync failed');
              }

          } catch (error) {
              console.error("Sync error:", error);
              updateSyncStatus("Gagal sinkron", "danger");
              showToast('Gagal menyinkronkan data transaksi', 'error');
          }
      }

      /**
       * Memperbarui status sinkronisasi di UI
       */
      function updateSyncStatus(message, type) {
          const syncStatus = document.getElementById("syncStatus");
          if (!syncStatus) return;
          
          syncStatus.innerHTML = `<i class="bi bi-arrow-repeat me-1"></i> ${message}`;
          syncStatus.className = `sync-status bg-${type} text-white`;

          if (type === "success") {
              setTimeout(() => {
                  syncStatus.classList.add("d-none");
              }, 5000);
          } else {
              syncStatus.classList.remove("d-none");
          }
      }

      /**
       * Memulai interval sinkronisasi
       */
      function startSyncInterval() {
          setTimeout(() => {
              syncToServer();
          }, 3000);
          
          syncInterval = setInterval(syncToServer, 3 * 60 * 1000);
      }

      // ==================== FUNGSI KALKULATOR ====================
      /**
       * Menambahkan nilai ke kalkulator
       */
      function addToCalc(value) {
          if (calculatorValue === "0") {
              calculatorValue = value;
          } else {
              calculatorValue += value;
          }
          updateCalculatorDisplay();
      }

      /**
       * Mengosongkan kalkulator
       */
      function clearCalc() {
          calculatorValue = "0";
          updateCalculatorDisplay();
      }

      /**
       * Menghapus karakter terakhir di kalkulator
       */
      function deleteCalc() {
          calculatorValue = calculatorValue.slice(0, -1) || "0";
          updateCalculatorDisplay();
      }

      /**
       * Menghitung hasil kalkulator
       */
      function calculateResult() {
          try {
              calculatorValue = eval(calculatorValue.replace("×", "*").replace("÷", "/")).toString();
          } catch (e) {
              calculatorValue = "0";
          }
          updateCalculatorDisplay();
      }

      /**
       * Memperbarui tampilan kalkulator
       */
      function updateCalculatorDisplay() {
          const calcDisplay = document.getElementById("calcDisplay");
          const calcValue = document.getElementById("calcValue");
          
          if (calcDisplay) calcDisplay.textContent = calculatorValue;
          if (calcValue) {
              const numValue = parseFloat(calculatorValue) || 0;
              calcValue.textContent = formatCurrency(numValue);
          }
      }

      /**
       * Mengatur pembayaran dari nilai kalkulator
       */
      function setPaymentFromCalc() {
          paidAmount = parseFloat(calculatorValue) || 0;
          updatePaymentCalculation();
      }

      // ==================== FUNGSI PEMBAYARAN ====================
      /**
       * Memperbarui perhitungan pembayaran
       */
      function updatePaymentCalculation() {
          const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
          const change = paidAmount - total;

          document.getElementById("totalBelanjaDisplay").textContent = formatCurrency(total);
          document.getElementById("kembalianAmount").textContent = formatCurrency(Math.max(0, change));
      }

      /**
       * Memproses pembayaran
       */
      function processPayment() {
          const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

          if (cart.length === 0) {
              showToast("Keranjang kosong!", "warning");
              return;
          }

          // Tampilkan modal pembayaran
          const modalElement = document.getElementById('modalPembayaran');
          if (modalElement) {
              // Perbarui tampilan total belanja
              document.getElementById("totalBelanjaDisplay").textContent = formatCurrency(total);
              
              const modalPembayaran = new bootstrap.Modal(modalElement);
              modalPembayaran.show();
          }
      }

      /**
       * Menyelesaikan proses pembayaran
       */
      async function prosesPembayaran() {
          const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
          const nominalBayar = parseInt(document.getElementById("nominalBayar").value) || 0;
          const customerName = document.getElementById("customerName").value || "Umum";
          const paymentMethod = document.getElementById("paymentMethod").value || "tunai";

          if (nominalBayar === 0) {
              document.getElementById("errorMessage").style.display = "block";
              return;
          }

          if (nominalBayar < total) {
              showToast('Nominal bayar kurang dari total belanja!', 'warning');
              return;
          }

          // Create transaction object dengan format untuk thermal printer
          const transaction = {
              id: 'TXN_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5).toUpperCase(),
              items: [...cart],
              total: total,
              paid: nominalBayar,
              change: nominalBayar - total,
              timestamp: new Date().toISOString(),
              date: new Date().toLocaleString("id-ID"),
              customer: customerName,
              payment_method: paymentMethod,
              order: {
                  transaction_number: 'TXN' + Date.now(),
                  payment_method: { name: paymentMethod.toUpperCase() },
                  cash_received: nominalBayar,
                  change: nominalBayar - total
              },
              store: {
                  name: "BAKSO RUDY",
                  address: "Depan Terminal Induk \n Kota Menggala",
                  phone: "081369970183"
              }
          };

          try {
              // Simpan data transaksi untuk thermal printer
              currentTransactionData = transaction;
              
              // Save to IndexedDB
              await saveTransactionToDB(transaction);
              
              // Show success modal
              const modalElement = document.getElementById('successModal');
              if (modalElement) {
                  const successModal = new bootstrap.Modal(modalElement);
                  successModal.show();
              }
              
              generateReceipt();
              
              // Trigger sync immediately after payment
              setTimeout(() => {
                  syncToServer();
              }, 2000);
              
              showToast('Pembayaran berhasil diproses!', 'success');
              
              // Tutup modal pembayaran
              const modalPembayaran = bootstrap.Modal.getInstance(document.getElementById('modalPembayaran'));
              if (modalPembayaran) modalPembayaran.hide();
              
              // Clear cart after successful payment
              clearCart();
              
          } catch (error) {
              console.error('Error processing payment:', error);
              showToast('Gagal memproses pembayaran', 'error');
          }
      }

      /**
       * Menetapkan nominal pembayaran
       */
      function setNominal(amount) {
          document.getElementById("nominalBayar").value = amount;
          hitungKembalian();
      }

      /**
       * Menghitung kembalian
       */
      function hitungKembalian() {
          const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
          const nominalBayar = parseInt(document.getElementById("nominalBayar").value) || 0;
          const kembalian = nominalBayar - total;

          if (kembalian >= 0) {
              document.getElementById("kembalianAmount").textContent = formatCurrency(kembalian);
              document.getElementById("errorMessage").style.display = "none";
          } else {
              document.getElementById("kembalianAmount").textContent = formatCurrency(0);
          }
      }

      // ==================== FUNGSI STRUK ====================
      /**
       * Membuat struk untuk modal
       */
      function generateReceipt() {
          const now = new Date();
          const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
          const nominalBayar = parseInt(document.getElementById("nominalBayar").value) || 0;
          const change = nominalBayar - total;
          const customerName = document.getElementById("customerName").value || "Umum";
          const paymentMethod = document.getElementById("paymentMethod").value || "tunai";

          const receiptHTML = `
              <div class="text-center mb-3">
                  <h4>BAKSO RUDY</h4>
                  <small>Depan Terminal Induk Kota Menggala<br>Tel: 081369970183</small>
              </div>
              <hr>
              <div class="text-center mb-2">
                  <small>No: ${Math.random().toString(36).substr(2, 9).toUpperCase()}<br>
                  ${now.toLocaleDateString("id-ID")} ${now.toLocaleTimeString("id-ID")}</small>
              </div>
              <hr>
              <div class="mb-2">
                  <strong>Customer:</strong> ${customerName}<br>
                  <strong>Metode Bayar:</strong> ${paymentMethod.toUpperCase()}
              </div>
              <hr>
              ${cart.map((item) => `
                  <div class="d-flex justify-content-between">
                      <div>${item.name}<br><small>${item.quantity} x ${formatCurrency(item.price)}</small></div>
                      <div>${formatCurrency(item.price * item.quantity)}</div>
                  </div>
              `).join("<br>")}
              <hr>
              <div class="d-flex justify-content-between"><strong>TOTAL</strong><strong>${formatCurrency(total)}</strong></div>
              <div class="d-flex justify-content-between"><span>BAYAR</span><span>${formatCurrency(nominalBayar)}</span></div>
              <div class="d-flex justify-content-between"><span>KEMBALI</span><span>${formatCurrency(change)}</span></div>
              <hr>
              <div class="text-center"><small>TERIMA KASIH<br>ATAS KUNJUNGAN ANDA</small></div>
          `;

          const receiptContent = document.getElementById("receiptContent");
          if (receiptContent) {
              receiptContent.innerHTML = receiptHTML;
          }
      }

      /**
       * Mencetak konten struk
       */
      function printReceiptContent() {
          window.print();
      }

      // ==================== FUNGSI UTILITAS UI ====================
      /**
       * Menampilkan notifikasi toast
       */
      function showToast(message, type = 'info') {
          let toastContainer = document.getElementById('toastContainer');
          if (!toastContainer) {
              toastContainer = document.createElement('div');
              toastContainer.id = 'toastContainer';
              toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
              document.body.appendChild(toastContainer);
          }

          const toastId = 'toast-' + Date.now();
          const toastHtml = `
              <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0" role="alert">
                  <div class="d-flex">
                      <div class="toast-body">
                          ${message}
                      </div>
                      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                  </div>
              </div>
          `;

          toastContainer.insertAdjacentHTML('beforeend', toastHtml);
          
          const toastElement = document.getElementById(toastId);
          const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
          toast.show();

          toastElement.addEventListener('hidden.bs.toast', () => {
              toastElement.remove();
          });
      }

      /**
       * Format mata uang
       */
      function formatCurrency(amount) {
          return new Intl.NumberFormat("id-ID", {
              style: "currency",
              currency: "IDR",
              minimumFractionDigits: 0,
              maximumFractionDigits: 0,
          }).format(amount);
      }

      const fullscreenIcon = document.getElementById("fullscreenIcon");
      const fullscreenTooltip = document.getElementById("fullscreenTooltip");
      const fullscreenToggle = document.getElementById("fullscreenToggle");

      if (!document.fullscreenElement) {
        if (document.documentElement.requestFullscreen) {
          document.documentElement.requestFullscreen();
        } else if (document.documentElement.webkitRequestFullscreen) {
          document.documentElement.webkitRequestFullscreen();
        } else if (document.documentElement.mozRequestFullScreen) {
          document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.msRequestFullscreen) {
          document.documentElement.msRequestFullscreen();
        }
      } else {
        if (document.exitFullscreen) {
          document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
          document.webkitExitFullscreen();
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
        } else if (document.msExitFullscreen) {
          document.msExitFullscreen();
        }
      }

      // ==================== EVENT LISTENERS ====================
      // Search functionality
      document.getElementById("searchInput")?.addEventListener("input", displayProducts);

      // Hitung kembalian saat nominal bayar berubah
      document.getElementById("nominalBayar")?.addEventListener("input", hitungKembalian);
    </script>
  </body>
</html>