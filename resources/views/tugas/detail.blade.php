@extends('layouts.apps')

@section('title')
Tugas
@endsection

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Tugas
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    @if(Auth::user()->role_id != 2)
                    <a href="{{ route('assesment.index', $tugasId) }}" class="btn btn-outline d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevrons-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M11 7l-5 5l5 5" />
                            <path d="M17 7l-5 5l5 5" />
                        </svg>
                        Kembali
                    </a>
                    @else
                    <a href="
                    {{ route('tugas.index', $tugas->pertemuan_id) }}
                    " class="btn btn-outline d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevrons-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M11 7l-5 5l5 5" />
                            <path d="M17 7l-5 5l5 5" />
                        </svg>
                        Kembali
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar" style="background-image: url(
                                    <?php

                                    use Illuminate\Support\Facades\Auth;

                                    if ($user->profile_pict == null) {
                                        echo asset('logo.png');
                                    } else {
                                        echo asset('media/' . $user->profile_pict);
                                    } ?>
                                    )"></span>
                            </div>
                            <div class="col">
                                <div class="card-title">{{ $mahasiswa->mahasiswa->nama_mahasiswa }} </div>
                                <div class="card-subtitle">{{ $mahasiswa->mahasiswa->nim }}</div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <?php
                            $pending = false;
                            foreach ($data as $item) {
                                if ($item['status'] == 'ditolak') {
                                    $pending = true;
                                } else {
                                    $pending = false;
                                    break;
                                }
                            }

                            if ($data == null) {
                                $pending = true;
                            }

                            if (Auth::user()->role_id == 2) {
                                echo $pending ? '
                            <button class="btn btn-outline-info d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#add_assesment_modal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-book-upload" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 20h-8a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12v5" /><path d="M11 16h-5a2 2 0 0 0 -2 2" /><path d="M15 16l3 -3l3 3" /><path d="M18 13v9" /></svg>
                            Kumpulkan
                            </button>
                            ' : '';
                            }
                            ?>
                        </div>

                    </div>
                    <div class="card-body p-5">
                        @foreach ($data as $item)
                        <div class="card mb-3">
                            <div class="card-header">
                                @if($item['status'] == 'pending')
                                <span class="badge bg-yellow-lt">Pending</span>
                                @elseif($item['status'] == 'disetujui')
                                <span class="badge bg-green-lt">Diterima</span>
                                @elseif($item['status'] == 'ditolak')
                                <span class="badge bg-red-lt">Ditolak</span>
                                @endif

                                @if($item['status'] == 'pending')
                                <div class="card-actions">
                                    @if(Auth::user()->role_id != 2)
                                    <a class="btn btn-outline-danger btn-tolak" data-id="{{ $item['id'] }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                            <path d="M10 10l4 4m0 -4l-4 4" />
                                        </svg>
                                        Tolak
                                    </a>
                                    <a class="btn btn-outline-success btn-terima" data-id="{{ $item['id'] }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                            <path d="M9 12l2 2l4 -4" />
                                        </svg>
                                        Terima
                                    </a>
                                    @endif
                                </div>
                                @else
                                <div class="card-actions">
                                    NILAI: {{ $item['nilai'] ?? '-' }}
                                </div>
                                @endif
                            </div>
                            <div class="modal-status 
                                @if($item['status'] == 'pending')
                                bg-yellow
                                @elseif($item['status'] == 'disetujui')
                                bg-green
                                @elseif($item['status'] == 'ditolak')
                                bg-red
                                @endif
                            "></div>

                            @if($item['status'] != 'pending')
                            <div class="card-header">
                                Catatan:<br />
                                {{$item['komentar'] ?? '-'}}
                            </div>
                            @endif
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label" for="file">File</label>
                                    @if($item['file'] != null)
                                    <a href="{{ asset('media/' . $item['file']) }}" class="btn btn-primary my-2" target="_blank">Lihat File</a>
                                    <iframe src="{{ asset('media/' . $item['file']) }}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                                    @else
                                    <a href="#" class="btn mt-2 disabled">Tidak File</a>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="link">Link</label>
                                    @if($item['link'] != null)
                                    <a href="{{ $item['link'] }}" class="btn btn-primary mt-2" target="_blank">Lihat File</a>
                                    @else
                                    <a href="#" class="btn mt-2 disabled">Tidak Link</a>
                                    @endif
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('tugas.tolak_modal')
@include('tugas.terima_modal')
@include('tugas.assesment_modal')
@endsection

@section('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        $('.btn-tolak').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)

            $('#tolak_modal').modal('show');
            $('#id').val(id);

            $('#tolak_modal_form').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('assesment.tolak') }}",
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#tolak_modal_form').find('button[type="submit"]').addClass('btn-loading');
                    },
                    success: function(response) {
                        $('#tolak_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                        console.log(response);
                        $('#tolak_modal').modal('hide');
                        window.location.reload();
                    },
                    error: function(response) {
                        console.log(response);
                    }
                })
            })
        })

        $('.btn-terima').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)

            $('#terima_modal').modal('show');
            // kirim id ke form modal
            $('#terima_modal_form').find('#id').val(id);

            $('#terima_modal_form').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('assesment.terima') }}",
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#terima_modal_form').find('button[type="submit"]').addClass('btn-loading');
                    },
                    success: function(response) {
                        $('#terima_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                        $('#terima_modal').modal('hide');
                        if (response.success == false) {
                            console.log(response)
                            $('#modal-danger').show();
                            $('#modal-danger').html(response.message);
                        } else {
                            console.log(response);
                            window.location.reload();
                        }
                    },
                })
            })
        })

        $('#add_assesment_modal_form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: "{{ route('assesment.store') }}",
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#add_assesment_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#add_assesment_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                    $('#add_assesment_modal_form').modal('hide');
                    if (response.success) {
                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                },
            })
        })

        $('#tolak_modal').on('hidden.bs.modal', function() {
            $('#tolak_modal_form').trigger('reset');
        })
        $('#terima_modal').on('hidden.bs.modal', function() {
            $('#terima_modal_form').trigger('reset');
        })
        $('#add_assesment_modal').on('hidden.bs.modal', function() {
            $('#add_assesment_modal_form').trigger('reset');
        })
    })
</script>
@endsection