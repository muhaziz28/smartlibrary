@extends('layouts.apps')

@section('title')
Konfirmasi Peserta Mata Kuliah
@endsection

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Konfirmasi Peserta Mata Kuliah
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
                                    <th>PRODI</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($konfirmasi) == 0)
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Tidak ada data
                                    </td>
                                </tr>
                                @endif
                                @foreach ($konfirmasi as $m )
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
                                            @if($m->disetujui == 'pending')
                                            <a href="#" class="btn btn-outline-info confirm" data-id="{{ $m->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                                    <path d="M9 12l2 2l4 -4" />
                                                </svg>
                                                Konfirmasi
                                            </a>
                                            @elseif ($m->disetujui == 'ditolak')
                                            <a href="#" class="btn btn-outline-danger disabled">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                                    <path d="M10 10l4 4m0 -4l-4 4" />
                                                </svg>
                                                Ditolak
                                            </a>
                                            @else
                                            <a href="#" class="btn btn-success disabled">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                                    <path d="M9 12l2 2l4 -4" />
                                                </svg>
                                                Disetujui
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
<div class="modal modal-blur fade" id="modal-konfirmasi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-status bg-info"></div>
            <div class="modal-body text-center py-4">
                <!-- Download SVG icon from http://tabler-icons.io/i/circle-check -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                    <path d="M12 9h.01" />
                    <path d="M11 12h1v4h1" />
                </svg>
                <h3>Konfirmasi Pengajuan</h3>
                <div class="text-muted">Apakah anda yakin ingin mengkonfirmasi pengajuan mata kuliah ini?</div>
            </div>
            <div class="modal-footer">
                <!-- tombol todal dan setuju -->
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <a class="btn btn-success w-100" id="setuju"> Setuju </a>
                        </div>
                        <div class="col">
                            <a class="btn btn-danger w-100" id="tolak"> Tolak </a>
                        </div>
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
        $('.confirm').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var confirmUrl = "{{ route('mata_kuliah_diajukan.setujui') }}";
            var rejectUrl = "{{ route('mata_kuliah_diajukan.tolak') }}";

            // Show confirmation modal
            $('#modal-konfirmasi').modal('show');

            // Handle "Setuju" and "Tolak" button clicks
            $('#setuju').on('click', function() {
                // Perform your AJAX request using the confirmUrl
                $.ajax({
                    url: confirmUrl,
                    method: 'PUT',
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        // Handle the success response, e.g., update the UI
                        console.log('Setuju action successful:', response);
                        window.location.reload();
                    },
                    error: function(error) {
                        // Handle the error response, e.g., show an error message
                        console.error('Setuju action failed:', error);
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(error.responseJSON.message);
                    },
                });

                // Close the modal after handling the action
                $('#modal-konfirmasi').modal('hide');
            });

            $('#tolak').on('click', function() {
                // Perform your AJAX request using the rejectUrl
                $.ajax({
                    url: rejectUrl,
                    method: 'PUT',
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        // Handle the success response, e.g., update the UI
                        console.log('Tolak action successful:', response);
                        window.location.reload();
                    },
                    error: function(error) {
                        // Handle the error response, e.g., show an error message
                        console.error('Tolak action failed:', error);
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(error.responseJSON.message);
                    },
                });

                // Close the modal after handling the action
                $('#modal-konfirmasi').modal('hide');
            });
        });





        // table4.on('click', '#tolak', function(e) {
        //     e.preventDefault();
        //     var id = $(this).data('id');
        //    

        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: "You won't be able to revert this!",
        //         showCancelButton: true,
        //         confirmButtonText: 'Tolak',
        //         cancelButtonText: 'Cancel',
        //         confirmButtonColor: '#4caf50',
        //         cancelButtonColor: '#ff0000',
        //     }).then((result) => {
        //         console.log(result.value)
        //         if (result.value == true) {
        //             $.ajax({
        //                 url: url,
        //                 type: 'PUT',
        //                 dataType: 'json',
        //                 data: {
        //                     id: id,
        //                     _token: '{{ csrf_token() }}'
        //                 },

        //                 success: function(response) {
        //                     if (response.success) {
        //                         toastr.success(response.message)
        //                         table4.DataTable().ajax.reload(null, false);
        //                     } else {
        //                         toastr.error(response.message)
        //                     }
        //                 }
        //             })
        //         }
        //     })
        // })
    });
</script>
@endsection