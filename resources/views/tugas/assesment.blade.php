@extends('layouts.apps')

@section('title')
List Assesment
@endsection

@section('content')
<!-- Page header -->
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
                    <a href="{{ route('tugas.index', $tugas->pertemuan_id) }}" class="btn btn-outline d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevrons-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M11 7l-5 5l5 5" />
                            <path d="M17 7l-5 5l5 5" />
                        </svg>
                        Kembali
                    </a>
                    @foreach ($data as $m)
                    @foreach ($m['mahasiswa']['tugas'] as $t)
                    @if($t['status'] == 'pending')
                    <a href="" class="btn btn-outline d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevrons-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M11 7l-5 5l5 5" />
                            <path d="M17 7l-5 5l5 5" />
                        </svg>
                        Tambah
                    </a>
                    @elseif($t['status'] == 'disetujui')
                    @endif
                    @endforeach
                    @endforeach
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
                            <div class="ms-auto text-muted">
                                Search:
                                <div class="ms-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm" aria-label="Search mahasiswa" name="search">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap">
                            <thead>
                                <tr>
                                    <th class="w-1">No. </th>
                                    <th>MAHASISWA</th>
                                    <th>TOTAL ASSESMENT</th>
                                    <th>NILAI</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($data) == 0)
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Tidak ada data
                                    </td>
                                </tr>
                                @endif
                                @foreach ($data as $m)
                                <tr>
                                    <td>
                                        <span class="text-muted">
                                            {{ $loop->iteration + ($currentPage - 1) * $perPage }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $m['mahasiswa']['nim'] }} - {{ $m['mahasiswa']['nama_mahasiswa'] }}
                                    </td>
                                    <td>
                                        {{count($m['mahasiswa']['tugas'])}}
                                        @if(count($m['mahasiswa']['tugas']) == 0)
                                        <span class="badge bg-gray-lt">Belum Ada Assesment</span>
                                        @endif
                                        @if(count($m['mahasiswa']['tugas']) > 0)
                                        @foreach ($m['mahasiswa']['tugas'] as $t)
                                        @if($t['status'] == 'pending')
                                        <span class="badge bg-yellow-lt">Pending</span>
                                        @elseif($t['status'] == 'disetujui')
                                        <span class="badge bg-green-lt">Disetujui</span>
                                        @elseif($t['status'] == 'ditolak')
                                        <span class="badge bg-red-lt">Ditolak</span>
                                        @endif
                                        @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if(count($m['mahasiswa']['tugas']) == 0)
                                        <span class="badge bg-gray-lt">Belum Ada Nilai</span>
                                        @endif

                                        @if(count($m['mahasiswa']['tugas']) > 0)
                                        @foreach ($m['mahasiswa']['tugas'] as $t)
                                        @if($t['status'] == 'disetujui')
                                        {{ $t['nilai'] }}
                                        @endif
                                        @endforeach
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-list">
                                            @if(count($m['mahasiswa']['tugas']) == 0)
                                            <a href="" class="btn btn-outline disabled">

                                                Tidak ada assesment
                                            </a>
                                            @elseif(count($m['mahasiswa']['tugas']) > 0)
                                            <a href="{{ route('assesment.detail' ,$m['mahasiswa']['tugas'][0]['tugas_id']) }}?nim={{ $m['mahasiswa']['nim'] }}" class="btn btn-outline-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                </svg>
                                                Lihat Assesment
                                            </a>
                                            @endif

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
    });

    $(function() {
        $('.edit_tugas').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $.ajax({
                url: "{{ route('tugas.show', '') }}/" + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#edit_tugas_modal').modal('show');
                        $('#edit_tugas_modal_form').find('#id').val(response.data.id);
                        $('#edit_tugas_modal_form').find('#link').val(response.data.link);
                        $('#edit_tugas_modal_form').find('#keterangan').val(response.data.keterangan);
                        $('#edit_tugas_modal_form').find('#deadline').val(response.data.deadline);
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            })
        })
        $('.delete_tugas').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            $('#delete').modal('show');
            $('#delete_button').unbind().click(function() {
                $.ajax({
                    url: "{{ route('tugas.destroy', '') }}/" + id,
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

        $('#add_tugas_modal_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var type = form.attr('method');
            var data = new FormData(form[0]);

            $.ajax({
                url: url,
                type: type,
                data: data,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                    form.find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    form.find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        // reset modal
                        form[0].reset();
                        $('#add_tugas_modal').modal('hide');
                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            });
        });

        $('#edit_tugas_modal_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var type = form.attr('method');
            var data = new FormData(form[0]);

            $.ajax({
                url: url,
                type: type,
                data: data,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                    form.find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    form.find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {

                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                }
            });
        });

        // reset modal ketika modal hidden
        $('#add_tugas_modal').on('hidden.bs.modal', function(e) {
            $('#add_tugas_modal_form')[0].reset();
        });

        $('#edit_tugas_modal').on('hidden.bs.modal', function(e) {
            $('#edit_tugas_modal_form')[0].reset();
        });
    });
</script>
@endsection