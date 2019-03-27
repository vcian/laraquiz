@extends('layouts.app')
@push('styles')
   <link href="{{asset('css/dashboard.css')}}" rel="stylesheet">
@endpush
@section('content')
    <div class="dashboard-main">
        <div id="result">
            <div id="new-user"></div>
            {{--<user-component v-bind:users="users"></user-component>--}}
            @include('frontEnd.partials.user_list_container')
        </div>
    </div>
@endsection
