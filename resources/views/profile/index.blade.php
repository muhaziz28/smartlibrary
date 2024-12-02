@extends('layouts.apps')

@section('title', 'Profile')

@section('content')
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <span class="avatar avatar-lg rounded" style="background-image: url(
                    <?php

                    use Illuminate\Support\Facades\Auth;

                    if (Auth::user()->profile_pict == null) {
                        echo asset('logo.png');
                    } else {
                        echo asset('media/' . Auth::user()->profile_pict);
                    }
                    ?>
                    )"></span>
            </div>
            <div class="col">
                <h1 class="fw-bold">
                    @if($userDosn)
                    {{ $userDosn->nama_dosen }}
                    @elseif($userMhs)
                    {{ $userMhs->nama_mahasiswa }}
                    @else
                    {{ Auth::user()->name }}
                    @endif
                </h1>
                <div class="my-2">
                    <!-- Unemployed. Building a $1M solo business while traveling the world. Currently at $400k/yr. -->
                </div>
                <div class="list-inline list-inline-dots text-muted">
                    <div class="list-inline-item">
                        <!-- Download SVG icon from http://tabler-icons.io/i/map -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 7l6 -3l6 3l6 -3l0 13l-6 3l-6 -3l-6 3l0 -13" />
                            <path d="M9 4l0 13" />
                            <path d="M15 7l0 13" />
                        </svg>
                        @if($userDosn)
                        {{ $userDosn->nama_dosen }}
                        @elseif($userMhs)
                        {{ $userMhs->nama_mahasiswa }}
                        @else
                        {{ Auth::user()->name }}
                        @endif
                    </div>
                    <div class="list-inline-item">
                        <!-- Download SVG icon from http://tabler-icons.io/i/mail -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                            <path d="M3 7l9 6l9 -6" />
                        </svg>
                        <a href="#" class="text-reset">
                            @if (Auth::user()->email == null)
                            <span class="badge bg-red-lt">Email belum ditambahkan</span>
                            @else
                            {{ Auth::user()->email }}
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="row g-0">

                <div class="col d-flex flex-column">
                    <div class="card-body">
                        <h2 class="mb-4">My Account</h2>
                        <h3 class="card-title">Profile Details</h3>
                        <div class="row align-items-center">
                            <div class="col-auto"><span class="avatar avatar-xl" style="background-image: url(
                                <?php

                                // use Illuminate\Support\Facades\Auth;

                                if (Auth::user()->profile_pict == null) {
                                    echo asset('logo.png');
                                } else {
                                    echo asset('media/' . Auth::user()->profile_pict);
                                }
                                ?>
                                )"></span>
                            </div>
                            <div class="col-auto">
                                <a class="btn" href="#" id="changePictureBtn">
                                    Change Picture
                                    <input type="file" id="profilePictureInput" style="display:none" accept="image/jpeg, image/png" />
                                </a>
                            </div>

                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                            </div>
                            <div class="alert" role="alert"></div>

                            <div class="col-auto"><a href="#" class="btn btn-ghost-danger deletePictureBtn" data-id="{{ Auth::user()->id }}">
                                    Delete Picture
                                </a>
                            </div>
                        </div>

                        <h3 class="card-title mt-4">Email</h3>
                        <p class="card-subtitle">
                            Email akan terlihat secara publik, jadi pastikan email yang kamu gunakan adalah email yang valid.
                        </p>
                        <div>
                            <div class="row g-2">
                                @if(Auth::user()->email != null)
                                <form action="{{ route('profile.addEmail') }}" method="POST" class="row g-2" id="email_modal_form">
                                    @csrf
                                    @method('PUT')
                                    <div class="col-auto">
                                        <input type="email" name="email" class="form-control w-auto" value="{{ Auth::user()->email }}" required>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                <path d="M14 4l0 4l-6 0l0 -4" />
                                            </svg>
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                                @else
                                <div class="col-auto">
                                    <a href="#" class="btn email">
                                        Tambahkan Email
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        <h3 class="card-title mt-4">Ganti Password</h3>
                        <p class="card-subtitle">
                            Gunakan password yang kuat agar akunmu aman.
                        </p>
                        <div>
                            <a href="#" class="btn ganti-password" data-bs-toggle="modal" data-bs-target="#ganti_password_modal"> Ganti Password </a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@include('profile.modal_crop')
@include('profile.email_modal')
@include('profile.ganti_password_modal')
@endsection

@section('script')
<script>
    document.getElementById('changePictureBtn').addEventListener('click', function() {
        document.getElementById('profilePictureInput').click();
    });

    document.getElementById('profilePictureInput').addEventListener('change', function() {
        var input = this;

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var image = new Image();
                image.src = e.target.result;

                var cropperContainer = document.getElementById('cropperContainer');
                cropperContainer.innerHTML = ''; // Clear previous content
                cropperContainer.appendChild(image);

                $('#cropImageModal').modal('show');

                var cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 3,
                });

                $('#cropImageButton').off('click').on('click', function() {
                    console.log('Cropping image...');
                    // Get the cropped canvas and convert to Blob
                    cropper.getCroppedCanvas().toBlob(function(blob) {
                        // Create a FormData object and append the Blob data
                        var formData = new FormData();
                        formData.append('file', blob);

                        var csrfToken = $('meta[name="csrf-token"]').attr('content');

                        // Send the FormData to the server using AJAX
                        $.ajax({
                            url: '{{ route("profile.uploadProfilePicture") }}',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            beforeSend: function() {
                                console.log('Uploading image...');
                            },
                            success: function(response) {
                                window.location.reload();
                            },
                            error: function(xhr) {
                                var errors = xhr.responseJSON.errors;

                                if (errors && errors.file) {
                                    // Tampilkan pesan kesalahan di sini, misalnya menggunakan alert
                                    alert(errors.file.join('\n'));
                                } else {
                                    console.error('Error uploading image:', xhr);
                                }
                            },
                            complete: function() {
                                $('#cropImageModal').modal('hide');
                                // Hapus Cropper dan bersihkan kontainer saat modal ditutup
                                cropper.destroy();
                                cropperContainer.innerHTML = '';
                            },
                        });
                    });
                });
            };

            reader.readAsDataURL(input.files[0]);
        }
    });

    $('#cropImageModal').on('shown.bs.modal', function() {
        // No need to create Cropper again here
        // Cropper has already been created when the modal is shown
    });

    $(function() {
        $('.deletePictureBtn').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $('#delete').modal('show');
            $('#delete_button').unbind().click(function() {
                $.ajax({
                    url: "{{ route('profile.deleteProfilePicture', '') }}/" + id,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#delete').modal('hide');
                            window.location.reload();
                        } else {
                            $('#modal-danger').modal('show');
                            $('#modal-danger').find('#message').html(response.message);
                        }
                    }
                })
            })
        })

        $('.email').on('click', function(e) {
            e.preventDefault();
            $('#email_modal').modal('show');
        })

        $('#email_modal_form').on('submit', function(e) {
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
                beforeSend: function() {
                    $('#email_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#email_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        $('#email_modal_form')[0].reset();
                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('#ganti_password_modal_form').on('submit', function(e) {
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
                beforeSend: function() {
                    $('#ganti_password_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#ganti_password_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        // reset modal
                        $('#ganti_password_modal_form')[0].reset();
                        $('#ganti_password_modal').modal('hide');

                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })
    })
</script>
@endsection