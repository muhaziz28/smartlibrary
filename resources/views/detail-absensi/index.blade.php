@extends('layouts.apps')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Absensi Pertemuan {{ $pertemuan->pertemuan_ke }}
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('absensi.index', $pertemuan->sesi_mata_kuliah_id) }}" class="btn btn-outline d-none d-sm-inline-block">
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
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
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
                                    <th>MAHASISWA</th>
                                    <th>STATUS</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($mahasiswa) == 0)
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Tidak ada data
                                    </td>
                                </tr>
                                @endif
                                @foreach ($mahasiswa as $m )
                                <tr>
                                    <td>
                                        <span class="text-muted">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $m->mahasiswa->nama_mahasiswa}} <br />
                                        {{ $m->nim }}
                                    </td>
                                    <td>
                                        @if ($m->absensi != null)

                                        <span class="badge bg-success">
                                            <?php
                                            $status = $m->absensi->hadir;
                                            if ($status) {
                                                echo 'Hadir';
                                            }
                                            ?>
                                        </span>

                                        @else
                                        <span class="badge bg-secondary">Tidak ada data absensi</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($m->absensi == null)
                                        <div class="btn-list">
                                            <a href="#" class="btn btn-outline-info hadir" data-id="{{ $m->nim }}" data-pertemuan="{{ $pertemuanID }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                    <path d="M16 5l3 3" />
                                                </svg>
                                                Hadir
                                            </a>
                                            <a href="#" class="btn btn-outline-warning tidak-hadir" data-id="{{ $m->nim }}" data-pertemuan="{{ $pertemuanID }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                    <path d="M16 5l3 3" />
                                                </svg>
                                                Tidak Hadir
                                            </a>
                                        </div>
                                        @else
                                        <?php
                                        if ($m->absensi->attachment) {
                                            echo '<img src="' . asset('absen/' . $m->absensi->attachment) . '" alt="Attachment" width="80">';
                                        }
                                        ?>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
        $('.hadir').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var pertemuanID = $(this).data('pertemuan')
            $.ajax({
                url: "{{ route('detail-absensi.insert') }}",
                method: "POST",
                data: {
                    "username": id,
                    "hadir": true,
                    "pertemuan_id": pertemuanID,
                },
                success: function(e) {
                    window.location.reload()
                },
                error: function(e) {
                    window.location.reload()
                }
            })

        })

        $('.tidak-hadir').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var pertemuanID = $(this).data('pertemuan')
            $.ajax({
                url: "{{ route('detail-absensi.insert') }}",
                method: "POST",
                data: {
                    "username": id,
                    "hadir": false,
                    "pertemuan_id": pertemuanID,
                },
                success: function(e) {
                    window.location.reload()
                },
                error: function(e) {
                    window.location.reload()
                }
            })

        })
    });
</script>
@endsection