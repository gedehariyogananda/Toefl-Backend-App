@extends('templates.master')
@section('title', 'All Packet Test ')
@section('page-name', 'Data Packet Full Test')
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
                    <button class="btn tbn-sm btn-primary" data-toggle="modal" data-target="#addPacket">
                        <i class="fa fa-plus"></i>
                        Add
                        Packet 😎</button>

                    {{-- modal add paket --}}
                    <div class="modal fade" id="addPacket" tabindex="-1" role="dialog" aria-labelledby="editModale"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModale">Tambahkan Paket</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('packetmini.store') }}" method="POST">
                                        @csrf

                                        <div class="form-group">
                                            <label for="no_packet">No Packet</label>
                                            <input class="form-control" type="number" name="no_packet" id="no_packet"
                                                required>
                                            <label for="name_packet">Nama Packet</label>
                                            <input type="text" class="form-control" id="name_packet" name="name_packet"
                                                required>
                                            <input type="hidden" name="tipe_test_packet" value="Full Test">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan
                                            Perubahan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-body mt-3">
                    <div class="table">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Packet Full Test</th>
                                    <th>Jumlah Pertanyaan</th>
                                    <th>Service Nested</th>
                                    <th>Service Pertanyaan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataPacketFull as $packet)
                                <tr>
                                    <td>{{ $packet->no_packet }}</td>
                                    <td>{{ $packet->name_packet }}</td>
                                    <td>
                                        @if($packet->questions->count() == 0)
                                        <span class="badge badge-danger"> {{ $packet->questions->count() }}
                                            Pertanyaan</span>
                                        @elseif($packet->questions->count() > 0 && $packet->questions->count() < 30 )
                                            <span class="badge badge-warning"> {{ $packet->questions->count() }}
                                            Pertanyaan</span>
                                            @elseif($packet->questions->count() >= 30 && $packet->questions->count() <
                                                50 ) <span class="badge badge-success"> {{ $packet->questions->count()
                                                }}
                                                Pertanyaan</span>
                                                @elseif($packet->questions->count() >= 50)
                                                <span class="badge badge-primary"> {{ $packet->questions->count() }}
                                                    Pertanyaan</span>
                                                @endif
                                    </td>
                                    <td>
                                        @if($packet->questions->count() == 0)
                                        <div class="text-center">
                                            <button class="btn btn-sm btn-warning" disabled>
                                                <i class="fas fa-plus-circle"></i>
                                        </div>
                                        @else
                                        <div class="text-center">
                                            <a class="btn btn-sm btn-warning"
                                                href="{{ route('packetfull.entryNested', $packet->_id) }}"><i
                                                    class="fas fa-plus-circle"></i></a>
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        <div class="text-center">
                                            <a class="btn btn-sm btn-success"
                                                href="{{ route('packetfull.index', $packet->_id) }}">
                                                <i class="fas fa-sign-in-alt"></i>
                                            </a>
                                        </div>
                                    <td>

                                        <a class="btn btn-sm btn-primary" href="#" data-toggle="modal"
                                            data-target="#editModal{{ $packet->_id }}">
                                            <i class="fas fa-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="confirmDelete('{{ route('packetmini.delete', $packet->_id) }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                {{-- qeustionDetailModal --}}
                                <div class="modal fade" id="editModal{{ $packet->_id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="editModalLabel{{ $packet->_id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $packet->_id }}">Edit
                                                    Packet</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('packetmini.edit', $packet->_id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="form-group">
                                                        <label for="name_packet">Nama Packet</label>
                                                        <input type="text" class="form-control" id="name_packet"
                                                            name="name_packet" value="{{ $packet->name_packet }}"
                                                            required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Simpan
                                                        Perubahan</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>


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