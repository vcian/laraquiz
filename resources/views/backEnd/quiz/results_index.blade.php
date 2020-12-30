@extends('layouts.auth')

@section('title', $title)

@section('content')
    <div class="card">
        <div class="card-header">
            {{ __('Manage Quiz Result') }}
        </div>

        <div class="card-body">
            <table class="table table-striped table-bordered compact datatable">
                <thead>
                    <tr>
                        <th width="50">{{ __('No.') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Total Question') }}</th>
                        <th>{{ __('Total Attempt') }}</th>
                        <th>{{ __('Total Skipped') }}</th>
                        <th>{{ __('Total Correct') }}</th>
                        <th>{{ __('Total Incorrect') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($results)
                        @foreach ($results as $result)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $result->user->full_name }} </td>
                                <td> {{ $result->user->email }} </td>
                                <td> {{ $result->total_attempted + $result->total_skipped }} </td>
                                <td> {{ $result->total_attempted }} </td>
                                <td> {{ $result->total_skipped }} </td>
                                <td> {{ $result->total_wrong }} </td>
                                <td> {{ $result->total_right }} </td>
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
