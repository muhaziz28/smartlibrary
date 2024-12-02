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
                        <form id="backup_form" action="{{ route('backup.preview') }}" method="GET">
                            @csrf
                            <!-- ambil value sesi di param -->
                            <input type="hidden" name="sesi" id="sesi" value="{{ $sesi->id}}">
                            <input type="hidden" name="current" id="current" value="{{ $current->id}}">
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
                                        </td>
                                        <td>
                                            <label class="form-check form-switch m-0">
                                                <input class="form-check-input position-static" type="checkbox" name="pengantar" id="pengantar">
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td>
                                            RPS
                                        </td>
                                        <td>
                                            <label class="form-check form-switch m-0">
                                                <input class="form-check-input position-static" type="checkbox" name="rps" id="rps">
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">3</td>
                                        <td>
                                            Modul Pengantar
                                        </td>
                                        <td>
                                            <label class="form-check form-switch m-0">
                                                <input class="form-check-input position-static" type="checkbox" name="modul_pengantar" id="modul_pengantar">
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">4</td>
                                        <td>
                                            Ebook
                                        </td>
                                        <td>
                                            <label class="form-check form-switch m-0">
                                                <input class="form-check-input position-static" type="checkbox" name="ebook" id="ebook">
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">5</td>
                                        <td>
                                            Modul
                                        </td>
                                        <td>
                                            <label class="form-check form-switch m-0">
                                                <input class="form-check-input position-static" type="checkbox" name="modul" id="modul">
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">6</td>
                                        <td>
                                            Video Conference
                                        </td>
                                        <td>
                                            <label class="form-check form-switch m-0">
                                                <input class="form-check-input position-static" type="checkbox" name="video_conf" id="video_conf">
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">7</td>
                                        <td>
                                            Video Pembelajaran
                                        </td>
                                        <td>
                                            <label class="form-check form-switch m-0">
                                                <input class="form-check-input position-static" type="checkbox" name="video_pembelajaran" id="video_pembelajaran">
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">8</td>
                                        <td>
                                            Tugas
                                        </td>
                                        <td>
                                            <label class="form-check form-switch m-0">
                                                <input class="form-check-input position-static" type="checkbox" name="tugas" id="tugas">
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">9</td>
                                        <td>
                                            Evaluasi
                                        </td>
                                        <td>
                                            <label class="form-check form-switch m-0">
                                                <input class="form-check-input position-static" type="checkbox" name="evaluasi" id="evaluasi">
                                            </label>
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
        var backup_form = $('#backup_form').serialize();
        $.ajax({
            url: "{{ route('backup.store') }}",
            type: "POST",
            data: backup_form,
            success: function(data) {
                console.log(data);
                $('#modal-success').modal('show');
            }
        });
    });
</script>
@endsection