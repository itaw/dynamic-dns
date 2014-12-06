$(document).ready(function () {

    $('.cb-accessor-sel-domain').change(function () {
        var domains = [];

        $.each($('#table-domains').children().children(), function (i, tr) {
            $.each($(tr).children('.td-cb'), function (j, td) {
                var cb = $(td).children()[0];

                if (cb.checked) {
                    domains.push($(cb).data('id'));
                }
            });
        });

        $('#input-domains').val(domains.join('|'));
    });

});