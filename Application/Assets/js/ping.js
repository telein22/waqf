(function(_scope){

    var _count  = 1;

    var _subscriptions = {};

    var _delay = 5000;

    var _values = {};

    var _ping = function ping () {

        // before handler run
        for ( var prop in _subscriptions ) {
            _subscriptions[prop].forEach(function(v, i) {

                var before = v[0];
    
                // No after handler
                if ( !before ) return;
    
                var value = before.apply({}, [_count]);
    
                // Add the values before sending the params.
                if ( !value ) return;
                
                if ( !_values[prop] ) _values[prop] = [];                        
                _values[prop].push(value);
    
            });
        }

        $.ajax({
            url: URLS.ping,
            data: {
                count: _count,
                values: JSON.stringify(_values)
            },
            success: function ( data ) {
                if ( data.info !== 'success' ) return;

                // else handle the handlers.
                var f = data.payload;

                for ( var prop in f )
                {
                    if ( !_subscriptions[prop] ) {
                        console.log("No handler for prop");
                        return;
                    }

                    // after handler run
                    _subscriptions[prop].forEach(function(v, i) {

                        var after = v[1];

                        // No after handler
                        if ( !after ) return;

                        var vars = [_count];
                        if ( f[prop] ) vars = vars.concat(f[prop]);

                        after.apply({}, vars);

                    });
                }

                // Reset values variable 
                _values = {};
                
                _count++;
            },
            complete: function () {
                setTimeout(function() {
                    ping();
                }, _delay);
            }
        });

    };

    _scope.ping = _scope.ping || {
        subscribe: function ( type, before, after ) {
            if ( !_subscriptions[type] ) _subscriptions[type] = [];

            _subscriptions[type].push([before, after]);
        }
    };

    // Call the ping on document load.
    window.addEventListener('load', function() {
        _ping();
    }, false);

})(window);