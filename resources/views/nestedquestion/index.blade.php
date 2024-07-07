@extends('templates.master')
@section('title', 'All Nested Question')
@section('page-name', 'All Nested Question')
@push('styles')
@endpush
@section('content')
<p>aku ganteng</p>

@endsection
@push('scripts')

<script>
    //     function confirmDelete(deleteUrl) {
//     // Tampilkan SweetAlert2 untuk konfirmasi penghapusan
//     Swal.fire({
//         title: 'Apakah Anda yakin?',
//         text: "Anda tidak akan dapat mengembalikan ini!",
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#d33',
//         cancelButtonColor: '#3085d6',
//         confirmButtonText: 'Ya, hapus!',
//         cancelButtonText: 'Batal'
//     }).then((result) => {
//         if (result.isConfirmed) {
//             // Jika pengguna mengonfirmasi, arahkan ke URL penghapusan
//             window.location.href = deleteUrl;
//         }
//     });
// }
</script>

@endpush