@extends('templates.master')
@section('title', 'Nested Question ')
@section('page-name', 'Assign Nested Question')
@push('styles')

<style>
    .modal-content {
        background-color: #ffffff;
        border-radius: 10px;
    }

    .modal-header {
        background-color: #f0f0f0;
    }

    .modal-body {
        background-color: #f0f0f0;
    }

    .modal-footer {
        background-color: #f0f0f0;
    }
</style>

@endpush

@section('content')
<section class="section">
    <div class="container">
        <div class="center">
            <div class="card">
                <div class="card-header">
                    <button class="btn tbn-sm btn-primary" data-toggle="modal" data-target="#addNested">
                        <i class="fa fa-plus"></i>
                        Tambah Pertanyaan Nested 😎</button>

                    {{-- modal add paket --}}
                    <div class="modal fade" id="addNested" tabindex="-1" role="dialog" aria-labelledby="addNesteds"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addNesteds">Service Tambah Pertanyaan Nested</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('packetfull.addNested', $initPaket) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <p><small class="text-danger">Catatan: Silahkan pilih salah satu
                                                    antara pertanyaan nested teks atau voice/gambar. 🤗</small></p>
                                            <label for="question_nested">Pertanyaan Nested</label>
                                            <textarea class="ckeditor form-control" name="question_nested"
                                                id="question_nested" rows="5" style="resize: vertical;"></textarea>
                                            <br>
                                            <p>Atau </p>

                                            <label for="question_nested_imeg">Pertanyaan Nested Gambar/Voice</label><br>
                                            <input class="form-control" type="file" name="question_nested"
                                                id="question_nested_imeg">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-body mt-3">
                    <div class="table">
                        <table class="table table-bordered" id="table2">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pertanyaan Nested</th>
                                    {{-- <th>Count Question</th>
                                    <th>Entry Nested Question</th> --}}
                                    <th>Assign Pertanyaan Nested Ke Pertanyaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nestedQuestionPacket as $nested)
                                <tr>
                                    <td> {{ $loop->iteration }}</td>
                                    <td>@if(Str::startsWith($nested->question_nested, 'nested_question/'))
                                        <audio controls>
                                            <source src="{{ env('AWS_RESOURCE').'/'.env('AWS_BUCKET').'/'.$nested->question_nested }}"
                                                type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                        @else
                                        @if(strlen($nested->question_nested) < 60) {!! strip_tags($nested->
                                            question_nested) !!}
                                            @else
                                            <button class="btn btn-sm" type="button" data-toggle="modal"
                                                data-target="#detailNested{{ $nested->_id }}">
                                                <i class="fas fa-eye mx-2 view-detail"></i>
                                            </button>
                                            {!! Str::limit(strip_tags($nested->question_nested), 60, '....') !!}

                                            {{-- the modal --}}
                                            {{-- qeustionDetailModal --}}
                                            <div class="modal fade" id="detailNested{{ $nested->_id }}" tabindex="-1"
                                                role="dialog"
                                                aria-labelledby="nesyedDetailModalLabel_{{ $nested->_id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Detail
                                                                Nested</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {!! html_entity_decode($nested->question_nested) !!}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            {{-- packetfull.editNested --}}
                                            @endif
                                            <button class="btn btn-sm" type="button" data-toggle="modal"
                                                data-target="#editNested{{ $nested->_id }}">
                                                <i class="fas fa-pencil"></i>
                                            </button>
                                            <div class="modal fade" id="editNested{{ $nested->_id }}" tabindex="-1"
                                                role="dialog"
                                                aria-labelledby="editInstructionsModalLabel{{ $nested->_id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg"
                                                    role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editInstructionsModalLabel{{ $nested->_id }}">
                                                                Edit nested🤗</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form
                                                            action="{{ route('packetfull.editNested', ['id' => $nested->_id]) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('patch')
                                                            <div class="modal-body">
                                                                <p><small class="text-danger">Catatan: Silahkan pilih
                                                                        salah satu
                                                                        antara pertanyaan nested teks atau voice/gambar.
                                                                        🤗</small></p>

                                                                <div class="form-group">
                                                                    <label for="question">Pertanyaan Text</label>
                                                                    <textarea name="question_nested"
                                                                        class="ckeditor form-control"></textarea>
                                                                </div>

                                                                <p>Atau</p>

                                                                <div class="form-group">
                                                                    <label for="image_question_input">Pertanyaan Gambar
                                                                        / Voice</label>
                                                                    <input type="file" name="question_nested"
                                                                        class="form-control">
                                                                </div>

                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary">Simpan
                                                                    Perubahan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                    </td>
                                    <td><a class="btn btn-sm btn-success"
                                            href="{{ route('packetfull.getAllNested', ['idNested' => $nested->_id, 'idPacket' => $initPaket]) }}"><i
                                                class="fas fa-sign-in-alt"></i></a>
                                    </td>

                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
@push('scripts')


<script>
    $(document).ready(function() {
        $('#table2').DataTable({
            "paging": true,
            "searching": true, 
            "columnDefs": [
                { "searchable": true, "targets": [0, 1] } 
            ],
           
        });
    });
</script>

<script>
    function confirmDelete(deleteUrl) {
        // Tampilkan SweetAlert2 untuk konfirmasi penghapusan
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan dapat mengembalikan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna mengonfirmasi, arahkan ke URL penghapusan
                window.location.href = deleteUrl;
            }
        });
    }
</script>

@endpush
