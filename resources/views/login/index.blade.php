<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ url('/favicon.png') }}">
 <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ url('/favicon.png') }}" />
    <meta
      name="description"
      content="Bakso Rudy - Semangkuk kehangatan dari Bakso Rudy! Sempurna untuk hari yang butuh penyemangat"
    />

    <!-- Open Graph Meta Tags -->
    <meta
      property="og:title"
      content="Bakso Rudy - Semangkuk kehangatan dari Bakso Rudy! Sempurna untuk hari yang butuh penyemangat"
    />
    <meta
      property="og:description"
      content="Bakso Rudy - Semangkuk kehangatan dari Bakso Rudy! Sempurna untuk hari yang butuh penyemangat"
    />
    <meta property="og:image" content="{{ asset('assets/images/og_image.png') }}" />
    <meta property="og:type" content="website" />
  <title>
    Login 
  </title>
  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
  <meta name="theme-color" content="#00582B">
  <link rel="manifest" href="{{ asset('manifest.json') }}">
</head>

<body class="">
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
              <div class="card card-plain">
                <div class="card-header pb-0 text-start">
                  <h4 class="font-weight-bolder">Masuk dulu yuk</h4>
                  <p class="mb-0">Silakan masukkan email dan password kamu ya</p>
                </div>
                <div class="card-body">
                 @if(session('error'))
    <div class="alert alert-danger text-white">
        {{ session('error') }}
    </div>
@endif
                  <form action="{{ route('login-post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                      <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Email" aria-label="Email">
                      @error('email')
                         <div id="validationServer03Feedback" class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                    <div class="mb-3">
                      <input type="password" name="password" class="form-control form-control-lg  @error('password') is-invalid @enderror" placeholder="Password" aria-label="Password">
                      @error('password')
                          <div id="validationServer03Feedback" class="invalid-feedback">
                           {{ $message }}
                        </div>
                      @enderror
                    </div>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="rememberMe">
                      <label class="form-check-label" for="rememberMe">Ingat saya</label>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Masuk</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-4 text-sm mx-auto">
                    Kamu tidak memiliki akun?
                    <a href="javascript:;" class="text-primary text-gradient font-weight-bold">Daftar</a> dulu ya
                  </p>
                </div>
              </div>
            </div>
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
              <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signin-ill.jpg');
          background-size: cover;">
                <span class="mask bg-gradient-primary opacity-6"></span>
                <h4 class="mt-5 text-white font-weight-bolder position-relative">"Fokus pada Kecepatan & Efisiensi"</h4>
                <p class="text-white position-relative">Dari Transaksi ke Laporan, Semua di Genggaman.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <script>
    if (!navigator.serviceWorker.controller) {
        navigator.serviceWorker.register("/sw.js").then(function (reg) {
            console.log("Service worker has been registered for scope: " + reg.scope);
        });
    }
  </script>
</body>
</html>