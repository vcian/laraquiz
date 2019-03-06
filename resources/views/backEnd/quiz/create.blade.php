@extends('layouts.auth')
@push('styles')
    <style type="text/css">
        .hr-text {
            line-height: 1em;
            position: relative;
            outline: 0;
            border: 0;
            text-align: center;
            height: 1.5em;
            opacity: .5;
        }
        .hr-text:before {
            content: '';
            /*  use the linear-gradient for the fading effect
            // use a solid background color for a solid bar */
            background: linear-gradient(to right, transparent, #818078, transparent);
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
        }
        .hr-text:after {
            content: attr(data-content);
            position: relative;
            display: inline-block;
            padding: 0 .5em;
            line-height: 1.5em;
            /* this is really the only tricky part, you need to specify the background color of the container element... */
            background-color: #fcfcfa;
        }
    </style>
@endpush
@section('content')
    <div class="card">
        <div class="card-header">{{ __('Create Quiz') }}</div>

        <div class="card-body">
            {{ Form::open(['route' => ['quiz.store'], 'method' => 'post', 'files' => true, 'autocomplete' => 'off']) }}
                @include('backEnd.quiz.partials.form')
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                        {{ Form::submit(__('Add New'), ['class' => 'btn btn-primary btn-sm']) }}
                        {{ link_to_route('quiz.index',  __('Cancel'), [], ['class' => 'btn btn-secondary btn-sm']) }}
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('input[type="file"]').change(function(e){
                var fileName = e.target.files[0].name;
                $("#selectedFile").html(fileName);
            });
        });
    </script>
@endpush
