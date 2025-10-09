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

        .filter-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-4 col-12 mb-4">
            <div class="card gradient-card">
                <div class="card-body card-content">
                    <div class="d-flex align-items-center">
                        <div class="icon-container">
                            <i class="tf-icons bx bx-gift" style="font-size: 30px"></i>
                        </div>
                        <div>
                            <h2 class="card-number text-white" id="total_products">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </h2>
                            <p class="card-label">Total produk</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-12 mb-4">
            <div class="card gradient-card">
                <div class="card-body card-content">
                    <div class="d-flex align-items-center">
                        <div class="icon-container">
                            {{-- <i class='bx  bx-arrow-up-stroke'  ></i>  --}}
                            <i class="tf-icons bx bx-list-plus" style="font-size: 35px"></i>
                        </div>
                        <div>
                            <h2 class="card-number text-white" id="total_sales">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </h2>
                            <p class="card-label">Total penjualan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-12 mb-4">
            <div class="card gradient-card">
                <div class="card-body card-content">
                    <div class="d-flex align-items-center">
                        <div class="icon-container">
                            <i class="tf-icons fa-solid fa-arrow-up"></i>
                        </div>
                        <div>
                            <h2 class="card-number text-white" id="average_sale_per_product">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </h2>
                            <p class="card-label">Rata rata penjualan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <div class="modal fade" id="itemsModal" tabindex="-1" aria-labelledby="itemsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="itemsModalLabel">List Item</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama item</th>
                    <th scope="col">Harga</th>
                    </tr>
                </thead>
                <tbody id="items">
                   
                </tbody>
            </table>
        </div>
        </div>
    </div>
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
    </script>
@endpush
