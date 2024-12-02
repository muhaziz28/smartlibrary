 <div class="container-xl mb-3">
     <div class="card">
         <div class="card-header">
             <h4 class="card-title">Pengantar</h4>
         </div>
         <div class="card-body">
             <div class="accordion" id="accordion-example">
                 <div class="accordion-item">
                     <h2 class="accordion-header" id="heading-1">
                         <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="true">
                             Pengantar Mata Kuliah
                         </button>
                     </h2>
                     <div id="collapse-1" class="accordion-collapse collapse show" data-bs-parent="#accordion-example">
                         <div class="accordion-body pt-0">
                             <div class="row row-deck row-cards">
                                 <div class="col-xl-8 col-lg-12 col-sm-12">
                                     <div class="card">
                                         <div class="card">
                                             <div class="card-body">
                                                 @if(Auth::user()->role_id == 2)
                                                 <input type="hidden" name="sesi_mata_kuliah_id" value="{{ $id }}">
                                                 <div class="mb-3">
                                                     <label class="form-label">Pengantar</label>
                                                     {{ trim(strip_tags($pengantar->pengantar)) }}
                                                 </div>
                                                 <div class="mb-3">
                                                     <label class="form-label">File</label>
                                                     @if($pengantar->file != null)
                                                     <a href="{{ asset('media/' . $pengantar->file) }}" target="_blank" class="btn btn-outline  mt-2">
                                                         <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                             <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                             <path d="M9 15l6 -6" />
                                                             <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                             <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                                         </svg>
                                                         {{ $pengantar->file }}
                                                     </a>
                                                     @else
                                                     <button disabled class="btn btn-outline  mt-2">
                                                         <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                             <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                             <path d="M9 15l6 -6" />
                                                             <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                             <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                                         </svg>
                                                         Tidak ada file
                                                     </button>
                                                     @endif
                                                 </div>
                                                 <div class="mb-3">
                                                     <label class="form-label">Link</label>

                                                     @if($pengantar->link != null)
                                                     <a href="{{ $pengantar->link }}" target="_blank" class="btn btn-outline  mt-2">
                                                         <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                             <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                             <path d="M9 15l6 -6" />
                                                             <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                             <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                                         </svg>
                                                         {{ $pengantar->link }}
                                                     </a>
                                                     @else
                                                     <button disabled class="btn btn-outline  mt-2">
                                                         <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                             <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                             <path d="M9 15l6 -6" />
                                                             <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                             <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                                         </svg>
                                                         Tidak ada link
                                                     </button>
                                                     @endif
                                                 </div>
                                                 <div class="mb-3">
                                                     <label class="form-label">Video</label>
                                                 </div>

                                                 <!-- YT VIDEO  -->
                                                 @if($pengantar->video != null)
                                                 <a href="{{ $pengantar->video }}" target="_blank" class="btn btn-outline  mt-2">
                                                     <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                         <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                         <path d="M9 15l6 -6" />
                                                         <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                         <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                                     </svg>
                                                     Buka Video
                                                 </a>
                                                 @else
                                                 <button disabled class="btn btn-outline  mt-2">
                                                     <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                         <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                         <path d="M9 15l6 -6" />
                                                         <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                         <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                                     </svg>
                                                     Tidak ada video
                                                 </button>
                                                 @endif
                                                 @else
                                                 @if($pengantar != null)
                                                 <form id="edit_sesi_mata_kuliah_form" method="POST" enctype="multipart/form-data">
                                                     @csrf
                                                     @method('PUT')
                                                     <input type="hidden" name="sesi_mata_kuliah_id" value="{{ $id }}">
                                                     <div class="mb-3">
                                                         @if(Auth::user()->role_id != 2)
                                                         <label class="form-label">Pengantar</label>
                                                         <textarea class="form-control" name="pengantar" id="tinymce-mytextarea">
                                                         {{ trim(strip_tags($pengantar->pengantar)) }}
                                                         </textarea>
                                                         @endif

                                                         @if(Auth::user()->role_id == 2)
                                                         <label class="form-label">Pengantar</label>
                                                         {{ trim(strip_tags($pengantar->pengantar)) }}
                                                         @endif
                                                     </div>
                                                     <div class="mb-3">
                                                         <label class="form-label">File</label>
                                                         @if(Auth::user()->role_id != 2)
                                                         <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" id="file">
                                                         @endif
                                                         @if($pengantar->file != null)
                                                         <a href="{{ asset('media/' . $pengantar->file) }}" target="_blank" class="btn btn-outline  mt-2">
                                                             <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                 <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                 <path d="M9 15l6 -6" />
                                                                 <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                                 <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                                             </svg>
                                                             {{ $pengantar->file }}
                                                         </a>
                                                         @else
                                                         <button disabled class="btn btn-outline  mt-2">
                                                             <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                 <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                 <path d="M9 15l6 -6" />
                                                                 <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                                 <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                                             </svg>
                                                             Tidak ada file
                                                         </button>
                                                         @endif
                                                     </div>
                                                     <div class="mb-3">
                                                         <label class="form-label">Link</label>
                                                         @if (Auth::user()->role_id != 2)
                                                         <input type="text" class="form-control @error('link') is-invalid @enderror" name="link" id="link" value="{{ $pengantar->link }}">
                                                         @endif

                                                         @if (Auth::user()->role_id == 2)
                                                         @if($pengantar->link != null)
                                                         <a href="{{ $pengantar->link }}" target="_blank" class="btn btn-outline  mt-2">
                                                             <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                 <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                 <path d="M9 15l6 -6" />
                                                                 <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                                 <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                                             </svg>
                                                             {{ $pengantar->link }}
                                                         </a>
                                                         @else
                                                         <button disabled class="btn btn-outline  mt-2">
                                                             <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                 <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                 <path d="M9 15l6 -6" />
                                                                 <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                                 <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                                             </svg>
                                                             Tidak ada link
                                                         </button>
                                                         @endif
                                                         @endif
                                                     </div>
                                                     <div class="mb-3">
                                                         <label class="form-label">Video</label>
                                                         <input type="text" class="form-control @error('video') is-invalid @enderror" name="video" id="video" value="{{ $pengantar->video }}">
                                                     </div>

                                                     <!-- YT VIDEO  -->
                                                     @if($pengantar->video != null)
                                                     <a href="{{ $pengantar->video }}" target="_blank" class="btn btn-outline  mt-2">
                                                         <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                             <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                             <path d="M9 15l6 -6" />
                                                             <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                             <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                                         </svg>
                                                         Buka Video
                                                     </a>
                                                     @else
                                                     <button disabled class="btn btn-outline  mt-2">
                                                         <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                             <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                             <path d="M9 15l6 -6" />
                                                             <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                                                             <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
                                                         </svg>
                                                         Tidak ada video
                                                     </button>
                                                     @endif

                                                     <div class="card-footer mt-3">
                                                         <button type="submit" class="btn btn-primary ms-auto">
                                                             <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                                             <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                 <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                 <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                                                 <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                                 <path d="M14 4l0 4l-6 0l0 -4" />
                                                             </svg>
                                                             Update
                                                         </button>
                                                     </div>
                                                 </form>
                                                 @else
                                                 <form id="add_sesi_mata_kuliah_form" method="POST" enctype="multipart/form-data">
                                                     @csrf
                                                     <input type="hidden" name="sesi_mata_kuliah_id" value="{{ $id }}">
                                                     <div class="mb-3">
                                                         <label class="form-label">Pengantar</label>
                                                         <textarea class="form-control @error('pengantar')  is-invalid @enderror" name="pengantar" id="tinymce-mytextarea"></textarea>
                                                     </div>
                                                     <div class="mb-3">
                                                         <label class="form-label">File</label>
                                                         <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" id="file">
                                                     </div>
                                                     <div class="mb-3">
                                                         <label class="form-label">Link</label>
                                                         <input type="text" class="form-control @error('link') is-invalid @enderror" name="link" id="link">
                                                     </div>
                                                     <div class="mb-3">
                                                         <label class="form-label">Video</label>
                                                         <input type="text" class="form-control @error('video') is-invalid @enderror" name="video" id="video">
                                                     </div>

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
                                                 </form>
                                                 @endif
                                                 @endif
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                                 @if(Auth::user()->role_id != 2)
                                 <div class="col-md-4 col-lg-4">
                                     <div class="card">
                                         <div class="card-status-top bg-info"></div>
                                         <div class="card-header">
                                             <h4 class="card-title">Catatan</h4>
                                         </div>
                                         <div class="card-body">
                                             <p class="text-muted">
                                                 <strong>File Upload<br /></strong>
                                                 File yang diupload harus berupa <strong>PDF</strong> dengan ukuran <strong>maksimal 10MB</strong>.
                                                 <br />
                                                 <br />
                                                 <strong>Link</strong>
                                                 Link dapat dikosongkan. Jika diisi, maka link yang diisi harus valid.
                                                 <br />
                                                 <br />
                                                 <strong>Video</strong>
                                                 Video dapat dikosongkan. Jika diisi, maka video yang diisi harus berupa link youtube.
                                             </p>
                                         </div>
                                     </div>
                                 </div>
                                 @endif
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>