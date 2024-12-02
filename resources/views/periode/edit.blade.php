<div class="modal modal-blur fade" id="edit_periode_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Periode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit_periode_modal_form" action="{{ route('periode.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="id">

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mulai</label>
                        <input class="form-control @error('mulai') is-invalid @enderror" type="text" name="mulai" id="mulai" placeholder="Mulai">
                        @error('mulai')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Selesai</label>
                        <input class="form-control @error('selesai') is-invalid @enderror" type="text" name="selesai" id="selesai" placeholder="Selesai">
                        @error('selesai')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" id="keterangan" rows="3" placeholder="Keterangan"></textarea>
                        @error('keterangan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <input class="form-control @error('tahun_ajaran') is-invalid @enderror" type="text" name="tahun_ajaran" id="tahun_ajaran" placeholder="Tahun Ajaran">
                        @error('tahun_ajaran')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="custom-control custom-checkbox mb-3 checkbox-success">
                        <input type="checkbox" class="custom-control-input" id="aktif" name="aktif">
                        <label class="custom-control-label" for="aktif">
                            Periode Aktif
                        </label>
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