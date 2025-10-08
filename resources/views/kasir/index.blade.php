<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta>
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
        width: 50%;
        bottom: 0;
        z-index: 100;
        background: white;
        border-radius: 16px;
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
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <!-- Kolom Produk -->
        <div class="col-lg-8 main-content">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="primary-text fw-bold">Kasir App</h2>
            <div class="d-flex gap-2">
              <button 
                id="connectPrinterBtn" 
                class="btn btn-outline-primary"
                title="Sambungkan ke Printer Thermal"
              >
                <i class="bi bi-printer me-2"></i>Connect Printer
              </button>
              <div class="input-group" style="width: 300px">
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

          <!-- Grid Produk -->
          <div class="row" id="productGrid">
            <!-- Products will be generated by JavaScript -->
          </div>
        </div>

        <!-- Kolom Keranjang -->
        <div class="col-lg-4 sidebar p-0">
          <div class="p-4">
            <h4 class="primary-text fw-bold mb-4">Keranjang Belanja</h4>

            <!-- Cart Items -->
            <div
              id="cartItems"
              class="mb-4"
              style="max-height: 300px; overflow-y: auto"
            >
              <p class="text-muted text-center">Keranjang kosong</p>
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

            <!-- Kalkulator Pembayaran -->
            <div class="calculator mt-4">
              <div class="calc-display" id="calcDisplay">0</div>

              <div class="row g-2">
                <div class="col-3">
                  <button class="calc-btn operator" onclick="clearCalc()">
                    C
                  </button>
                </div>
                <div class="col-3">
                  <button class="calc-btn operator" onclick="deleteCalc()">
                    ⌫
                  </button>
                </div>
                <div class="col-3">
                  <button class="calc-btn operator" onclick="addToCalc('/')">
                    ÷
                  </button>
                </div>
                <div class="col-3">
                  <button class="calc-btn operator" onclick="addToCalc('*')">
                    ×
                  </button>
                </div>

                <div class="col-3">
                  <button class="calc-btn number" onclick="addToCalc('9')">
                    9
                  </button>
                </div>
                <div class="col-3">
                  <button class="calc-btn number" onclick="addToCalc('8')">
                    8
                  </button>
                </div>
                <div class="col-3">
                  <button class="calc-btn number" onclick="addToCalc('7')">
                    7
                  </button>
                </div>
                <div class="col-3">
                  <button class="calc-btn operator" onclick="addToCalc('-')">
                    -
                  </button>
                </div>

                <div class="col-3">
                  <button class="calc-btn number" onclick="addToCalc('6')">
                    6
                  </button>
                </div>
                <div class="col-3">
                  <button class="calc-btn number" onclick="addToCalc('5')">
                    5
                  </button>
                </div>
                <div class="col-3">
                  <button class="calc-btn number" onclick="addToCalc('4')">
                    4
                  </button>
                </div>
                <div class="col-3">
                  <button class="calc-btn operator" onclick="addToCalc('+')">
                    +
                  </button>
                </div>
                <div class="col-3">
                  <button class="calc-btn number" onclick="addToCalc('3')">
                    3
                  </button>
                </div>
                <div class="col-3">
                  <button class="calc-btn number" onclick="addToCalc('2')">
                    2
                  </button>
                </div>
                <div class="col-3">
                  <button class="calc-btn number" onclick="addToCalc('1')">
                    1
                  </button>
                </div>
                <div class="col-3">
                  <button class="calc-btn operator" onclick="calculateResult()">
                    =
                  </button>
                </div>
                <div class="col-3">
                  <button class="calc-btn number" onclick="addToCalc('0')">
                    0
                  </button>
                </div>
                <div class="col-3">
                  <button class="calc-btn number" onclick="addToCalc('00')">
                    00
                  </button>
                </div>
                <div class="col-3">
                  <button class="calc-btn number" onclick="addToCalc('000')">
                    000
                  </button>
                </div>
              </div>
              <div class="mt-3">
                <button
                  class="btn btn-lg btn-outline-primary w-100 mb-2"
                  onclick="setPaymentFromCalc()"
                >
                  Set Pembayaran: <span id="calcValue">Rp 0</span>
                </button>

                <div class="d-flex justify-content-between">
                  <span>Dibayar:</span>
                  <span id="paidAmount" class="fw-bold">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between">
                  <span>Kembalian:</span>
                  <span id="changeAmount" class="fw-bold text-danger"
                    >Rp 0</span
                  >
                </div>
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
              <button
                class="btn btn-outline-danger w-100"
                onclick="clearCart()"
              >
                <i class="bi bi-trash me-2"></i>Clear Keranjang
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

    <!-- Modal Konfirmasi Transaksi -->
    <div class="modal fade" id="modalAksi" tabindex="-1" aria-labelledby="modalAksiLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <div class="modal-body text-center p-4">
            <div class="mb-3">
              <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
            </div>
            <h5 class="modal-title fw-bold mb-2" id="modalAksiLabel">
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

      // Initialize app
      window.onload = function () {
          initDatabase();
          getProducts();
          updateCartDisplay();
          updateCalculatorDisplay();
          initPrinterConnection();
      };

      // ==================== PRINTER FUNCTIONS ====================
      function initPrinterConnection() {
          const connectButton = document.getElementById('connectPrinterBtn');
          if (connectButton) {
              connectButton.addEventListener('click', async () => {
                  await connectToPrinter();
              });
          }
          updatePrinterStatus();
      }

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

      function updatePrinterStatus(message = '', type = 'secondary') {
          const printerStatus = document.getElementById("printerStatus");
          const connectBtn = document.getElementById("connectPrinterBtn");
          
          if (!printerStatus) return;

          if (connectedPrinter) {
              printerStatus.innerHTML = `<i class="bi bi-printer me-1"></i> Printer Connected`;
              printerStatus.className = `printer-status bg-success text-white`;
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
              const modal = bootstrap.Modal.getInstance(document.getElementById('modalAksi'));
              if (modal) modal.hide();
              
          } catch (error) {
              console.error('Gagal mencetak struk:', error);
              showToast('Gagal mencetak struk: ' + error.message, 'error');
          }
      }

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

      function generateThermalReceiptContent(data) {
          let receipt = "\x1B\x40"; // Reset printer
          receipt += "\x1B\x61\x01"; // Perataan Tengah
          receipt += "\x1B\x21\x10"; // Text tebal dan besar
          receipt += (data.store?.name || "TOKO SUMBER REJEKI") + "\n";
          receipt += "\x1B\x21\x00"; // Normal text
          receipt += (data.store?.address || "Jl. Contoh No. 123, Jakarta") + "\n";
          receipt += "Telp: " + (data.store?.phone || "021-1234567") + "\n";
          receipt += "================================\n";
          receipt += "\x1B\x61\x00"; // Kembalikan ke rata kiri

          // Detail Transaksi
          receipt += "Kode Transaksi: " + (data.order?.transaction_number || data.transactionId || 'N/A') + "\n";
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
          receipt += "================================\n";
          receipt += "\x1B\x61\x00"; // Kembalikan ke rata kiri
          receipt += "\x1D\x56\x00"; // ESC/POS cut paper

          return receipt;
      }

      async function sendChunks(characteristic, data) {
          const chunkSize = 180; // BLE limit
          let offset = 0;
          while (offset < data.length) {
              let chunk = data.slice(offset, offset + chunkSize);
              await characteristic.writeValue(chunk);
              offset += chunkSize;
          }
      }

      function formatRibuan(number) {
          let n = typeof number === 'string' ? parseFloat(number) : number;
          return n.toLocaleString("id-ID");
      }

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

      // ==================== EXISTING POS FUNCTIONS ====================
      // (Semua fungsi existing tetap sama, hanya ditambahkan integrasi printer)
      
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
      
      // Initialize IndexedDB
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

      // Save transaction to IndexedDB
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

      // Mark transaction as synced
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

      // Sync transactions to server
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

      // Update sync status display
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

      // Show toast notification
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

      // Start sync interval (every 3 minutes)
      function startSyncInterval() {
          setTimeout(() => {
              syncToServer();
          }, 3000);
          
          syncInterval = setInterval(syncToServer, 3 * 60 * 1000);
      }

      // Display products
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

      // Filter products by category
      function filterProducts(category) {
          currentCategory = category;
          document.querySelectorAll(".category-btn").forEach((btn) => btn.classList.remove("active"));
          event.target.classList.add("active");
          displayProducts();
      }

      // Add to cart
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

      // Update cart display
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

      // Update quantity
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

      // Remove from cart
      function removeFromCart(productId) {
          const item = cart.find(item => item.id === productId);
          cart = cart.filter((item) => item.id !== productId);
          updateCartDisplay();
          if (item) {
              showToast(`${item.name} dihapus dari keranjang`, 'info');
          }
      }

      // Clear cart
      function clearCart() {
          cart = [];
          paidAmount = 0;
          updateCartDisplay();
          showToast('Keranjang dikosongkan', 'info');
      }

      // Calculator functions
      function addToCalc(value) {
          if (calculatorValue === "0") {
              calculatorValue = value;
          } else {
              calculatorValue += value;
          }
          updateCalculatorDisplay();
      }

      function clearCalc() {
          calculatorValue = "0";
          updateCalculatorDisplay();
      }

      function deleteCalc() {
          calculatorValue = calculatorValue.slice(0, -1) || "0";
          updateCalculatorDisplay();
      }

      function calculateResult() {
          try {
              calculatorValue = eval(calculatorValue.replace("×", "*").replace("÷", "/")).toString();
          } catch (e) {
              calculatorValue = "0";
          }
          updateCalculatorDisplay();
      }

      function updateCalculatorDisplay() {
          document.getElementById("calcDisplay").textContent = calculatorValue;
          const numValue = parseFloat(calculatorValue) || 0;
          document.getElementById("calcValue").textContent = formatCurrency(numValue);
      }

      function setPaymentFromCalc() {
          paidAmount = parseFloat(calculatorValue) || 0;
          updatePaymentCalculation();
      }

      function updatePaymentCalculation() {
          const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
          const change = paidAmount - total;

          document.getElementById("paidAmount").textContent = formatCurrency(paidAmount);
          document.getElementById("changeAmount").textContent = formatCurrency(Math.max(0, change));
      }

      // Process payment - UPDATED untuk menyimpan data transaksi
      async function processPayment() {
          const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

          if (cart.length === 0) {
              alert("Keranjang kosong!");
              return;
          }

          if (paidAmount < total) {
              alert("Pembayaran kurang! Silakan masukkan jumlah yang tepat.");
              return;
          }

          // Create transaction object dengan format untuk thermal printer
          const transaction = {
              id: 'TXN_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5).toUpperCase(),
              items: [...cart],
              total: total,
              paid: paidAmount,
              change: paidAmount - total,
              timestamp: new Date().toISOString(),
              date: new Date().toLocaleString("id-ID"),
              order: {
                  transaction_number: 'TXN' + Date.now(),
                  payment_method: { name: "TUNAI" },
                  cash_received: paidAmount,
                  change: paidAmount - total
              },
              store: {
                  name: "TOKO SUMBER REJEKI",
                  address: "Jl. Contoh No. 123, Jakarta",
                  phone: "021-1234567"
              }
          };

          try {
              // Simpan data transaksi untuk thermal printer
              currentTransactionData = transaction;
              
              // Save to IndexedDB
              await saveTransactionToDB(transaction);
              
              // Show success modal
              const modalElement = document.getElementById('modalAksi');
              if (modalElement) {
                  const modalAksi = new bootstrap.Modal(modalElement);
                  modalAksi.show();
              }
              
              generateReceipt();
              
              // Trigger sync immediately after payment
              setTimeout(() => {
                  syncToServer();
              }, 2000);
              
              showToast('Pembayaran berhasil diproses!', 'success');
              
              // Clear cart after successful payment
              clearCart();
              
          } catch (error) {
              console.error('Error processing payment:', error);
              showToast('Gagal memproses pembayaran', 'error');
          }
      }

      // Generate receipt untuk modal
      function generateReceipt() {
          const now = new Date();
          const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
          const change = paidAmount - total;

          const receiptHTML = `
              <div class="text-center mb-3">
                  <h4>TOKO SUMBER REJEKI</h4>
                  <small>Jl. Contoh No. 123, Jakarta<br>Tel: 021-1234567</small>
              </div>
              <hr>
              <div class="text-center mb-2">
                  <small>No: ${Math.random().toString(36).substr(2, 9).toUpperCase()}<br>
                  ${now.toLocaleDateString("id-ID")} ${now.toLocaleTimeString("id-ID")}</small>
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
              <div class="d-flex justify-content-between"><span>BAYAR</span><span>${formatCurrency(paidAmount)}</span></div>
              <div class="d-flex justify-content-between"><span>KEMBALI</span><span>${formatCurrency(change)}</span></div>
              <hr>
              <div class="text-center"><small>TERIMA KASIH<br>ATAS KUNJUNGAN ANDA</small></div>
          `;

          const receiptContent = document.getElementById("receiptContent");
          if (receiptContent) {
              receiptContent.innerHTML = receiptHTML;
          }
      }

      // Print receipt modal
      function printReceipt() {
          generateReceipt();
          const modal = new bootstrap.Modal(document.getElementById("receiptModal"));
          modal.show();
      }

      function printReceiptContent() {
          window.print();
      }

      // Search functionality
      document.getElementById("searchInput")?.addEventListener("input", displayProducts);

      // Format currency
      function formatCurrency(amount) {
          return new Intl.NumberFormat("id-ID", {
              style: "currency",
              currency: "IDR",
              minimumFractionDigits: 0,
              maximumFractionDigits: 0,
          }).format(amount);
      }
    </script>
  </body>
</html>