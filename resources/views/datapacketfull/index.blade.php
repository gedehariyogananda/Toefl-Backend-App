@extends('templates.master')
@section('title', 'Data Packet')
@section('page-name', 'Data Packet')
@push('styles')
<style>
    .modal-dialog {
        max-width: 80%;
        margin: 1.75rem auto;
    }
</style>
@endpush
@section('content')

<section class="section">
    <div class="card">
        <div class="container mt-3">
            @if (request()->routeIs('packetfull.index'))
            <a href="{{ route('packetfull.entryQuestion', $dataId) }}" class="btn btn-primary"><i
                    class="fa fa-plus"></i> Add
                Question🤗</a>
            @endif
            @if (request()->routeIs('packetmini.index'))
            <a href="{{ route('packetmini.entryQuestion', $dataId) }}" class="btn btn-primary"><i
                    class="fa fa-plus"></i> Add
                Question🤗</a>
            @endif
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pertanyaan</th>
                        <th>Kunci Jawaban</th>
                        <th>Pilihan Ganda</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($dataPacketFull as $key => $packet)
                    @foreach($packet->questions as $question)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if(Str::startsWith($question->question, 'questions/'))
                            <audio controls>
                                <source src="{{ env('AWS_RESOURCE').'/'.env('AWS_BUCKET').'/'.$question->question }}" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                            @else
                            @if(strlen($question->question) > 50)
                            <button class="btn btn-sm" type="button" data-toggle="modal"
                                data-target="#questionDetailModal_{{ $question->_id }}">
                                <i class="fas fa-eye mx-2 view-detail"></i>
                            </button>
                            @endif
                            {{ Str::limit($question->question, 50, '...') }}
                            @endif

                            <button class="btn btn-sm" type="button" data-toggle="modal"
                                data-target="#editInstructionsModale{{ $question->_id }}">
                                <i class="fas fa-pencil"></i>
                            </button>
                            <div class="modal fade" id="editInstructionsModale{{ $question->_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="editInstructionsModalLabel{{ $question->_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editInstructionsModalLabel{{ $question->_id }}">
                                                Edit Question🤗</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('packetfull.editQuestion', ['id' => $question->_id]) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('patch')
                                            <div class="modal-body">
                                                <p><small class="text-danger">Catatan: Silahkan pilih salah satu
                                                        antara pertanyaan nested teks atau voice/gambar. 🤗</small></p>

                                                <div class="form-group" id="text_question_div">
                                                    <label for="question">Pertanyaan Text</label>
                                                    <textarea name="question" id="question_text"
                                                        class="form-control"></textarea>
                                                </div>

                                                <p>Atau</p>

                                                <div class="form-group" id="image_question_div">
                                                    <label for="image_question_input">Pertanyaan Gambar / Voice</label>
                                                    <input type="file" name="question" id="question_image_input"
                                                        class="form-control">
                                                </div>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- qeustionDetailModal --}}
                            <div class="modal fade" id="questionDetailModal_{{ $question->_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="questionDetailModalLabel_{{ $question->_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Detail Pertanyaan😉</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {{ $question->question }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if(strlen($question->key_question) > 50)
                            <button class="btn btn-sm" type="button" data-toggle="modal"
                                data-target="#keyQuestionDetailModal_{{ $question->_id }}">
                                <i class="fas fa-eye mx-2 view-detail"></i>
                            </button>
                            @endif
                            {{ Str::limit($question->key_question, 40, '...') }}
                            <button class="btn btn-sm" type="button" data-toggle="modal"
                                data-target="#editInstructionsModal{{ $question->_id }}">
                                <i class="fas fa-pencil"></i>
                            </button>
                            {{-- modal detail keyquestion --}}
                            <div class="modal fade" id="keyQuestionDetailModal_{{ $question->_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="questionDetailModalLabel_{{ $question->_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Detail Key Question😉
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {{ $question->key_question }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>

                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="modal fade" id="editInstructionsModal{{ $question->_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="editInstructionsModalLabel{{ $question->_id }}"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editInstructionsModalLabel{{ $question->_id }}">
                                                Edit Jawaban</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('packetfull.editAnswer', ['id' => $question->_id]) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="editedQuestion{{ $question->_id }}">Jawaban </label>
                                                    <textarea class="form-control"
                                                        id="editedQuestion{{ $question->_id }}" name="key_question"
                                                        rows="6">{{ $question->key_question }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>


                        </td>
                        <td>
                            <div class="text-center">
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#multipleChoiceModal_{{ $question->_id }}"> <i
                                        class="fas fa-list"></i></button>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="multipleChoiceModal_{{ $question->_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="multipleChoiceModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="multipleChoiceModalLabel">Pilihan Ganda Entry
                                                Service
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="multipleChoiceForm_{{ $question->_id }}" method="POST"
                                                action="{{ route('packetfull.entryMultiple', ['id' => $question->_id]) }}">
                                                @csrf
                                                <div class="form-group">
                                                    <div id="multipleChoicesContainer_{{ $question->_id }}">
                                                        <p>Kunci Jawaban : </p>
                                                        <p><small class="text-danger">Catatan: Kunci jawaban tidak perlu
                                                                diimasukkan lagi yaa. 🤗</small></p>
                                                        <input type="text" class="form-control mb-2" name=""
                                                            value="{{ $question->key_question }}" required readonly>
                                                        <p><small class="text-danger">jika mau edit kunci jawaban,
                                                                silahkan edit di halaman sebelumnya yaa🤗</small></p>

                                                        <p>Pilihan Ganda : </p>
                                                        @if($question->multipleChoices)
                                                        @foreach($question->multipleChoices as $choice)
                                                        @if($choice->choice != $question->key_question)
                                                        <input type="text" class="form-control mb-2" name="choice[]"
                                                            value="{{ $choice->choice }}" required>
                                                        @endif
                                                        @endforeach

                                                        @endif
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-success btn-add-choice"
                                                    data-max-choices="5"
                                                    data-parent="#multipleChoicesContainer_{{ $question->_id }}"><i
                                                        class="fa fa-plus"></i> Tambah
                                                    Pilihan Jawaban</button>

                                                <div class="modal-footer mt-3">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-primary btn-save-choices"
                                                        data-question-id="{{ $question->_id }}">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </td>

                        </form>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

@endsection

@push('scripts')

<script>
    $(document).ready(function(){
           
            $('.edit-button').click(function(){
                var inputField = $(this).closest('.input-group').find('input[type="text"]');
                inputField.removeAttr('readonly');
            });

            $('.btn-add-choice').click(function(){
                var maxChoices = $(this).data('max-choices');
                var parent = $($(this).data('parent'));
                var numChoices = parent.find('.form-control').length;
                if (numChoices < maxChoices) {
                    parent.append('<input type="text" class="form-control mb-2" name="choice[]" required>');
                } else {
                    alert('Anda telah mencapai jumlah maksimum pilihan jawaban.');
                }
            });

           
            $('.btn-save-choices').click(function(){
                var questionId = $(this).data('question-id');
                var formData = $('#multipleChoiceForm_' + questionId).serializeArray();
                console.log('Question ID:', questionId);
                console.log('Pilihan Jawaban:', formData);
             
                $('#multipleChoiceModal_' + questionId).modal('hide');
            });
        });

       
</script>

<script>
    $(document).ready(function() {
        $('#table1').DataTable({
            "paging": true,
            "searching": true, 
            "columnDefs": [
                { "searchable": true, "targets": [0, 1] } // Kolom yang tidak ingin dicari
            ],
           
        });
    });
</script>
@endpush