@extends('layouts.blank')
@push('styles')
    <link href="{{ asset('css/winner.css') }}" rel="stylesheet">
@endpush
@section('content')
    <winner-component :slug="'{!! request()->route("slug") !!}'"></winner-component>
	<canvas id="canvas2"></canvas>

@endsection
@push('scripts')
    <script src="{{ asset('js/winner.js') }}"></script>
@endpush