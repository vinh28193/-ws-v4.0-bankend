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
        portal: undefined
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
                var $queryParams = {};
                $.each(yii.getQueryParams(settings.absoluteUrl), function (name, value) {
                    $queryParams[name] = value;
                });
                $queryParams.type = [];
                $queryParams.type = settings.portal;

                var pos = settings.absoluteUrl.indexOf('?');
                var url = pos < 0 ? settings.absoluteUrl : settings.absoluteUrl.substring(0, pos);
                var hashPos = settings.absoluteUrl.indexOf('#');
                if (pos >= 0 && hashPos >= 0) {
                    url += settings.absoluteUrl.substring(hashPos);
                }
                $search.data('wsSearch', {
                    settings: settings,
                    ajaxUrl: url,
                    queryParams: $queryParams
                });

                initEventHandler($search, 'filter', 'change', 'input.form-check-input', function (event) {
                    methods.applyFilter.call($search, $(this));
                    return false;
                });

                // methods.search.apply($search);
                return false;
            });
        },
        search: function () {
            var $search = $(this);
            var data = $search.data('wsSearch');
            var queryParams = data.queryParams;
            var ajaxUrl = data.ajaxUrl;
            if (ajaxUrl === undefined) {
                return;
            }
            var deferredArrays = deferredArray();
            $.when.apply(this, deferredArrays).always(function () {
                $.ajax({
                    url: ajaxUrl,
                    type: 'GET',
                    data: queryParams,
                    dataType: 'json',
                    success: function (response) {
                        $search.find('div.' + data.settings.portal + '-search').html(response);
                    },
                    error: function () {
                    }
                });
            });
        },

        applyFilter: function ($element) {
            var $search = $(this);
            var data = $search.data('wsSearch');
            var settings = data.settings;
            var queryParams = data.queryParams;
            var filters = queryParams.filter;

            var value = $element.val();
            if (settings.portal === 'ebay') {
                filters = ebayFilterParams(filters, $element.data('for'), $element.data('value'));
            } else {
                filters = value;
            }

            queryParams.filter = filters;
            var ajaxUrl = data.ajaxUrl;
            if (ajaxUrl === undefined) {
                return;
            }


            $search.find('form.search-filter-form').remove();
            var $form = $('<form/>', {
                action: ajaxUrl,
                method: 'get',
                'class': 'search-filter-form',
                style: 'display:none',
                'data-pjax': ''
            }).appendTo($search);
            $.each(queryParams, function (name, value) {
                $form.append($('<input/>').attr({type: 'hidden', name: name, value: value}));
            });
            $form.submit();
        },
        destroy: function () {
            this.off('.wsSearch');
            this.removeData('wsSearch');
            return this;
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
    // filters = Color:Red,Blue;Display:FullHd,Asus
    var ebayFilterParams = function (params, key, value) {
        var newParam = [];
        var pushed = false;
        if (params === undefined) {
            return key + ':' + value;
        }
        params = params.split(';');
        $.each(params, function (index, parameter) {
            var paramSplit = parameter.split(':');
            if (key === paramSplit[0]) {
                paramSplit[1] = makeParam(paramSplit[1], value, ',');
                pushed = true;
            }
            if (paramSplit[1] !== '' && paramSplit[1] !== null) {
                newParam.push(paramSplit.join(':'));
            }
        });
        if (!pushed) {
            newParam.push(key + ':' + value);
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