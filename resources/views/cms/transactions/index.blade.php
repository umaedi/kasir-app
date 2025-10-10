@extends('cms.layouts.app')
@push('css')
    <style>
        .gradient-card {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 50%, #60a5fa 100%);
            border: none;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .gradient-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: 1;
        }

        .gradient-card::after {
            content: '';
            position: absolute;
            bottom: -20%;
            right: -10%;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            z-index: 1;
        }

        .card-content {
            position: relative;
            z-index: 2;
        }

        .icon-container {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .icon-container i {
            font-size: 24px;
            color: white;
        }

        .card-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0;
            line-height: 1;
        }

        .card-label {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-12 mb-3">
            <div class="card gradient-card">
                <div class="card-body card-content">
                    <div class="d-flex align-items-center">
                        <div class="icon-container">
                            <i class="tf-icons fa-solid fa-arrow-down" style="font-size: 30px"></i>
                        </div>
                        <div>
                            <h2 class="card-number text-white" id="total_products">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </h2>
                            <p class="card-label">Pemasukan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12 mb-3">
            <div class="card gradient-card">
                <div class="card-body card-content">
                    <div class="d-flex align-items-center">
                        <div class="icon-container">
                            {{-- <i class='bx  bx-arrow-up-stroke'  ></i>  --}}
                            <i class="tf-icons fa-solid fa-arrow-up" style="font-size: 35px"></i>
                        </div>
                        <div>
                            <h2 class="card-number text-white" id="total_sales">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </h2>
                            <p class="card-label">Pengeluaran</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#transactionModal">Buat laporan</button>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">NO</th>
                        <th scope="col">NAMA ITEM</th>
                        <th scope="col">TOTAL PENJUALAN ITEM</th>
                        <th scope="col">HARGA PER ITEM</th>
                        <th scope="col">TOTAL PENJUALAN</th>
                        </tr>
                    </thead>
                    <tbody id="dataTable">
                         <x-skeleton-table  identifier="sales" />
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{ route('cms.laporan.store') }}" method="POST">
        @csrf
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Buat laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_laporan">Nama laporan</label>
                        <input type="text" name="nama_laporan" class="form-control">
                        <span class="text-danger" id="error-nama_laporan"></span>
                    </div>
                    <div class="mb-3">
                        <label for="nama_laporan">Kategori laporan</label>
                        <select name="kategori_laporan" id="kategori_laporan" class="form-select">
                            <option value="">--pilih kategori--</option>
                            <option value="pemasukan">pemasukan</option>
                            <option value="pengeluaran">pengeluaran</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nama_laporan">Satuan</label>
                        <select name="kategori_laporan" id="kategori_laporan" class="form-select" onchange="getValueSatuan(this.value)">
                            <option value="">--pilih satuan--</option>
                            <option value="kilogram">kilogram</option>
                            <option value="item">item</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nama_laporan">Jumlah</label>
                        <input type="number" name="nama_laporan" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="nama_laporan">Harga <span id="satuan"></span></label>
                        <input type="text" name="nama_laporan" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Catatan (opsional)</label>
                        <textarea name="catatan" id="catatn" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <x-btnLoading id="btnLoading" />
                    <x-btnSubmit id="btnSubmit" onclick="loading(true, 'btnSubmit','btnLoading')" />
                </div>
        </div>
    </div>
    </form>
</div>
@endsection
@push('js')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <script type="text/javascript">
        $(document).ready(async function() {
            getSummary();
        });

        async function getSummary()
        {
            let param = {
                url: '{{ url()->current() }}',
                method: 'GET',
                data: {
                    'load': 'summary'
                }
            }

            await transAjax(param).then((result) => {
                let data = result.metadata;
                console.log(data);
                $("#total_products").html(data.total_products || 0);
                $("#total_sales").html(formatRupiah(data.total_sales) || 0);
                $("#average_sale_per_product").html(formatRupiah(data.average_sale_per_product) || 0);
            }).catch((err) => {
               console.log(err);
            });
            getSales();
        };

        async function getSales()
        {
            let param = {
                url: '{{ url()->current() }}',
                method: 'GET',
                data: {
                    'load': 'sales'
                }
            }

            await transAjax(param).then((result) => {
                $(".skeleton-sales").addClass('d-none');
               $("#dataTable").html(result);
            }).catch((err) => {
                $(".skeleton-sales").addClass('d-none');
               console.log(err);
            });
        };

        function showItems(items)
        {
            let html = '';
            items.forEach((item, index) => {
                html += 
                `
                <tr>
                    <th scope="row">${index + 1}</th>
                    <td>${item.name}</td>
                    <td>${formatRupiah(item.price)}</td>
                </tr>
                `
            });
            $("#items").html(html);
            $("#itemsModal").modal("show");
        }

        function getValueSatuan(value)
        {
            $("#satuan").html('/ ' +value);
        }
    </script>
@endpush
