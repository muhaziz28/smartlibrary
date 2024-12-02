@extends('layouts.apps')

@section('title')
Peserta Mata Kuliah
@endsection

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Peserta Mata Kuliah
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('detail_sesi_mata_kuliah.index', $id) }}" class="btn btn-outline d-none d-sm-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevrons-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M11 7l-5 5l5 5" />
                        <path d="M17 7l-5 5l5 5" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
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
                                    <th>PRODI</th>
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
                                @foreach ($data as $m )
                                <tr>
                                    <td>
                                        <span class="text-muted">
                                            {{ $loop->iteration + ($currentPage - 1) * $perPage }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $m->nim }} - {{ $m->mahasiswa->nama_mahasiswa }}
                                    </td>
                                    <td>
                                        {{$m->mahasiswa->prodi->nama_prodi}} - {{ $m->mahasiswa->fakultas->nama_fakultas}}
                                    </td>

                                    <td class="text-end">
                                        <div class="btn-list">
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
        var table4 = $('#peserta-table')
        table4.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('peserta.data', $id) }}",
                type: "GET",
            },
            columns: [{ // add column indexing
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'mahasiswa.nim',
                },
                {
                    data: 'mahasiswa.nama_mahasiswa',
                },
                {
                    data: 'mahasiswa.fakultas.nama_fakultas',
                },
                {
                    data: 'mahasiswa.prodi.nama_prodi'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        })

        table4.on('click', '#delete', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var url = "{{ route('peserta.destroy') }}";

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#4caf50',
                cancelButtonColor: '#ff0000',
            }).then((result) => {
                console.log(result.value)
                if (result.value == true) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}'
                        },

                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message)
                                table4.DataTable().ajax.reload(null, false);
                            } else {
                                toastr.error(response.message)
                            }
                        }
                    })
                }
            })
        })
    });
</script>
@endsection