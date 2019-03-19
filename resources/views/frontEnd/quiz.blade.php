@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="card">
        <div class="card-header">
            {{ $quiz->quiz_name ?? "" }}
            <span id="countdown" class="float-right" data-time="{{ $quiz->time_limit ? date('i:s', strtotime($quiz->time_limit)) : '' }}" data-quiz="{{ $quiz->slug }}"></span>
        </div>

        <div class="card-body">
            {!! Form::open(['route' => ['quiz.store', $quiz->slug], 'class' => 'js-frm-create-user userQuizForm', 'role' => 'form', 'id' => 'userQuizForm', 'enctype' => 'multipart/form-data']) !!}
                <div class="form-group">
                    <div class="list-group">
                        @foreach($quiz->questions as $question)
                            <div class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-start">
                                    <h6><span class="badge badge-info">Que. {{ $loop->iteration }} : </span> </h6> <h5 class="ml-2 mb-1"> {{ $question->question }}</h5>
                                </div>
                                @foreach($question->options as $option)
                                    <div class="custom-control custom-radio ">
                                        {{ Form::radio('options[' . $question->id . ']', $option->id, false, ['class' => 'custom-control-input', 'id' => 'options' . $option->id]) }}
                                        {{ Form::label('options' . $option->id, $option->option, ['class' => 'custom-control-label']) }}
                                    </div>
                                @endforeach
                                {{-- <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                                <small>Donec id elit non mi porta.</small> --}}
                            </div>
                                {{-- <li class="list-group-item">
                                    <h5>{{$question->question}}</h5>
                                    @foreach($question->options as $option)
                                        <div class="custom-control custom-radio ">
                                            {{ Form::radio('options[' . $question->id . ']', $option->id, false, ['class' => 'custom-control-input', 'id' => 'options' . $option->id]) }}
                                            {{ Form::label('options' . $option->id, $option->option, ['class' => 'custom-control-label']) }}
                                        </div>
                                    @endforeach
                                </li> --}}
                        @endforeach
                    </div>
                    {{ Form::hidden('total_q', count($quiz->questions)) }}
                </div>
                {{ Form::submit(__('Submit'), ['class' => 'btn btn-sm btn-primary']) }}
            {!! Form::close() !!}
        </div>
    </div>
@endsection
