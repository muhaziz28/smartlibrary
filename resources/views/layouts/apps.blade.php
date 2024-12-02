<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    @yield('meta')
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- CSS files -->
    <link href="{{ asset('dist/css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-payments.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/demo.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('cropper/cropper.css') }}">
    @stack('css')
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-selection--single {
            height: 36px !important;
            border: 1px solid #ced4da !important;
        }

        .select2-selection__rendered {
            color: #495057 !important;
        }

        .select2-selection__arrow {
            border-color: #495057 transparent transparent !important;
        }

        textarea {
            text-align: left;
            vertical-align: top;
        }

        .progress {
            display: none;
            margin-bottom: 1rem;
        }

        .alert {
            display: none;
        }

        .img-container img {
            max-width: 100%;
        }
    </style>
</head>

<body>
    <script src="{{ asset('dist/js/demo-theme.min.js') }}"></script>
    <div class="page">
        <!-- Navbar -->
        @include('layouts.header')
        @include('layouts.menubar')
        <div class="page-wrapper">

            @yield('content')
            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    Copyright &copy; 2024
                                    <a href="." class="link-secondary">Smart Library</a>.
                                    All rights reserved.
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class="link-secondary" rel="noopener">
                                        Kaalz
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('dist/libs/nouislider/dist/nouislider.min.js?1684106062') }}" defer></script>
    <script src="{{ asset('dist/libs/litepicker/dist/litepicker.js') }}" defer></script>
    <script src="{{ asset('dist/libs/tom-select/dist/js/tom-select.base.min.js?1684106062') }}" defer></script>
    <!-- Tabler Core -->

    <script src="{{ asset('dist/libs/tinymce/tinymce.min.js') }}" defer></script>
    <script src="{{ asset('dist/js/tabler.min.js') }}" defer></script>
    <script src="{{ asset('dist/js/demo.min.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <script src="{{ asset('cropper/cropper.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @include('layouts.modal')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let options = {
                selector: '#tinymce-mytextarea',
                height: 300,
                menubar: false,
                statusbar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | ' +
                    'bold italic backcolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat',
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; -webkit-font-smoothing: antialiased; }'
            }
            if (localStorage.getItem("tablerTheme") === 'dark') {
                options.skin = 'oxide-dark';
                options.content_css = 'dark';
            }
            tinyMCE.init(options);
        })
    </script>

</body>

</html>