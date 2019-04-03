@extends('layouts.blank')
@push('styles')
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
@endpush
@section('content')
    <div class="form">
        @include('layouts.partials.messages')
        <h1>Laravel Quiz</h1>
        <div class="container">
            {{ Form::open(['route' => ['quiz.registerUser', $slug], 'method' => 'post', 'autocomplete' => 'off', 'name' => 'login']) }}
                <div class="form-group {{ $errors->has("full_name") ? "has-error" : '' }}">
                    {!! Html::decode(Form::label('full_name', __('Full Name') . '<span class="text-danger">*</span> :', ['class' => 'col-form-label'])) !!}
                    {{ Form::text('full_name', null, ['placeholder' => __('Full Name'), "class" => "form-control form-control-sm"]) }}
                    {!! Html::decode($errors->has("full_name") ? $errors->first('full_name', '<span class="text-danger">:message</span>') : '') !!}
                </div>
                <div class="form-group {{ $errors->has("nick_name") ? "has-error" : '' }}">
                    {!! Html::decode(Form::label('nick_name', __('Nick Name') . '<span class="text-danger">*</span> :', ['class' => 'col-form-label'])) !!}
                    {{ Form::text('nick_name', null, ['placeholder' => __('Nick Name'), "class" => "form-control form-control-sm"]) }}
                    {!! Html::decode($errors->has("nick_name") ? $errors->first('nick_name', '<span class="text-danger">:message</span>') : '') !!}
                </div>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                        {{ Form::submit(__('Click Here To Play Quiz'), ['class' => 'btn btn-sm']) }}
                    </div>
                </div>
                {{-- <button id="login" class="btn" type="submit" >Take Quiz</button> --}}
            {{ Form::close() }}
        </div>
    </div>
    <canvas id="canvas" width="1000" height="1000"></canvas>
@endsection
@push('scripts')
    <script src="{{ asset('js/quiz-login.js') }}"></script>
@endpush
