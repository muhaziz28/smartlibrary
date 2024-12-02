<div class="modal modal-blur fade" id="edit_dosen_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Dosen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit_dosen_modal_form" action="{{ route('dosen.update') }}" method="POST">
                @method('PUT')
                @csrf
                <input type="hidden" name="kode_dosen" id="kode_dosen">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Dosen</label>
                        <input class="form-control @error('kode_dosen') is-invalid @enderror" type="text" name="kode_dosen" id="kode_dosen" placeholder="Kode Dosen" disabled readonly>
                        @error('kode_dosen')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Dosen</label>
                        <input class="form-control @error('nama_dosen') is-invalid @enderror" type="text" name="nama_dosen" id="nama_dosen" placeholder="Nama Dosen" required>
                        @error('nama_dosen')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fakultas</label>
                        <select class="form-control " name="fakultas_id" id="fakultas_id" disabled>
                            <option value="">-- Pilih Fakultas --</option>
                            @foreach ($fakultas as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_fakultas }}</option>
                            @endforeach
                        </select>
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