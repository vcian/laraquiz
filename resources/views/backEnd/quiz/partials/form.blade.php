<div class="row">
    <div class="col-12 col-md-12 col-lg-6 col-xl-4">
        <div class="form-group {{ $errors->has("quiz_name") ? "has-error" : '' }}">
            {!! Html::decode(Form::label('quiz_name', __('Name') . '<span class="text-danger">*</span> :', ['class' => 'col-form-label'])) !!}
            {{ Form::text('quiz_name', null, ['placeholder' => __('Name'), "class" => "form-control form-control-sm"]) }}
            {!! Html::decode($errors->has("quiz_name") ? $errors->first('quiz_name', '<span class="text-danger">:message</span>') : '') !!}
        </div>
    </div>
    <div class="col-12 col-md-12 col-lg-6 col-xl-4">
        <div class="form-group {{ $errors->has("start_time") ? "has-error" : '' }}">
            {!! Html::decode(Form::label('start_time', 'Start time<span class="text-danger">*</span> :', ['class' => 'col-form-label'])) !!}
            {{ Form::text('start_time', null, ['placeholder' => __('Start Time'), "class" => "form-control form-control-sm start_time"]) }}
            {!! Html::decode($errors->has("start_time") ? $errors->first('start_time', '<span class="text-danger">:message</span>') : '') !!}
        </div>
    </div>
    <div class="col-12 col-md-12 col-lg-6 col-xl-4">
        <div class="form-group {{ $errors->has("end_time") ? "has-error" : '' }}">
            {!! Html::decode(Form::label('end_time', 'End time<span class="text-danger">*</span> :', ['class' => 'col-form-label'])) !!}
            {{ Form::text('end_time', null, ['placeholder' => __('End Time'), "class" => "form-control form-control-sm end_time"]) }}
            {!! Html::decode($errors->has("end_time") ? $errors->first('end_time', '<span class="text-danger">:message</span>') : '') !!}
        </div>
    </div>
    <div class="col-12 col-md-12 col-lg-6 col-xl-4">
        <div class="form-group {{ $errors->has("time_limit") ? "has-error" : '' }}">
            {!! Html::decode(Form::label('time_limit', 'Quiz time<span class="text-danger">*</span> <small>(MM:SS)</small> :', ['class' => 'col-form-label'])) !!}
            {{ Form::text('time_limit', null, ['placeholder' => __('Quiz Total Time'), "class" => "form-control form-control-sm"]) }}
            {!! Html::decode($errors->has("time_limit") ? $errors->first('time_limit', '<span class="text-danger">:message</span>') : '') !!}
        </div>
    </div>
    <div class="col-12 col-md-12 col-lg-6 col-xl-4">
        <div class="form-group {{ $errors->has("status") ? "has-error" : '' }}">
            {!! Html::decode(Form::label('', 'Status<span class="text-danger">*</span> :', ['class' => 'control-label'])) !!}
            <div>
                <div class="custom-control custom-radio">
                    {{ Form::radio("status", '1', false, ['class' => 'custom-control-input', 'id' => 'status1']) }}
                    {!! Html::decode(Form::label('status1', __('Active'), ['class' => 'custom-control-label'])) !!}
                </div>
                <div class="custom-control custom-radio">
                    {{ Form::radio("status", '0', false, ['class' => 'custom-control-input', 'id' => 'status2']) }}
                    {!! Html::decode(Form::label('status2', __('Inactive'), ['class' => 'custom-control-label'])) !!}
                </div>
            </div>
            {!! Html::decode($errors->has("status") ? $errors->first('status', '<span class="text-danger">:message</span>') : '') !!}
        </div>
    </div>
</div>

@if(Route::currentRouteName() == 'admin.quiz.create')
    <div class="row">
        <div class="col-12">
            <hr class="mt-0">
            <div class="col-6 offset-md-1">
                <span class="text-danger"><b>Note* :</b> Either choose excel file or Add questions below</span>
            </div>
            <div class="col-6 offset-md-3">
                <div class="custom-file">
                    <label class="custom-file-label" for="import_questions" id="selectedFile">Choose excel file to import quiz questions</label>
                    {{ Form::file('import_questions', ['class' => 'custom-file-input']) }}
                    {!! Html::decode($errors->has("import_questions") ? $errors->first('import_questions', '<span class="text-danger">:message</span>') : '') !!}
                </div>
            </div>
            <hr class="hr-text" data-content="OR">
        </div>
    </div>
@endif
<div class="row">
    <div class="col-12">
            <table class="table table-bordered table-hover tablesorter">
                <thead>
                    <tr>
                        <th class="p-1 pl-3"> {{ __('Add Questions') }} <span class="text-danger">*</span></th>
                        <th style="vertical-align: middle;" class="text-center p-1" width="10"><a href="#" class="btn btn-sm btn-success fieldsaddmore-addbtn"> <i class="fa fa-plus"></i> </a></th>
                    </tr>
                </thead>
                <!-- Main element container -->
                <tbody class="admore-fields">
                    @if (old('questions'))
                        @foreach (old('questions') as $key => $question)
                            <tr class="fieldsaddmore-row rowId-{{ $key }}">
                                @if (isset($question['id']))
                                    {!! Form::hidden('questions[' . $key . '][id]', $question['id']) !!}
                                @endif
                                <td>
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <div class="input-group input-group-sm ">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">{{ __('Question') . " $key" }} <span class="text-danger">*</span> : </span>
                                                </div>
                                                {{ Form::text('questions[' . $key . '][question]', $question["question"], ['placeholder' => __('Question'), "class" => "form-control"]) }}
                                            </div>
                                            {!! Html::decode($errors->has("questions.$key.question") ? $errors->first("questions.$key.question", '<span class="text-danger">:message</span>') : '') !!}
                                        </div>
                                        @foreach ($question['options'] as $oKey => $option)
                                            <div class="col-12 col-md-6 col-lg-6 col-xl-3 {{ $loop->last ? 'pl-1' : ($loop->first ? 'pr-1' : 'pr-1 pl-1') }} ">
                                                @if (isset($question['id']))
                                                    {{ Form::hidden('questions[' . $key . '][options][' . $oKey . '][id]', $option["id"]) }}
                                                @endif
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            {{ Form::radio('questions[' . $key . '][answer]', $oKey, (isset($question["answer"]) && $question["answer"] == $oKey) ?? true, []) }}
                                                        </div>
                                                    </div>
                                                    {{ Form::text('questions[' . $key . '][options][' . $oKey . '][option]', $option["option"], ['placeholder' => __("Option") . " " . ($oKey+1), "class" => "form-control form-control-sm"]) }}
                                                </div>
                                                {!! Html::decode($errors->has("questions.$key.options.$oKey.option") ? $errors->first("questions.$key.options.$oKey.option", '<span class="text-danger">:message</span>') : '') !!}
                                            </div> 
                                        @endforeach
                                    </div>
                                </td>
                                <td class="text-center p-1"><a href="#" data-rowid="{{ $key }}" class="btn btn-sm btn-danger fieldsaddmore-removebtn"><i class="fas fa-minus"></i></a></td>
                            </tr>
                        @endforeach
                    @elseif (isset($quiz['questions']) && count($quiz['questions']))
                        @foreach ($quiz['questions'] as $key => $question)
                            <tr class="fieldsaddmore-row rowId-{{ $key+1 }}">
                                @if (isset($question['id']))
                                    {!! Form::hidden('questions[' . $key . '][id]', $question['id']) !!}
                                @endif
                                <td>
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <div class="input-group input-group-sm ">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">{{ __('Question') . " " . ($key+1) }} <span class="text-danger">*</span> : </span>
                                                </div>
                                                {{ Form::text('questions[' . $key . '][question]', $question["question"], ['placeholder' => __('Question'), "class" => "form-control"]) }}
                                            </div>
                                            {!! Html::decode($errors->has("questions.$key.question") ? $errors->first("questions.$key.question", '<span class="text-danger">:message</span>') : '') !!}
                                        </div>
                                        @foreach ($question['options'] as $oKey => $option)
                                            <div class="col-12 col-md-6 col-lg-6 col-xl-3 {{ $loop->last ? 'pl-1' : ($loop->first ? 'pr-1' : 'pr-1 pl-1') }} ">
                                                {{ Form::hidden('questions[' . $key . '][options][' . $oKey . '][id]', $option["id"]) }}
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            {{ Form::radio('questions[' . $key . '][answer]', $oKey, $option['answer'], []) }}
                                                        </div>
                                                    </div>
                                                    {{ Form::text('questions[' . $key . '][options][' . $oKey . '][option]', $option["option"], ['placeholder' => __("Option") . " " . ($oKey+1), "class" => "form-control form-control-sm"]) }}
                                                </div>
                                                {!! Html::decode($errors->has("questions.$key.options.$oKey.option") ? $errors->first("questions.$key.options.$oKey.option", '<span class="text-danger">:message</span>') : '') !!}
                                            </div> 
                                        @endforeach
                                    </div>
                                </td>
                                <td class="text-center p-1"><a href="#" data-rowid="{{ $key+1 }}" class="btn btn-sm btn-danger fieldsaddmore-removebtn"><i class="fas fa-minus"></i></a></td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
    </div>
</div>
<div class="clearfix"></div>

<script id="fieldsaddmore-template" type="text/template">
    <tr class="fieldsaddmore-row rowId">
        <td class="">
            <div class="row">
                <div class="col-12">
                    <div class="input-group input-group-sm mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{{ __('Question') }} key<span class="text-danger">*</span> : </span>
                        </div>
                        {{ Form::text('questions[key][question]', null, ['placeholder' => __('Question'), "class" => "form-control"]) }}
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6 col-xl-3 pr-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                {{ Form::radio('questions[key][answer]', '0', null, []) }}
                            </div>
                        </div>
                        {{ Form::text('questions[key][options][0][option]', null, ['placeholder' => __('Option') . ' 1', "class" => "form-control form-control-sm"]) }}
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6 col-xl-3 pr-1 pl-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                {{ Form::radio('questions[key][answer]', '1', null, []) }}
                            </div>
                        </div>
                        {{ Form::text('questions[key][options][1][option]', null, ['placeholder' => __('Option') . ' 2', "class" => "form-control form-control-sm"]) }}
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6 col-xl-3 pr-1 pl-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                {{ Form::radio('questions[key][answer]', '2', null, []) }}
                            </div>
                        </div>
                        {{ Form::text('questions[key][options][2][option]', null, ['placeholder' => __('Option') . ' 3', "class" => "form-control form-control-sm"]) }}
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6 col-xl-3 pl-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                {{ Form::radio('questions[key][answer]', '3', null, []) }}
                            </div>
                        </div>
                        {{ Form::text('questions[key][options][3][option]', null, ['placeholder' => __('Option') . ' 4', "class" => "form-control form-control-sm"]) }}
                    </div>
                </div>
            </div>
        </td>
        <td class="text-center p-1"><a href="#" data-rowid="key" class="btn btn-sm btn-danger fieldsaddmore-removebtn"><i class="fas fa-minus"></i></a></td>
    </tr>
</script>
@push('scripts')
    <script>
        $(".start_time").datetimepicker({format: 'YYYY-MM-DD HH:mm'});
        $(".end_time").datetimepicker({format: 'YYYY-MM-DD HH:mm'});
        $(document).ready(function() {
            //Simple plugin implementation
            $('.admore-fields').fieldsaddmore({                
                templateEle: "#fieldsaddmore-template",
                rowEle: ".fieldsaddmore-row",
                addbtn: ".fieldsaddmore-addbtn",
                removebtn: ".fieldsaddmore-removebtn",
                min: ($('.fieldsaddmore-row').length > 0) ? 0 : 0, // 1,
            });
        });
    </script>
@endpush
