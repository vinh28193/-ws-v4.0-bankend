(function ($) {

    $.fn.wsSearch = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.wsSearch');
            return false;
        }
    };

    var defaults = {
        typeOfSearch: undefined,
        enableFilter: true,
        filterParams: 'filter',
        absoluteUrl: undefined,
        portals: []
    };

    var eventHandlers = {};

    var methods = {
        init: function (options) {
            return this.each(function () {
                var $search = $(this);
                var settings = $.extend({}, defaults, options || {});
                if (settings.absoluteUrl === undefined) {
                    settings.absoluteUrl = $search.data('action')
                }
                $search.data('wsSearch', settings);
                initEventHandler($search, 'filter', 'change', 'input.form-check-input', function (event) {
                    methods.applyFilter.call($search, $(this));
                    return false;
                });
                methods.search.apply($search);
                return false;
            });
        },
        search: function () {
            var $search = $(this);
            var data = $search.data('wsSearch');
            $.each(data.portals, function (index, portal) {
                var queryParams = {};
                $.each(yii.getQueryParams(data.absoluteUrl), function (name, value) {
                    queryParams[name] = value;
                });
                if (!('type' in queryParams)) {
                    queryParams['type'] = portal
                }
                var deferredArrays = deferredArray();

                $.when.apply(this, deferredArrays).always(function () {
                    $.ajax({
                        url: data.absoluteUrl,
                        type: 'GET',
                        data: queryParams,
                        dataType: 'json',
                        success: function (response) {
                            $search.find('div.' + portal + '-search').html(response);
                        },
                        error: function () {
                        }
                    });
                });
            });
        },

        applyFilter: function ($element) {
            var $search = $(this);
            var data = $search.data('wsSearch');
            var settings = data.settings;
            var queryParams = {};
            $.each(yii.getQueryParams(data.absoluteUrl), function (name, value) {
                if (!(name in queryParams)) {
                    queryParams[name] = [];
                }
                queryParams[name].push(value);
            });
            console.log($element);
            methods.search.apply($search);
        },
        data: function () {
            return this.data('wsSearch');
        },
    };
    var deferredArray = function () {
        var array = [];
        array.add = function (callback) {
            this.push(new $.Deferred(callback));
        };
        return array;
    };
    var initEventHandler = function ($search, type, event, selector, callback) {
        var id = $search.attr('id');
        var prevHandler = eventHandlers[id];
        if (prevHandler !== undefined && prevHandler[type] !== undefined) {
            var data = prevHandler[type];
            $(document).off(data.event, data.selector);
        }
        if (prevHandler === undefined) {
            eventHandlers[id] = {};
        }
        $(document).on(event, selector, callback);
        eventHandlers[id][type] = {event: event, selector: selector};
    };

    var ebayFilterParams = function (params, value) {
        params = params.split(';');
        value = value.split(':');
        var newParam = [];
        var pushed = false;
        $.each(params, function (index, parameter) {
            var paramSplit = parameter.split(':');
            if (value[0] === paramSplit[0]) {
                paramSplit[1] = makeParam(paramSplit[1], value[1], ',');
                pushed = true;
            }
            if (paramSplit[1] !== '' && paramSplit[1] !== null) {
                newParam.push(paramSplit.join(':'));
            }
        });
        if (!pushed) {
            newParam.push(value.join(':'));
        }
        return newParam.join(';');
    };
    var makeParam = function (params, value, separator) {
        params = params.split(separator);
        var check = hasValue(params, value);
        if (check.length > 0) {
            $.each(check, function (index, position) {
                params = removeValue(params, position);
            });
        } else {
            params.push(value);
        }
        return params.join(separator);
    };
    var hasValue = function (arrays, key) {
        var pushed = [];
        $.each(arrays, function (index, item) {
            if (item === key) {
                pushed.push(index);
            }
        });
        return pushed;
    };
    var removeValue = function (arrays, position) {
        var newParam = [];
        $.each(arrays, function (index, item) {
            if (index !== position) {
                newParam.push(item);
            }
        });
        return newParam;
    };
})(jQuery);