@extends('layouts.app')
@push('styles')
   <link href="{{asset('css/dashboard.css')}}" rel="stylesheet">
@endpush
@section('content')
    <user-component></user-component>
@endsection
