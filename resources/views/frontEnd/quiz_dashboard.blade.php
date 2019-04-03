@extends('layouts.blank')
@push('styles')
   <link href="{{asset('css/dashboard.css')}}" rel="stylesheet">
@endpush
@section('content')
    <dashboard-component :slug="'{!! request()->route("slug") !!}'"></dashboard-component>
@endsection
