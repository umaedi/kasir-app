<!DOCTYPE html>
<html lang="id" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Samudra Wasesa</title>

    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo/samudra-wasesa.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Belum dibuka */
        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control:before {
            content: "\f0fe" !important;
            /* fa-plus-square */
            font-family: "Font Awesome 6 Free";
            /* sesuaikan dengan versimu */
            font-weight: 900;
        }

        /* Sudah dibuka */
        table.dataTable.dtr-inline.collapsed>tbody>tr.parent>td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed>tbody>tr.parent>th.dtr-control:before {
            content: "\f146" !important;
            /* fa-minus-square */
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
        }
    </style>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
    @stack('css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <script>
        const currentTheme = localStorage.getItem('theme');
        if (currentTheme === 'dark') {
            document.body.classList.add('dark-mode');
        }
      </script>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            @include('cms.layouts.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                @include('cms.layouts.navbar')

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <!-- Footer -->
                    @include('cms.layouts.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
        {{-- <x-loadingPopup /> --}}
    </div>
    <!-- / Layout wrapper -->
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/sweetalert.min.js') }}"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- Page JS -->
    <script type="text/javascript">
        let btnSubmit = null;
        let btnLoading = null;

        async function transAjax(data) {
            html = null;
            data.headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            await $.ajax(data).done(function(res) {
                    html = res;
                })
                .fail(function() {
                    return false;
                })
            return html
        }

        $('form').on('submit', async function(e) {
            e.preventDefault();
            loading(true, btnSubmit, btnLoading);
            var form = $(this);
            var formData = new FormData(this);
            var method = form.attr('method') || 'POST';

            //config
            var reloadDataTable = form.data('table') === true;
            var reloadPage = form.data('reload') || false;
            var resetForm = form.data('resetForm');

            console.log('reloapage', reloadPage);
            
            var param = {
                url: form.attr('action'),
                method: method,
                data: formData,
                contentType: false,
                cache: false,
                processData: false
            };

            await transAjax(param).then((result) => {
                loading(false, btnSubmit, btnLoading);
                $(".modal").modal('hide');

                if(reloadPage) {
                    swal({
                        title: 'Berhasil',
                        text: result.message,
                        icon: 'success',
                        timer: 3000,
                    }).then(() => {
                        window.location.href = "{{ url()->current() }}";
                    });
                }else {
                    swal({
                        title: 'Berhasil',
                        text: result.message,
                        icon: 'success',
                    });
                }
                
                if (resetForm) {
                    form.trigger('reset');
                }

                if (reloadDataTable) {
                    dataTable.ajax.reload();
                }

            }).catch((err) => {
                console.log(err.message);

                switch (err.status) {
                    case 400:
                        badRequest(err)
                        console.error("Bad Request");
                        break;
                    case 401:
                        unauthorized(err);
                        console.log("Unauthorized - Silakan login kembali.");
                        break;
                    case 403:
                        forbidden(err);
                        console.error("Forbidden - Anda tidak memiliki akses.");
                        break;
                    case 404:
                        console.error("Not Found - Resource tidak ditemukan.");
                        notFound(err);
                        break;
                    case 422:
                        unprocessableEntity(err);
                        console.error("Unprocessable Entity - Validasi gagal.");
                        break;
                    case 500:
                        internalServerError(err);
                        console.error("Internal Server Error - Terjadi kesalahan di server.");
                        break;
                    default:
                        internalServerError(err);
                        console.error("Terjadi kesalahan yang tidak diketahui.");
                        break;
                }

                function badRequest(err) {
                    loading(false, btnSubmit, btnLoading);
                    const response = err.responseJSON || '';
                    swal({
                        title: "Oops!",
                        text: response.message ||
                            "Bad Request - Permintaan tidak dapat diproses.",
                        icon: 'error',
                    });
                }

                function unauthorized(err) {
                    swal({
                        title: "Sesi habis",
                        text: err.responseJSON.message,
                        icon: 'error',
                        timer: 3000,
                    }).then(() => {
                        window.location.href = '/';
                    });
                }

                function forbidden(err) {
                    loading(false, btnSubmit, btnLoading);
                    const response = err.responseJSON || '';

                    swal({
                        title: "Oops!",
                        text: response.message || "Forbidden - Anda tidak memiliki akses.",
                        icon: 'error',
                    });
                }

                function notFound(err) {
                    loading(false, btnSubmit, btnLoading);
                    const response = err.responseJSON || '';

                    swal({
                        title: "Oops!",
                        text: response.message || "Not Found - Data tidak ditemukan",
                        icon: 'error',
                    });
                }

                function unprocessableEntity(err) {
                    loading(false, btnSubmit, btnLoading);

                    const response = err.responseJSON || {};
                    const errors = response.errors;

                    if (errors) {
                        $.each(errors, function(key, value) {
                            const errorElement = $('#error-' + key);
                            if (errorElement.length) {
                                errorElement.html(value[0]);
                            }
                        });
                    } else {
                        swal({
                            title: "Oops!",
                            text: response.message || "Terjadi kesalahan validasi.",
                            icon: 'error',
                        });
                    }
                }

                function internalServerError(err) {
                    const response = err.responseJSON || '';
                    const errors = response?.errors || '';

                    loading(false, btnSubmit, btnLoading);
                    swal({
                        title: "Mohon Maaf!",
                        text: response.message || 'Internal Server Error!' + '\n' + errors,
                        icon: 'error',
                    });
                }
            });
        });

        async function update(url, message = 'Apakah Anda yakin untuk memperbaharui data ini?') {
            const willDelete = await swal({
                title: "Perbaharui?",
                text: message,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            });
            if (willDelete) {
                let param = {
                    url: url,
                    method: "PUT",
                    processData: false,
                    contentType: false,
                    cache: false,
                }

                await transAjax(param).then((response) => {
                    swal({
                        title: "Berhasil",
                        text: response.message,
                        icon: 'success',
                    });
                }).catch((error) => {
                    console.log(error);
                });
            }
        }

        async function hapus(url, message = 'Data yang dihapus tidak dapat dikembalikan') {
            const willDelete = await swal({
                title: "Hapus?",
                text: message,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            });
            if (willDelete) {
                let param = {
                    url: url,
                    method: "DELETE",
                    processData: false,
                    contentType: false,
                    cache: false,
                }

                // $("#loadingOverlay").removeClass('d-none');
                await transAjax(param).then((response) => {
                    // $("#loadingOverlay").addClass('d-none');
                    dataTable();
                    swal({
                        title: "Berhasil",
                        text: response.message,
                        icon: 'success',
                    });
                }).catch((error) => {
                    // $("#loadingOverlay").addClass('d-none');
                    console.log(error);
                });
            }
        }

        async function logOut() {
            const willLogout = await swal({
                title: "Apakah Anda yakin?",
                text: "Anda akan keluar dari aplikasi ini!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            });
            if (willLogout) {
                let param = {
                    url: '/wms/logout',
                    method: "POST",
                    processData: false,
                    contentType: false,
                    cache: false,
                }

                await transAjax(param).then((response) => {
                    swal({
                        title: "Sukses!",
                        text: response.message || 'Anda berhasil keluar',
                        icon: 'success',
                    }).then(() => {
                        window.location.href = '/';
                    });
                }).catch((error) => {
                    console.log(error);
                });
            }
        }

        function formatRupiah(value) {
            return Number(value).toLocaleString('id-ID', { 
                style: 'currency', 
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        function loading(state, submit, loading, callback) {

            console.log(submit, loading);

            btnSubmit = submit;
            btnLoading = loading;

            if (state) {
                $('#' + btnSubmit).addClass('d-none');
                $('#' + btnLoading).removeClass('d-none');
            } else {
                $('#' + btnSubmit).removeClass('d-none');
                $('#' + btnLoading).addClass('d-none');
            }
        }

        function setLang(lang)
        {
            localStorage.setItem("locale", lang);
        }

        function setDarkMode(color)
        {
            localStorage.setItem("locale", color);
        }
    </script>
    @stack('js')
</body>

</html>
