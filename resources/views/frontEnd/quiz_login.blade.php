@extends('layouts.app')

@section('content')
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">{{ __('Login') }}</div>

            <div class="card-body">
                {{ Form::open(['route' => ['quiz.registerUser', $slug], 'method' => 'post', 'autocomplete' => 'off']) }}
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
                            {{ Form::submit(__('Click Here To Play Quiz'), ['class' => 'btn btn-primary btn-sm']) }}
                        </div>
                    </div>
                {{ Form::close() }}
            </div> 
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            delete_cookie('LLQ_time', '/{{ request()->path() }}')
        });
    </script>
@endpush
