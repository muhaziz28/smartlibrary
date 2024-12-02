@extends('layouts.apps')

@section('title', 'Create Session')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Create Session
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('detail_sesi_mata_kuliah.index', $id) }}" class="btn btn-outline d-none d-sm-inline-block">
                        <svg xmlns=" http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevrons-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('session.store') }}" method="POST" id="session">
                            @csrf
                            <input type="text" name="id" id="id" value="{{ $id }}">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Date *</label>
                                    <input class="form-control" type="date" name="date" id="date" placeholder="" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Time *</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label">Start</label>
                                            <input class="form-control" type="time" name="time_start" id="date" placeholder="" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">To</label>
                                            <input class="form-control" type="time" name="time_end" id="date" placeholder="" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="tinymce-mytextarea"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary ms-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M14 4l0 4l-6 0l0 -4" />
                                    </svg>
                                    Simpan
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