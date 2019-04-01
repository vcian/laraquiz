$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(function () {
    var t = {
        initialize: function () {
            this.methodLinks = $('body');
            this.registerEvents()
        },
        registerEvents: function () {
            this.methodLinks.on('click', 'a[data-method]', this.handleMethod)
        },
        handleMethod: function (e) {
            e.preventDefault();
            var link = $(this);
            var httpMethod = link.data('method').toUpperCase();
            var allowedMethods = ['PUT', 'DELETE', 'GET'];
            var extraMsg = link.data('modal-text');
            var msg = '<i class="fa fa-exclamation-triangle fa-2x" style="vertical-align: middle; color:#f39c12;"></i> &nbsp;Are you sure you want to&nbsp;' + extraMsg;
            if ($.inArray(httpMethod, allowedMethods) === -1) {
                return
            }
            bootbox.dialog({
                message: msg,
                title: "Please Confirm",
                buttons: {
                    success: {
                        label: "Cancel",
                        className: "btn-secondary btn-sm",
                        callback: function () {}
                    },
                    danger: {
                        label: "OK",
                        className: "btn-success btn-sm",
                        callback: function () {
                            var form = $('<form>', {
                                'method': 'POST',
                                'action': link.attr('href')
                            });
                            var hiddenInput = $('<input>', {
                                'name': '_method',
                                'type': 'hidden',
                                'value': link.data('method')
                            });
                            var hiddenToken = $('<input>', {
                                'name': '_token',
                                'type': 'hidden',
                                'value': $('meta[name="csrf-token"]').attr('content')
                            });
                            form.append(hiddenInput).append(hiddenToken).appendTo('body').submit()
                        }
                    }
                }
            })
        }
    };
    t.initialize();

    $(".datatable").DataTable();
});

/**
 * Quiz Timer - START
 */
var timeInSecs;
var ticker;

function startTimer(secs) {
    timeInSecs = parseInt(secs);
    ticker = setInterval("tick()", 1000);
}

function tick() {
    var secs = timeInSecs;
    if (secs > 0) {
        timeInSecs--;
    } else {
        clearInterval(ticker);
    }

    var mins = Math.floor(secs / 60);
    secs %= 60;
    var pretty = ((mins < 10) ? "0" : "") + mins + ":" + ((secs < 10) ? "0" : "") + secs;

    var countdown = $("#countdown");

    if (countdown.length !== 0) {
        countdown.html(pretty);
        document.cookie = "LLQ_time=" + pretty;
        //console.log(pretty === '00:00')
        if (pretty === '00:00') {
            $('#userQuizForm').submit();
        }
    }
}

function countDown() {
    var time = getCookie('LLQ_time');
    if (time !== undefined) {
        var parts = time.split(':'),
            minutes = +parts[0],
            seconds = +parts[1];
        return (minutes * 60 + seconds).toFixed(3);
    } else {
        return (10 * 60);
    }
}

function getCookie(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length === 2) return parts.pop().split(";").shift();
}
startTimer(countDown());

delete_cookie = function (name, path) {
    document.cookie = name + '=;Expires=Thu, 01 Jan 1970 00:00:01 GMT; Path=' + path;
};
/** Quiz Timer - End */