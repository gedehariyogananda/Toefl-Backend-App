@extends('templates.master')
@section('title', 'Data Packet')
@section('page-name', 'Data Packet')
@push('styles')
@endpush
@section('content')

<div class="card">
    <div class="card-body">
        <form action="{{ route('packetfull.postEntryQuestion') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name_packet">Paket </label>
                <select name="packet_id" id="name_packet" class="form-control">
                    <option value="{{ $dataPacketFull->_id }}"> {{ $dataPacketFull->name_packet }} </option>
                </select>
            </div>
            <div class="form-group">
                <label for="question">Tipe Inputan Pertanyaan</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="question_type" id="text_question" value="text"
                        checked>
                    <label class="form-check-label" for="text_question">Pertanyaan Teks</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="question_type" id="image_question" value="image">
                    <label class="form-check-label" for="image_question">Pertanyaan Gambar / Voice</label>
                </div>
            </div>

            <div class="form-group" id="text_question_div">
                <label for="question">Pertanyaan</label>
                <textarea name="question" id="question" class="form-control"></textarea>
            </div>

            <div class="form-group" id="image_question_div">
                <label for="image_question_input">Upload Pertanyaan</label>
                <input type="file" name="question" id="image_question_input" class="form-control">
            </div>
            <div class="form-group">
                <label for="key_question">Kunci Jawaban</label>
                <input type="text" name="key_question" id="key_question" class="form-control">
            </div>
            <div class="form-group">
                <label for="type_question">Tipe Pertanyaan</label>
                <select name="type_question" id="type_question" class="form-control">
                    <option value="">-- Pilih Tipe Pertanyaan --</option>
                    <option value="Listening">Listening</option>
                    <option value="Structure And Written Expression">Structure And Written Expression</option>
                    <option value="Reading">Reading</option>
                </select>
            </div>
            <div class="form-group" id="part_question_div" style="display: none;">
                <label for="part_question">Part Pertanyaan</label>
                <select name="part_question" id="part_question" class="form-control">
                    <option value="">-- Pilih Part Pertanyaan --</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>


@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var textQuestionDiv = document.getElementById('text_question_div');
        var imageQuestionDiv = document.getElementById('image_question_div');

        // Memastikan Text Question terpilih dan bidangnya ditampilkan secara default
        document.getElementById('text_question').checked = true;
        textQuestionDiv.style.display = 'block';
        imageQuestionDiv.style.display = 'none';

        document.querySelectorAll('input[name="question_type"]').forEach(function(elem) {
            elem.addEventListener('change', function() {
                if (this.value === 'image') {
                    textQuestionDiv.style.display = 'none';
                    imageQuestionDiv.style.display = 'block';
                } else {
                    textQuestionDiv.style.display = 'block';
                    imageQuestionDiv.style.display = 'none';
                }
            });
        });

        document.getElementById('type_question').addEventListener('change', function() {
            var typeQuestion = this.value;
            var partQuestionDiv = document.getElementById('part_question_div');
            var partQuestionSelect = document.getElementById('part_question');

            if (typeQuestion === 'Listening' || typeQuestion === 'Structure And Written Expression') {
                partQuestionDiv.style.display = 'block';
            } else {
                partQuestionDiv.style.display = 'none';
            }

            if (typeQuestion === 'Listening') {
                partQuestionSelect.innerHTML = `
                    <option value="A-SHORT TALKS">A - Short Talks</option>
                    <option value="B-Long Conversation">B - Long Conversation</option>
                    <option value="C-Mini-Lectures">C - Mini-Lectures</option>
                `;
            } else if (typeQuestion === 'Structure And Written Expression') {
                partQuestionSelect.innerHTML = `
                    <option value="A-Sentence Completitions">A - Sentence Completitions</option>
                    <option value="B-Error Recognition">B - Error Recognition</option>
                `;
            } else {
                partQuestionSelect.innerHTML = `<option value="">-- Pilih Part Question --</option>`;
            }
        });
    });
</script>
@endpush