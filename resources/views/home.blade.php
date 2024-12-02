@extends('layouts.apps')

@section('title')
Dashboard
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid/main.css" rel="stylesheet" />
@endpush

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="row g-2 align-items-center">
                @if(Auth::user()->role_id == 2)
                @if ($mataKuliahDiambil->count() == 0)
                <div class="alert alert-danger" role="alert">
                    Anda belum mengambil mata kuliah apapun di periode ini.
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        @if(Auth::user()->role_id == 2)
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body border-bottom py-3">
                            <div class="d-flex">
                                Mata Kuliah Tersedia

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
                                        <th>KODE SESI</th>
                                        <th>MATA KULIAH</th>
                                        <th>SKS</th>
                                        <th>DOSEN</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($sesiMataKuliah) == 0)
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            Tidak ada data
                                        </td>
                                    </tr>
                                    @endif
                                    @foreach ($matakuliahTersedia as $m )
                                    <tr>
                                        <td>
                                            <span class="text-muted">
                                                {{ $loop->iteration + ($currentPage - 1) * $perPage }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $m->kode_sesi }}
                                        </td>
                                        <td>
                                            {{ $m->periode_mata_kuliah->mata_kuliah->nama_mk }}
                                        </td>
                                        <td>
                                            {{ $m->periode_mata_kuliah->mata_kuliah->sks }}
                                        </td>
                                        <td>{{ $m->dosen->kode_dosen }} - {{ $m->dosen->nama_dosen }}</td>
                                        <td>
                                            <a class="btn btn-outline ajukan" data-id="{{ $m->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M10 14l11 -11" />
                                                    <path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                                                </svg>
                                                Ajukan
                                            </a>
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
        <div class="container-xl mt-3">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body border-bottom py-3">
                            <div class="d-flex">
                                Mata Kuliah Diajukan
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
                                        <th>KODE SESI</th>
                                        <th>MATA KULIAH</th>
                                        <th>SKS</th>
                                        <th>DOSEN</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($matakuliahDiajukan) == 0)
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            Tidak ada data
                                        </td>
                                    </tr>
                                    @endif
                                    @foreach ($matakuliahDiajukan as $m )
                                    <tr>
                                        <td>
                                            <span class="text-muted">
                                                {{ $loop->iteration + ($currentPage - 1) * $perPage }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $m->sesi_mata_kuliah->kode_sesi }}
                                        </td>
                                        <td>
                                            {{ $m->sesi_mata_kuliah->periode_mata_kuliah->mata_kuliah->nama_mk }}
                                        </td>
                                        <td>
                                            {{ $m->sesi_mata_kuliah->periode_mata_kuliah->mata_kuliah->sks }} SKS
                                        </td>
                                        <td>{{ $m->sesi_mata_kuliah->dosen->kode_dosen }} - {{ $m->sesi_mata_kuliah->dosen->nama_dosen }}</td>
                                        <td>
                                            @if($m->disetujui == "pending")
                                            <span class="badge bg-yellow-lt">Pending</span>
                                            @elseif($m->disetujui == "disetujui")
                                            <span class="badge bg-green-lt">Disetujui</span>
                                            @elseif($m->disetujui == "ditolak")
                                            <span class="badge bg-red-lt">Ditolak</span>
                                            @endif
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
        @elseif(Auth::user()->role_id == 3)
        <div class="container-xl">
            <div id="calendar"></div>
        </div>
        @endif
    </div>
</div>

@include('create')
@endsection

@section('script')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var events = @json($events);
        console.log(events);

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            slotMinTime: '08:00:00',
            slotMaxTime: '19:00:00',
            events: events,
        });
        calendar.render();
    });
</script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        var table = $('#mata-kuliah-tersedia-table')

        $('.ajukan').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var url = "{{ route('mata_kuliah_diajukan.show', '') }}/" + id;

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },

                success: function(response) {
                    console.log(response)
                    if (response.success) {
                        $('#add_mata_kuliah_diajukan_modal').modal('show');
                        $('#add_mata_kuliah_diajukan_modal_form').find('#sesi_mata_kuliah_id').val(response.data.id);
                        $('#add_mata_kuliah_diajukan_modal_form').find('#kode_sesi').val(response.data.kode_sesi);
                        $('#add_mata_kuliah_diajukan_modal_form').find('#nama_mk').val(response.data.periode_mata_kuliah.mata_kuliah.nama_mk);
                        $('#add_mata_kuliah_diajukan_modal_form').find('#dosen').val(response.data.dosen.kode_dosen + ' - ' + response.data.dosen.nama_dosen);
                    } else {
                        toastr.error(response.message)
                    }
                }
            })
        })

        $('#add_mata_kuliah_diajukan_modal_form').on('submit', function(e) {
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
                    $('#add_mata_kuliah_diajukan_modal_form').find('button[type="submit"]').addClass('btn-loading');
                },
                success: function(response) {
                    $('#add_mata_kuliah_diajukan_modal_form').find('button[type="submit"]').removeClass('btn-loading');
                    if (response.success) {
                        $('#add_mata_kuliah_diajukan_modal_form').modal('hide');

                        window.location.reload();
                    } else {
                        $('#modal-danger').modal('show');
                        $('#modal-danger').find('#message').html(response.message);
                    }
                    console.log(response)
                }
            })
        })

    })
</script>
@endsection