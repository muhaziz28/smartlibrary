@extends('layouts.apps')

@section('title')
Master Dosen
@endsection

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Master Dosen
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">

                    <a href="#" class="btn btn-outline-danger d-none d-sm-inline-block" data-bs-toggle="modal" id="delete_dosen_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 7l16 0" />
                            <path d="M10 11l0 6" />
                            <path d="M14 11l0 6" />
                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                        </svg>
                        Hapus Semua
                    </a>
                    <a href="#" class="btn btn-outline d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#import_dosen_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-import" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M5 13v-8a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5.5m-9.5 -2h7m-3 -3l3 3l-3 3" />
                        </svg>
                        Import Dosen
                    </a>
                    <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#add_dosen_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Tambah Dosen
                    </a>
                    <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#add_dosen_modal" aria-label="Tambah Mahasiswa">
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


        <div class="row row-deck row-cards">
            <div class="col">
                <form action="" class="d-flex gap-1 ms-auto mb-3">
                    <div class="col-auto">
                        <div class="input-group">
                            <input type="text" class="form-control" id="keyword" name="keyword" placeholder="Keyword..." value="{{ $request->keyword }}">
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="input-group">
                            <select class="form-control" name="fakultas_id" id="fakultas_id">
                                <option value="">Pilih Fakultas</option>
                                @foreach ($fakultas as $f )
                                <option value="{{ $f->id }}" {{ $request->fakultas_id == $f->id ? 'selected' : '' }}>{{ $f->nama_fakultas }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="input-group">
                            <select class="form-control" name="is_user" id="is_user">
                                <option value="" class="text-muted">Pilih Status</option>
                                <option value="true" {{ $request->is_user == 'true' ? 'selected' : '' }}>Registered</option>
                                <option value="false" {{ $request->is_user == 'false' ? 'selected' : '' }}>Not Registered</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-outline-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-filter-cog" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 20l-3 1v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v1.5" />
                            <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M19.001 15.5v1.5" />
                            <path d="M19.001 21v1.5" />
                            <path d="M22.032 17.25l-1.299 .75" />
                            <path d="M17.27 20l-1.3 .75" />
                            <path d="M15.97 17.25l1.3 .75" />
                            <path d="M20.733 20l1.3 .75" />
                        </svg>
                        Filter
                    </button>
                    @if ($request->keyword || $request->fakultas_id || $request->is_user)
                    <a href="{{ route('dosen.index') }}" class="btn btn-outline-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M18 6l-12 12" />
                            <path d="M6 6l12 12" />
                        </svg>
                        Clear
                    </a>

                    @endif
                </form>
            </div>
        </div>

        @if (count($data) == 0)
        <div class="row row-deck row-cards">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <p class="text-center text-secondary">Tidak ada data</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row row-cards">
            @foreach($data as $m)
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body p-4 text-center">
                        <span class="avatar avatar-xl mb-3 rounded" style="background-image: url(<?= $m->user != null ? asset('media/' . $m->user->profile_pict) : asset('logo.png') ?>)"></span>
                        <h3 class="m-0 mb-1"><a href="#">{{ $m->nama_dosen }}</a></h3>
                        <div class="text-secondary">{{ $m->kode_dosen }}</div>
                        <div class="mt-3">
                            @if ($m->is_user == true)
                            <span class="badge bg-success-lt">Registered</span>
                            @else
                            <span class="badge bg-warning-lt">Not Registered</span>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex">

                        <a href="#" class="card-btn text-default edit_dosen" data-id="{{ $m->kode_dosen }}">
                            <!-- Download SVG icon from http://tabler-icons.io/i/brand-facebook -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                <path d="M16 5l3 3" />
                            </svg>
                            Edit
                        </a>
                        <a href="#" class="card-btn text-danger delete_dosen" data-id="{{ $m->kode_dosen }}">
                            <!-- Download SVG icon from http://tabler-icons.io/i/brand-facebook -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 7l16 0" />
                                <path d="M10 11l0 6" />
                                <path d="M14 11l0 6" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg>
                            Hapus
                        </a>
                        @if ($m->is_user != true)
                        <a href="#" class="card-btn aktif" data-id="{{ $m->kode_dosen }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                <path d="M15 19l2 2l4 -4" />
                            </svg>
                            Daftar
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row row-deck row-cards mt-3">
            <div class="col-12">
                <p class="m-0 text-muted ms-3">
                    Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} entries
                </p>
                <ul class="pagination m-0 ms-auto">
                    @if($data->currentPage() > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{ $data->url(1) }}">First</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="{{ $data->previousPageUrl() }}">Previous</a>
                    </li>
                    @endif

                    @for($i = max(1, $data->currentPage() - 2); $i <= min($data->currentPage() + 2, $data->lastPage()); $i++)
                        @if($i == $data->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $i }}</span>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
                        </li>
                        @endif
                        @endfor

                        @if($data->currentPage() < $data->lastPage())
                            <li class="page-item">
                                <a class="page-link" href="{{ $data->nextPageUrl() }}">Next</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="{{ $data->url($data->lastPage()) }}">Last</a>
                            </li>
                            @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@include('dosen.create')
@include('dosen.edit')
@include('dosen.import_dosen')
@include('dosen.register_dosen')
@include('dosen.skip_report')
@endsection

@section('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        $('.aktif').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $('#register_dosen_modal').modal('show');
            // tambahkan action ke form
            $('#register_dosen_modal_form').attr('action', "{{ route('dosen.make_user', '') }}/" + id);
            console.log($('#register_dosen_modal_form').attr('action'))
            $('#register_dosen_modal_form').find('#kode_dosen').val(id);
            $('#register_dosen_modal_form').find('#nama_dosen').val($(this).closest('tr').find('td:nth-child(3)').text());
            $('#register_dosen_modal_form').find('#nama_dosen').val($('#register_dosen_modal_form').find('#nama_dosen').val().replace(/\s/g, ''));

            // handle form submit
            $('#register_dosen_modal_form').on('submit', function(e) {
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
                        $('#register_dosen_modal_form').find('button[type="submit"]').addClass('btn-loading');
                    },
                    success: function(response) {
                        $('#register_dosen_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                        if (response.success) {
                            // reset modal
                            $('#register_dosen_modal_form')[0].reset();
                            $('#register_dosen_modal').modal('hide');

                            window.location.reload();
                        } else {
                            $('#modal-danger').modal('show');
                            $('#modal-danger').find('#message').html(response.message);
                        }
                    }
                })
            })
        })

        $('#import_dosen_modal_form').on('submit', function(e) {
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
                beforeSend: function() {
                    $('#import_dosen_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    console.log(response)
                    if (response.success) {
                        $('#import_dosen_modal_form')[0].reset();
                        $('#import_dosen_modal').modal('hide');
                        if (response.haveSkip) {
                            $('#skip_report_modal').modal('show');
                            $('#skip_report_modal').find('#skip_report').html(response.skipValue);
                        } else {
                            $('#import_success_modal').modal('show');
                        }
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('.delete_dosen').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $('#delete').modal('show');
            $('#delete_button').unbind().click(function() {
                $.ajax({
                    url: "{{ route('dosen.destroy', '') }}/" + id,
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

        $('#delete_dosen_modal').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $('#delete').modal('show');
            $('#delete_button').unbind().click(function() {
                $.ajax({
                    url: "{{ route('dosen.truncate') }}",
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        $('#delete_button').addClass('btn-loading');
                    },
                    success: function(response) {
                        $('#delete_button').removeClass('btn-loading');
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

        $('#add_dosen_modal_form').on('submit', function(e) {
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
                    $('#add_dosen_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#add_dosen_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        // reset modal
                        $('#add_dosen_modal_form')[0].reset();
                        $('#fakultas_id').val('').trigger('change');
                        $('#add_dosen_modal').modal('hide');

                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('.edit_dosen').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $.ajax({
                url: "{{ route('dosen.show', '') }}/" + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#edit_dosen_modal').modal('show');
                        $('#edit_dosen_modal').find('#kode_dosen').val(response.data.kode_dosen);
                        $('#edit_dosen_modal').find('#nama_dosen').val(response.data.nama_dosen);
                        $('#edit_dosen_modal').find('#fakultas_id').val(response.data.fakultas_id);
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('#edit_dosen_modal_form').on('submit', function(e) {
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
                    $('#edit_dosen_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#edit_dosen_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        // reset modal
                        $('#edit_dosen_modal_form')[0].reset();
                        $('#edit_dosen_modal').modal('hide');

                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })
    });
</script>
@endsection