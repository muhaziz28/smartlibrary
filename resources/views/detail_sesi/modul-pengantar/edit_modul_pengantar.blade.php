<div class="modal modal-blur fade" id="edit_modul_pengantar_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Modul Pengantar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit_modul_pengantar_modal_form" action="{{ route('modul_pengantar.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="sesi_mata_kuliah_id" value="{{ $id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="deskripsi">Deskripsi</label>
                        <input type="text" class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" id="deskripsi">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="file">File</label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" id="file_rps">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="link">Link</label>
                        <input class="form-control @error('link') is-invalid @enderror" type="text" name="link" id="link" placeholder="Link">
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary ms-auto">
                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
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