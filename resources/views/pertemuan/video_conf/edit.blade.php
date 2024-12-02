<div class="modal modal-blur fade" id="edit_video_conf_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Video Conference</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit_video_conf_modal_form" action="{{ route('video_conf.update') }}" method="POST">
                @method('PUT')
                @csrf
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="pertemuan_id" id="pertemuan_id" value="{{ $id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">LINK</label>
                        <input type="url" class="form-control" name="link" id="link" placeholder="LINK">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">KETERANGAN</label>
                        <textarea class="form-control" name="keterangan" id="keterangan" placeholder="KETERANGAN"></textarea>
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