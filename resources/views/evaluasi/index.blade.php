@extends('layouts.apps')

@section('title' , 'Evaluasi')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Evaluasi
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('pertemuan.index', $pertemuan_id) }}" class="btn btn-outline ">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevrons-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M11 7l-5 5l5 5" />
                            <path d="M17 7l-5 5l5 5" />
                        </svg>
                        Kembali
                    </a>

                    <a href="#" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#add_evaluasi_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                            <path d="M12 11l0 6" />
                            <path d="M9 14l6 0" />
                        </svg>
                        Tambah Evaluasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            @if (count($evaluasi) == 0)
            <tr>
                <td colspan="4" class="text-center text-muted">
                    Tidak ada data
                </td>
            </tr>
            @else
            @foreach ($evaluasi as $val)


            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body border-bottom py-3">
                        <h4 class="card-title">Evaluasi {{ $loop->iteration + ($currentPage - 1) * $perPage }}</h4>

                        <div class="card-subtitle">Keterangan:
                            {{ $val->keterangan ?? '-'}}
                        </div>
                        <div class="btn-list">
                            <a href="#" class="btn btn-outline-warning btn-icon edit_evaluasi" data-id="{{ $val->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                    <path d="M16 5l3 3" />
                                </svg>
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-icon delete_evaluasi" data-id="{{ $val->id }}">
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
                    <div class="card-footer">
                        <a href="{{ $val->link }}" target="_blank" class="btn btn-outline-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-text" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                <path d="M9 9l1 0" />
                                <path d="M9 13l6 0" />
                                <path d="M9 17l6 0" />
                            </svg>
                            Soal
                        </a>

                    </div>
                </div>
            </div>
            @endforeach
            <div class="d-flex align-items-center">
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
            @endif
        </div>
    </div>
</div>

@include('evaluasi.create-soal')
@include('evaluasi.edit-soal')
@endsection

@section('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        $('#add_evaluasi_modal_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var type = form.attr('method');
            var data = form.serialize();

            var insert = document.getElementById('insert').checked;
            if (insert == true) {
                var insert = 1;
            } else {
                var insert = 0;
            }

            $.ajax({
                url: url,
                type: type,
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    $('#add_evaluasi_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#add_evaluasi_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('.delete_evaluasi').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $('#delete').modal('show');
            $('#delete_button').unbind().click(function() {
                $.ajax({
                    url: "{{ route('evaluasi.destroy', '') }}/" + id,
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

        $('.edit_evaluasi').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $.ajax({
                url: "{{ route('evaluasi.show', '') }}/" + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#edit_evaluasi_modal').modal('show');
                        $('#edit_evaluasi_modal').find('#id').val(response.data.id);
                        $('#edit_evaluasi_modal').find('#link').val(response.data.link);
                        $('#edit_evaluasi_modal').find('#keterangan').val(response.data.keterangan);
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })

        $('#edit_evaluasi_modal_form').on('submit', function(e) {
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
                    $('#edit_evaluasi_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#edit_evaluasi_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        $('#edit_evaluasi_modal_form')[0].reset();
                        $('#edit_evaluasi_modal').modal('hide');

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
</script>
@endsection