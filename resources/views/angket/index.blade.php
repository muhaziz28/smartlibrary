@extends('layouts.apps')

@section('title')
Master Prodi
@endsection

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Angket
                </div>
                <h2 class="page-title">
                    Lengkapi Angket
                </h2>
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
                    <div class="card-header">
                        <div class="row w-full">
                            <div class="col-6">
                                <label for="" class="mb-2 text-muted">Mahasiswa</label>
                                <input type="text" class="form-control disabled" readonly disabled value="{{ $mahasiswa->nama_mahasiswa }}">
                            </div>
                            <div class="col-6">
                                <label for="" class="mb-2 text-muted">Sesi</label>
                                <input type="text" class="form-control disabled" readonly disabled value="{{ $sesi->kode_sesi}} - {{ $sesi->periode_mata_kuliah->mata_kuliah->nama_mk }}">
                            </div>
                        </div>
                    </div>
                    <form method="post" action="{{ route('angket.store') }}" id="formAngket">
                        @csrf
                        <input type="text" name="sesi_mata_kuliah_id" value="{{ $sesi->id }}">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table card-table table-vcenter table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="w-1">No</th>
                                            <th class="w-50 text-center">Instrumen</th>
                                            <th class="text-center">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($instruments as $item)
                                        <tr>
                                            <td>
                                                <span class=" text-muted">
                                                    {{ $loop->iteration }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $item->item }}
                                            </td>
                                            <td class="text-center" id="nilai_{{ $item->id }}">
                                                <label class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="instrument_{{ $item->id }}" id="instrument_{{ $item->id }}_1" value="1">
                                                    <span class="form-check-label">1</span>
                                                </label>
                                                <label class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="instrument_{{ $item->id }}" id="instrument_{{ $item->id }}_2" value="2">
                                                    <span class="form-check-label">2</span>
                                                </label>
                                                <label class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="instrument_{{ $item->id }}" id="instrument_{{ $item->id }}_3" value="3">
                                                    <span class="form-check-label">3</span>
                                                </label>
                                                <label class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="instrument_{{ $item->id }}" id="instrument_{{ $item->id }}_4" value="4">
                                                    <span class="form-check-label">4</span>
                                                </label>
                                                <label class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="instrument_{{ $item->id }}" id="instrument_{{ $item->id }}_5" value="5">
                                                    <span class="form-check-label">5</span>
                                                </label>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button class="btn btn-primary" id="btnSubmit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // $('#formAngket').on('submit', function(e) {
    //     e.preventDefault();
    //     let data = $(this).serializeArray();
    //     console.log(data);
    // });
</script>
@endsection