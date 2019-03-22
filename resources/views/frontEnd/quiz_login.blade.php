@extends('layouts.blank')
@push('styles')
    <style>
        .form {
            text-align:center;
            font-family: 'Raleway', sans-serif;
            min-height: 470px;
            background-color: white;
            border-radius:0px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 470px;
            -webkit-box-shadow: 10px 10px 60px 10px rgba(0, 0, 0, 0.2);
            -moz-box-shadow: 10px 10px 60px 10px rgba(0, 0, 0, 0.2);
            box-shadow: 10px 10px 60px 10px rgba(0, 0, 0, 0.2);
        }

        h1{
            color:#f55247;
            margin-top:50px;
        }
        body {
            margin: 0;
        }

        .container {
            margin-top:30px;
            position: relative;
            padding: 30px;
        }

        .background {
            background: #eb3349;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #eb3349, #f45c43);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #eb3349, #f45c43);
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0.8;
        }

        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: block;
        }

        .form-control {
            width: 100%;
            height: 30px;
            border-width: 0 0 2px 0px;
            border-color: lightgray;
            background-color: transparent;
            opacity: 1;
            font-size: 16px;
            color: #EC3A48;
            -webkit-transition: border-color 1s ease-out;
            -moz-transition: border-color 1s ease-out;
            -o-transition: border-color 1s ease-out;
        }

        label{
            font-size:12px;
            float:left;
        }

        .form-control:focus {
            outline: 0;
            opacity: 1;
            border-color: #EC3A48;
            background-color: #fff;
            box-shadow: none !important;
        }

        ::-webkit-input-placeholder {
            /* Chrome/Opera/Safari */
            color: lightgray;
            font-size: 16px;
        }

        ::-moz-placeholder {
            /* Firefox 19+ */
            color: lightgray;
            font-size: 16px;
        }

        :-ms-input-placeholder {
            /* IE 10+ */
            color: lightgray;
            font-size: 16px;
        }

        :-moz-placeholder {
            /* Firefox 18- */
            color: lightgray;
            font-size: 16px;
        }

        .form-control:focus::-webkit-input-placeholder {
            color: transparent;
        }

        .form-control:focus:-moz-placeholder {
            color: transparent;
        }


        /* FF 4-18 */

        .form-control:focus::-moz-placeholder {
            color: transparent;
        }


        /* FF 19+ */

        .form-control:focus:-ms-input-placeholder {
            color: transparent;
        }

        input[type=submit] {
            margin-top: 60px;
            height: 50px;
            width: 100%;
            background-color: #f55247;
            position: relative;
            border: 0;
            border-radius: 10px;
            color: white;
            font-size: 20px !important;
            transform-origin: 50% 50%;
        }

        input[type=submit]:focus {
            outline: none;
        }

        input[type=submit]:hover {
            opacity: 0.8;
            cursor: pointer;
            color: white;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        #canvas {
            width: 100%;
            height: 100%;
            /*background: #04BBD3;*/
            background: #333;
        }
        @media only screen and (max-width: 500px) {
            .form {
                max-width: 320px;
                min-height: auto;
            }
        }
    </style>
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
    <script>
        /* $(function() {
            $("form[name='login']").validate({
                rules: {
                    full_name: "required",
                    name:{
                        required: true,
                        maxlength: 10

                    },
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        }); */
        var Canvas = document.getElementById('canvas');
        var ctx = Canvas.getContext('2d');
        can_w = parseInt(canvas.getAttribute('width'));
        can_h = parseInt(canvas.getAttribute('height'));
        var resize = function() {
            Canvas.width = Canvas.clientWidth;
            Canvas.height = Canvas.clientHeight;
        };
        window.addEventListener('resize', resize);
        resize();

        var elements = [];
        var presets = {};

        presets.o = function (x, y, s, dx, dy) {
            return {
                x: x,
                y: y,
                r: 12 * s,
                w: 5 * s,
                dx: dx,
                dy: dy,
                draw: function(ctx, t) {
                    this.x += this.dx;
                    this.y += this.dy;

                    ctx.beginPath();
                    ctx.arc(this.x + + Math.sin((50 + x + (t / 10)) / 100) * 3, this.y + + Math.sin((45 + x + (t / 10)) / 100) * 4, this.r, 0, 2 * Math.PI, false);
                    ctx.lineWidth = this.w;
                    ctx.strokeStyle = '#fff';
                    ctx.stroke();
                }
            }
        };

        presets.x = function (x, y, s, dx, dy, dr, r) {
            r = r || 0;
            return {
                x: x,
                y: y,
                s: 100 * s,
                w: 5 * s,
                r: r,
                dx: dx,
                dy: dy,
                dr: dr,
                draw: function(ctx, t) {
                    this.x += this.dx;
                    this.y += this.dy;
                    this.r += this.dr;

                    var _this = this;
                    var line = function(x, y, tx, ty, c, o) {
                        o = o || 0;
                        ctx.beginPath();
                        ctx.moveTo(-o + ((_this.s / 2) * x), o + ((_this.s / 2) * y));
                        ctx.lineTo(-o + ((_this.s / 2) * tx), o + ((_this.s / 2) * ty));
                        ctx.lineWidth = _this.w;
                        ctx.strokeStyle = c;
                        ctx.stroke();
                    };

                    ctx.save();

                    ctx.translate(this.x + Math.sin((x + (t / 10)) / 100) * 5, this.y + Math.sin((10 + x + (t / 10)) / 100) * 2);
                    ctx.rotate(this.r * Math.PI / 180);

                    line(-1, -1, 1, 1, '#f55247');
                    line(1, -1, -1, 1, '#f55247');

                    ctx.restore();
                }
            }
        };

        for(var x = 0; x < Canvas.width; x++) {
            for(var y = 0; y < Canvas.height; y++) {
                if(Math.round(Math.random() * 8000) == 1) {
                    var s = ((Math.random() * 5) + 1) / 10;
                    if(Math.round(Math.random()) == 1)
                        elements.push(presets.o(x, y, s, 0, 0));
                    else
                        elements.push(presets.x(x, y, s, 0, 0, ((Math.random() * 3) - 1) / 10, (Math.random() * 360)));
                }
            }
        }

        setInterval(function() {
            ctx.clearRect(0, 0, Canvas.width, Canvas.height);

            var time = new Date().getTime();
            for (var e in elements)
                elements[e].draw(ctx, time);
        }, 10);

        $(document).ready(function() {
            delete_cookie('LLQ_time', '/{{ request()->path() }}')
        });
    </script>
@endpush
