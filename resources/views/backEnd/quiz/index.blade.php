@extends('layouts.auth')

@section('title', $title)

@section('content')
    <div class="card">
        <div class="card-header">
            {{ __('Manage Quiz') }}
            {{ link_to_route('admin.quiz.create', "Add Quiz", [], ['class' => 'btn btn-success btn-sm float-right']) }}
        </div>

        <div class="card-body">
            <table class="table table-striped table-bordered compact datatable">
                <thead>
                    <tr>
                        <th width="50">{{ __('No.') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Quiz Time') }}</th>
                        <th>{{ __('Start Time') }}</th>
                        <th>{{ __('End Time') }}</th>
                        <th width="100">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($quizzes)
                        @foreach ($quizzes as $quiz)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $quiz->quiz_name }} </td>
                                <td> {{ $quiz->time_limit }} </td>
                                <td> {{ $quiz->start_time }} </td>
                                <td> {{ $quiz->end_time }} </td>
                                <td>
                                    {!! Html::decode(link_to_route('admin.quiz.edit', '<i class="fas fa-edit"></i>', ['id' => $quiz->id], ['class' => 'btn btn-sm btn-primary'])) !!}
                                    {!! Html::decode(link_to_route('admin.quiz.destroy', '<i class="fas fa-trash-alt"></i>', ['id' => $quiz->id], ['class' => 'btn btn-sm btn-danger', "data-method" => "delete", 
                                        "data-modal-text" => "delete the record?", "data-original-title" => "Delete quiz"])) !!}
                                        {!! Html::decode(link_to_route('quiz.play', '<i class="fas fa-eye"></i>', ['slug' => $quiz->slug], ['class' => 'btn btn-sm btn-primary','target' => '_blank'])) !!}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr align="center">
                            <td colspan="6"> {{ __('No data found.') }} </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
