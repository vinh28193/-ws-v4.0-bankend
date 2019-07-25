var wsAddress = (function ($) {
    var methods = {
        zipAjaxProcessResults: function (resp, params) {
            params.page = params.page || 1;
            var results = resp.data.map(item => {
                return {
                    id: item.zip_code,
                    text: item.zip_code,
                    label: item.label,
                    province: item.province_id,
                    district: item.district_id,
                }
            });

            if (results.length === 1 && results[0].id === '0') {
                results = [];
            }
            if (params.term) {
                results.push({
                    id: params.term,
                    text: params.term,
                    label: params.term,
                    province: false,
                    district: false,
                })
            }
            return {
                results: results,
                pagination: {
                    more: (params.page * 30) < resp.count
                }
            };
        },
        formatZipAjaxResults: function (resp) {
            return (resp.label) || resp.text;
        },
        formatZipAjaxSelection: function (resp) {
            if (resp.loading) {
                return resp.text;
            }
            return resp.text;
        },
        select2Params: function (params) {
            return {q: params.term, p: params.page};
        },
        select2ChangeSelect: function ($select2Element, selectValue,validate = false) {
            selectValue = String(selectValue);
            // $select2Element = $($select2Element);
            var activeOption = $select2Element.find('option[value="' + selectValue + '"]');
            console.log('option[value="' + selectValue + '"] length = ' + activeOption.length + 'selected' + activeOption.is(':selected'));
            if (activeOption.length && !activeOption.is(':selected')) {
                $select2Element.val(selectValue);
                $select2Element.trigger('change');
                $select2Element.trigger('select2:select');
                $select2Element.trigger('done');
            }

        },
        select2RemoveSelect: function ($select2Element) {
            $select2Element.trigger('select2:unselect');
        }
    };
    return methods;
})(jQuery);
