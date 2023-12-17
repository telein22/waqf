(function(_scope) {
    function cConfirm( msg, success, declined ) {
        bootbox.confirm({
            message: msg,
            buttons: {
                confirm: {
                    label: _scope.cConfirm.labels.yes,
                    className: 'btn-primary'
                },
                cancel: {
                    label: _scope.cConfirm.labels.no,
                    className: 'btn-danger'
                }
            },
            centerVertical: true,
            callback: function( result ) {

                if ( !result ) {
                    declined && declined();
                    return;
                }

                success && success();
            }
        });
    }

    cConfirm.labels = {
        yes: "yes",
        no: "no"
    };

    _scope.cConfirm = _scope.cConfirm || cConfirm;
})(window);