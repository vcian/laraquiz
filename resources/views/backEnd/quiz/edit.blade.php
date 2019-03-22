@extends('layouts.auth')

@section('content')
    <div class="card">
        <div class="card-header">{{ __('Create Quiz') }}</div>

        <div class="card-body">
            {{ Form::model($quiz, ['route' => ['admin.quiz.update', $quiz['id']], 'method' => 'PATCH', 'files' => true, 'autocomplete' => 'off']) }}
                @include('backEnd.quiz.partials.form')
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                        {{ Form::submit(__('Update'), ['class' => 'btn btn-primary btn-sm']) }}
                        {{ link_to_route('admin.quiz.index',  __('Cancel'), [], ['class' => 'btn btn-secondary btn-sm']) }}
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection
