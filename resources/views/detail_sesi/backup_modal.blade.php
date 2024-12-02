<div class="modal modal-blur fade" id="backup_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Backup Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="backup_modal_form" action="{{ route('backup.preview') }}" method="GET">
                @csrf
                <input type="hidden" name="current" id="current" value="{{ $sesiMataKuliah->id}}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Periode</label>
                        <select name="periode" id="periode" class="form-select" required>
                            <option value="">-- Pilih Periode --</option>
                            @foreach ($periode as $p)
                            <option value="{{ $p->id }}">{{ $p->mulai }} - {{ $p->selesai }} ({{ $p->tahun_ajaran }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih Sesi Mata Kuliah</label>
                        <select name="sesi" id="sesi" class="form-select" disabled>
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