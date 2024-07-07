@extends('templates.master')
@section('title', 'Videos Tutorial')
@section('page-name', 'Videos Tutorial')
@push('styles')

@endpush
@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Video Tutorial</h4>
    </div>
    <div class="card-body">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/YIwIm6CYEZQ?si=0E24s91f1lJtj8sb"
            title="YouTube video player" frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>
</div>


@endsection
@push('scripts')
@endpush