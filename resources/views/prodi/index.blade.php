@extends('layouts.apps')

@section('title')
Master Prodi
@endsection

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Fakultas / {{ $fakultas->nama_fakultas }}
                </div>
                <h2 class="page-title">
                    Master Prodi
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('fakultas.index') }}" class="btn btn-outline d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevrons-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M11 7l-5 5l5 5" />
                            <path d="M17 7l-5 5l5 5" />
                        </svg>
                        Kembali
                    </a>
                    <a href="#" class="btn btn-outline-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#add_prodi_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Tambah Prodi
                    </a>
                    <a href="#" class="btn btn-outline-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#add_prodi_modal" aria-label="Tambah Mahasiswa">
                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            @foreach ($prodi as $i)
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row gap-3 align-items-center">
                            <div>
                                <span class="avatar avatar-sm rounded">{{ $i->kode_prodi }}</span>
                            </div>
                            <div class="col">
                                <p class="my-0">{{ $i->nama_prodi }}</p>
                                <p class="text-secondary my-0">Created At: {{ \Carbon\Carbon::parse($i->created_at)->locale('id')->translatedFormat('j F y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row align-items-center">
                            <div class="d-flex justify-content-between text-center">
                                <div class="col-auto ml-auto">
                                    @if(count($i->mahasiswa) >0)
                                    <div class="avatar-list avatar-list-stacked">
                                        @php

                                        $max = 3;
                                        $mahasiswa = $i->mahasiswa;
                                        @endphp

                                        @foreach ($mahasiswa as $index => $mhs)
                                        @if ($index < $max)
                                            <span class="avatar avatar-sm rounded" style="background-image: url('<?= asset('logo.png') ?>')"></span>
                                            @endif
                                            @endforeach

                                            @if (count($mahasiswa) > $max)
                                            <span class="avatar rounded">+{{ $count - $max }}</span>
                                            @endif

                                    </div>
                                    @else
                                    <p class="text-secondary">Tidak ada data mahasiswa </p>
                                    @endif
                                </div>
                                <div>
                                    <a href="#" class="btn btn-icon btn-outline-default edit_prodi" data-id="{{ $i->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                            <path d="M16 5l3 3" />
                                        </svg>
                                    </a>
                                    <a href="#" class="btn btn-icon btn-outline-danger delete_prodi" data-id="{{ $i->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 7l16 0" />
                                            <path d="M10 11l0 6" />
                                            <path d="M14 11l0 6" />
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@include('prodi.create')
@include('prodi.edit')
@include('prodi.import_prodi')
@include('prodi.skip_report')
@endsection

@section('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        $('#add_prodi_modal_form').on('submit', function(e) {
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
                    $('#add_prodi_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#add_prodi_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        // reset modal
                        $('#add_prodi_modal_form')[0].reset();
                        $('#add_prodi_modal').modal('hide');

                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('.delete_prodi').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $('#delete').modal('show');
            $('#delete_button').unbind().click(function() {
                $.ajax({
                    url: "{{ route('prodi.destroy', '') }}/" + id,
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

        $('.edit_prodi').on('click', function(e) {
            e.preventDefault();
            var prodiId = $(this).data('id');
            console.log(prodiId)
            $.ajax({
                url: "{{ route('prodi.show', '') }}/" + prodiId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#edit_prodi_modal').modal('show');
                        $('#edit_prodi_modal').find('#id').val(response.data.id);
                        $('#edit_prodi_modal').find('#kode_prodi').val(response.data.kode_prodi);
                        $('#edit_prodi_modal').find('#nama_prodi').val(response.data.nama_prodi);
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('#edit_prodi_modal_form').on('submit', function(e) {
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
                    $('#edit_prodi_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#edit_prodi_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        $('#edit_prodi_modal_form')[0].reset();
                        $('#edit_prodi_modal').modal('hide');

                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                    console.log(response)
                }
            })
        })
    });
    $('#import_prodi_modal_form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var type = form.attr('method');
        var data = new FormData(this);

        $.ajax({
            url: url,
            type: type,
            data: data,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response)
                if (response.success) {

                    $('#import_prodi_modal_form')[0].reset();
                    $('#import_prodi_modal').modal('hide');
                    if (response.haveSkip) {
                        $('#skip_report_modal').modal('show');
                        $('#skip_report_modal').find('#skip_report').html(response.skipValue);
                    } else {
                        toastr.success(response.message)
                    }
                    table.DataTable().ajax.reload(null, false);
                } else {
                    toastr.error(response.message)
                }
            }
        })
    })
</script>
@endsection