<div class="modal fade" id="import_prodi_modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload File</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <form id="import_prodi_modal_form" action="{{ route('prodi.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="id" id="id">
                <input type="hidden" name="fakultas_id" value="{{ $fakultas->id }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-primary left-icon-big alert-dismissible fade show">
                                <div class="media">
                                    <div class="media-body">
                                        <h4 class="mt-1 mb-2">{{ $fakultas->nama_fakultas }}</h4>
                                        <p class="mb-0">
                                            Pastikan prodi yang diupload sesuai dengan fakultas yang terpilih saat ini.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="file">File</label>
                        <input class="form-control @error('file') is-invalid @enderror" type="file" name="file" id="file">
                        @error('nama_prodi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <a href="{{ asset('sample-import-prodi.xlsx') }}" class="btn btn-outline-info btn-sm">
                        <i class="flaticon-381-file mr-2"></i>
                        Download Template
                    </a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm light" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>