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