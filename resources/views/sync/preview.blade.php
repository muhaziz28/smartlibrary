@extends('layouts.apps')

@section('title')
Pilih Data Untuk Backup
@endsection

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Pilih Data Untuk Backup
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('detail_sesi_mata_kuliah.index', $sesi->id) }}" class="btn btn-outline d-none d-sm-inline-block">
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
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title text-center">Pilih Data Untuk Backup</h3>
                    </div>
                    <div class="card-body">
                        <form id="backup_form" method="GET">
                            @csrf
                            <input type="hidden" name="sesi" value="{{ request()->sesi }}">
                            <input type="hidden" name="current" value="{{ request()->current }}">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%">No</th>

                                        <th class="text-center"></th>
                                        <th class="text-center" width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>
                                            Pengantar Mata Kuliah

                                            <small class="text-muted" id="pengantar">
                                                @if($sesi->pengantar != null)
                                                <span class="badge bg-green-lt">Sudah Ada</span>
                                                @else
                                                <span class="badge bg-red-lt">Belum Ada</span>
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <label class="form-check  m-0">
                                                <input class="form-check-input position-static" type="checkbox" name="pengantar" id="pengantar">
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td>
                                            RPS
                                            <small class="text-muted" id="rps">
                                                @if($sesi->rps->count() > 0)
                                                <span class="badge bg-green-lt">{{ $sesi->rps->count() }}</span>
                                                @else
                                                <span class="badge bg-red-lt">0</span>
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <label class="form-check  m-0">
                                                <input class="form-check-input position-static" type="checkbox" name="rps" id="rps">
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">3</td>
                                        <td>
                                            Modul Pengantar
                                            <small class="text-muted" id="modul_pengantar">
                                                @if($sesi->modul_pengantar->count() > 0)
                                                <span class="badge bg-green-lt">{{ $sesi->modul_pengantar->count() }}</span>
                                                @else
                                                <span class="badge bg-red-lt">0</span>
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <label class="form-check  m-0">
                                                @if($sesi->modul_pengantar->count() > 0)
                                                <input class="form-check-input position-static" type="checkbox" name="modul_pengantar" id="modul_pengantar">
                                                @else
                                                <input class="form-check-input position-static" type="checkbox" disabled readonly>
                                                @endif
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">4</td>
                                        <td>
                                            Pertemuan
                                            <small class="text-muted" id="pertemuan">
                                                @if($sesi->pertemuan != null)
                                                <span class="badge bg-green-lt">{{ $sesi->pertemuan->count() }}</span>
                                                @else
                                                <span class="badge bg-red-lt">Belum Ada</span>
                                                @endif
                                            </small>
                                            <!-- list pertemuan -->
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" width="5%">No</th>
                                                        <th class="text-center">Pertemuan</th>
                                                        <th class="text-center">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($sesi->pertemuan as $pertemuan)
                                                    <tr>
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td>
                                                            Pertemuan {{ $pertemuan->pertemuan_ke }}
                                                            <small class="text-muted d-block" id="pertemuan">
                                                                @if($pertemuan->tugas->count() > 0)
                                                                <span class="badge bg-green-lt">Tugas ({{ $pertemuan->tugas->count() }})</span>
                                                                @else
                                                                <span class="badge bg-red-lt">Tugas (0)</span>
                                                                @endif

                                                                @if($pertemuan->ebook->count() > 0)
                                                                <span class="badge bg-green-lt">Ebook ({{ $pertemuan->ebook->count() }})</span>
                                                                @else
                                                                <span class="badge bg-red-lt">Ebook (0)</span>
                                                                @endif

                                                                @if($pertemuan->modul->count() > 0)
                                                                <span class="badge bg-green-lt">Modul ({{ $pertemuan->modul->count() }})</span>
                                                                @else
                                                                <span class="badge bg-red-lt">Modul (0)</span>
                                                                @endif

                                                                @if($pertemuan->video_conf->count() > 0)
                                                                <span class="badge bg-green-lt">Video Conference ({{ $pertemuan->video_conf->count() }})</span>
                                                                @else
                                                                <span class="badge bg-red-lt">Video Conference (0)</span>
                                                                @endif

                                                                @if($pertemuan->video_pembelajaran->count() > 0)
                                                                <span class="badge bg-green-lt">Video Pembelajaran ({{ $pertemuan->video_pembelajaran->count() }})</span>
                                                                @else
                                                                <span class="badge bg-red-lt">Video Pembelajaran (0)</span>
                                                                @endif

                                                                @if($pertemuan->evaluasi->count() > 0)
                                                                <span class="badge bg-green-lt">Evaluasi ({{ $pertemuan->evaluasi->count() }})</span>
                                                                @else
                                                                <span class="badge bg-red-lt">Evaluasi (0)</span>
                                                                @endif
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <label class="form-check m-0">
                                                                @if ($pertemuan->tugas->count() > 0 || $pertemuan->ebook->count() > 0 || $pertemuan->modul->count() > 0 || $pertemuan->video_conf->count() > 0 || $pertemuan->video_pembelajaran->count() > 0 || $pertemuan->evaluasi->count() > 0)
                                                                <input class="form-check-input " type="checkbox" name="pertemuan[]" id="pertemuan" value="{{ $pertemuan->id }}">
                                                                @else
                                                                <input class="form-check-input " type="checkbox" disabled readonly>
                                                                @endif
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>


                                            </table>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary" id="backup_preview_button">
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
                                    Preview
                                </button>
                            </div>
                        </form>
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

    $('#backup_preview_button').on('click', function() {
        var form = $('#backup_form');
        var data = form.serialize();

        // cek data pertemuan yang dipilih
        var pertemuan = [];
        $.each($("input[name='pertemuan[]']:checked"), function() {
            pertemuan.push(Number($(this).val()));
        });


        // cek data pengantar, rps, modul_pengantar
        var pengantar = $("input[name='pengantar']").is(':checked');
        var rps = $("input[name='rps']").is(':checked');
        var modul_pengantar = $("input[name='modul_pengantar']").is(':checked');

        console.log(pertemuan);
        console.log(pengantar);
        console.log(rps);
        console.log(modul_pengantar);

        var url = "{{ route('sync.store') }}";

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            success: function(response) {
                console.log(data);
            }
        });
    });
</script>
@endsection