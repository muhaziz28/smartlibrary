@extends('auth.layouts.app')

@section('content')
<div class="card card-md">
    <div id="nim-check-feedback"></div>

    <div class="card-body">
        <form action="{{ route('checkNim') }}" method="post" id="checknim-form">
            <!-- card -->
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Cek NIM</h2>
            </div>
            @csrf
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" placeholder="Username..." name="nim" id="nim" autocomplete="off">
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Check NIM</button>
            </div>
        </form>

        <div id="registration-form-container" style="display: none;">
            <form method="POST" action="{{ route('registerm') }}" id="registration-form" disabled>
                @csrf
                <div class="card-body">
                    <h2 class="h2 text-center mb-4">Register</h2>
                </div>
                <div class="row mb-3">
                    <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('NIM') }}</label>

                    <div class="col-md-6">
                        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" readonly>

                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="fakultas_id" class="col-md-4 col-form-label text-md-end">{{ __('Fakultas') }}</label>
                    <div class="col-md-6">
                        <select name="fakultas_id" id="fakultas_select" class="form-control form-select col-md-6">
                        </select>

                        @error('fakultas_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="prodi_id" class="col-md-4 col-form-label text-md-end">{{ __('Prodi') }}</label>
                    <div class="col-md-6">
                        <select name="prodi_id" id="prodi_select" class="form-control form-select col-md-6">
                        </select>

                        @error('prodi_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Register') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="text-center text-muted mt-3">
    Have an account? <a href="{{ route('login') }}" tabindex="-1">Sign in</a>
</div>
@endsection

@section('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        console.log('test')
        $('#fakultas_select').select2({
            placeholder: "--Pilih Fakultas--",
            allowClear: true,
            ajax: {
                url: "{{ route('getFakultas') }}",
                type: "GET",
                dataType: 'json',
                data: function(params) {
                    console.log(params)
                    return {
                        search: params.term,
                    }
                },
                processResults: function(data) {
                    return {
                        results: $.map(data.data, function(item) {
                            return {
                                text: item.nama_fakultas,
                                id: item.id
                            }
                        })
                    }
                },
                cache: true
            }
        })

        $('#prodi_select').select2({
            placeholder: "--Pilih Prodi--",
            allowClear: true,
            ajax: {
                url: "{{ route('getProdi') }}",
                type: "GET",
                dataType: 'json',
                data: function(params) {
                    console.log(params)
                    return {
                        search: params.term,
                        fakultas: $('#fakultas_select').val()
                    }
                },
                processResults: function(data) {
                    return {
                        results: $.map(data.data, function(item) {
                            return {
                                text: item.nama_prodi,
                                id: item.id
                            }
                        })
                    }
                },
                cache: true
            }
        })

        $('#checknim-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var type = form.attr('method');
            var data = form.serialize();

            $.ajax({
                url: url,
                type: type,
                data: data,
                dataType: 'json',
                // ubah tombol submit menjadi loading
                beforeSend: function() {
                    $('#checknim-form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#nim-check-feedback').html('');
                    $('#checknim-form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        var message = response.message;

                        // Displaying some information from the message object
                        var successMessage = '<div class="alert alert-success">';
                        successMessage += '<p>NIM: ' + message.nim + '</p>';
                        successMessage += '<p>Nama Mahasiswa: ' + message.nama_mahasiswa + '</p>';
                        // Add more fields as needed
                        successMessage += '</div>';

                        // Set the HTML of the feedback container
                        $('#nim-check-feedback').html(successMessage);

                        // Enable the registration form
                        $('#checknim-form').hide();

                        // Show registration form container
                        $('#registration-form-container').show();

                        // Enable the registration form
                        $('#registration-form :input').prop('disabled', false);
                        $('#username').val(message.nim);
                    } else {
                        // Display error message
                        $('#nim-check-feedback').html('<div class="alert alert-danger">' + response.message + '</div>');

                        // Disable the registration form
                        $('#registration-form :input').prop('disabled', true);
                    }
                }
            })
        })
    })
</script>
@endsection