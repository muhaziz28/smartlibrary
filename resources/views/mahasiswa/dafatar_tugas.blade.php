@extends('layouts.apps')

@section('title')
Master Mahasiswa
@endsection

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Master Mahasiswa
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="#" class="btn btn-outline d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#import_mahasiswa_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-import" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M5 13v-8a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5.5m-9.5 -2h7m-3 -3l3 3l-3 3" />
                        </svg>
                        Import Mahasiswa
                    </a>
                    <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#add_mahasiswa_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Tambah Mahasiswa
                    </a>
                    <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#add_mahasiswa_modal" aria-label="Tambah Mahasiswa">
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
            <div class="col-12">
                <div class="card">
                    <div class="card-body border-bottom py-3">
                        <div class="d-flex">
                            <a href="#" class="btn btn-outline-danger d-none d-sm-inline-block" data-bs-toggle="modal" id="delete_mahasiswa_modal">
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
                            <div class="ms-auto text-muted">
                                Search:
                                <div class="ms-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm" aria-label="Search mahasiswa" name="search">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th class="w-1">No. <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M6 15l6 -6l6 6" />
                                        </svg>
                                    </th>
                                    <th>NIM</th>
                                    <th>NAMA</th>
                                    <th>STATUS</th>
                                    <th>PRODI</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($tugas) == 0)
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Tidak ada data
                                    </td>
                                </tr>
                                @endif
                                @foreach ($tugas as $m )
                                <tr>
                                    <td>
                                        <span class="text-muted">

                                            {{ $loop->iteration + ($currentPage - 1) * $perPage }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $m->nim }}
                                    </td>
                                    <td>
                                        {{ $m->nama_mahasiswa}}
                                    </td>
                                    <td>
                                        @if ($m->is_user)
                                        <span class="badge bg-success me-1"></span> Registered
                                        @else
                                        <span class="badge bg-warning me-1"></span> Not Registered
                                        @endif
                                    </td>
                                    <td>
                                        @if ($m->prodi)
                                        <div>
                                            {{ $m->prodi->nama_prodi }}
                                        </div>
                                        <div class="text-muted">{{ $m->fakultas->nama_fakultas }}</div>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-list">
                                            <a href="#" class="btn btn-outline-info edit_mahasiswa" data-id="{{ $m->nim }}">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/brand-facebook -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                    <path d="M16 5l3 3" />
                                                </svg>
                                                Edit
                                            </a>
                                            <a href="#" class="btn btn-outline-danger delete_mahasiswa" data-id="{{ $m->nim }}">
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
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        <p class="m-0 text-muted ms-3">
                            Showing <span>{{ $currentPage }}</span> to <span>{{ $currentPage * $perPage }}</span> of <span>{{ $totalItems }}</span> entries
                        </p>
                        <ul class="pagination m-0 ms-auto">
                            @if($currentPage > 1)
                            <li class="page-item">
                                <a class="page-link" href="?page=1">First</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="?page={{ $currentPage - 1 }}">Previous</a>
                            </li>
                            @endif

                            @for($i = max(1, $currentPage - 2); $i <= min($currentPage + 2, $totalPages); $i++) @if($i==$currentPage) <li class="page-item active">
                                <span class="page-link">{{ $i }}</span>
                                </li>
                                @else
                                <li class="page-item">
                                    <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
                                </li>
                                @endif
                                @endfor

                                @if($currentPage < $totalPages) <li class="page-item">
                                    <a class="page-link" href="?page={{ $currentPage + 1 }}">Next</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="?page={{ $totalPages }}">Last</a>
                                    </li>
                                    @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    $(function() {
        $('#add_mahasiswa_modal_form').on('submit', function(e) {
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
                    $('#add_mahasiswa_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    // kembalikan tombol submit seperti semula
                    $('#add_mahasiswa_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        // reset modal
                        $('#add_mahasiswa_modal_form')[0].reset();
                        $('#add_mahasiswa_modal').modal('hide');

                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('.delete_mahasiswa').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $('#delete').modal('show');
            $('#delete_button').unbind().click(function() {
                $.ajax({
                    url: "{{ route('mahasiswa.destroy', '') }}/" + id,
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

        $('#delete_mahasiswa_modal').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $('#delete').modal('show');
            $('#delete_button').unbind().click(function() {
                $.ajax({
                    url: "{{ route('mahasiswa.truncate') }}",
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

        $('.edit_mahasiswa').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $.ajax({
                url: "{{ route('mahasiswa.show', '') }}/" + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#edit_mahasiswa_modal').modal('show');
                        $('#edit_mahasiswa_modal').find('#nim').val(response.data.nim);
                        $('#edit_mahasiswa_modal').find('#nama_mahasiswa').val(response.data.nama_mahasiswa);
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('#edit_mahasiswa_modal_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = "{{ route('mahasiswa.update') }}";
            var type = form.attr('method');
            var data = form.serialize();

            $.ajax({
                url: url,
                type: type,
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    $('#edit_mahasiswa_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#edit_mahasiswa_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        // reset modal
                        $('#edit_mahasiswa_modal_form')[0].reset();
                        $('#edit_mahasiswa_modal').modal('hide');

                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('#import_mahasiswa_modal_form').on('submit', function(e) {
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
                    $('#import_mahasiswa_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    console.log(response)
                    if (response.success) {
                        $('#import_mahasiswa_modal_form')[0].reset();
                        $('#import_mahasiswa_modal').modal('hide');
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
    });
</script>
@endsection