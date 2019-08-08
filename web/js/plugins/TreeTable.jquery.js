/**
 * TreeTable Jquery plugin
 *
 * How to use :
 *
 * The plugin is best to use with Nested Tree data structure
 *
 * Before use this, put in tr tag, the data attributes following : data-id=" id of data", data-parent=" id parent of data ",
 * data-level=" level of data".
 *
 *  and in the first td tag of the row, put the data attribute following : data-column="name", the chevron icon was top ut here.
 *
 * Licensed under MIT (https://github.com/iron-viper/jqueryTreeTable)
 */
(function($) {
    $.fn.TreeTable = function()
    {
        this.each(function() {
            var
                $table = $(this),
                rows = $table.find('tbody tr');

            rows.each(function (index, row) {
                var
                    $row = $(row),
                    level = $row.data('level'),
                    id = $row.data('id'),
                    $columnName = $row.find('td[data-column="name"]'),
                    children = $table.find('tr[data-parent="' + id + '"]');

                if (children.length) {
                    var expander = $columnName.prepend('' +
                        '<span id="treegrid-expander-'+ id +'" class="treegrid-expander fas fa-chevron-right" style="cursor:pointer;margin-right:5px"></span>' +
                        '');

                    $columnName.css('cursor', 'pointer');

                    children.hide();

                    $columnName.on('click', function (e) {
                        var $target = $('#treegrid-expander-'+ id);
                        if ($target.hasClass('fa-chevron-right')) {
                            $target
                                .removeClass('fa-chevron-right')
                                .addClass('fa-chevron-down');

                            children.show();
                        } else {
                            $target
                                .removeClass('fa-chevron-down')
                                .addClass('fa-chevron-right');

                            reverseHide($table, $row);
                        }
                    });
                }

                $columnName.prepend('' +
                    '<span class="treegrid-indent" style="display:inline-block;width:' + 30 * level + 'px"></span>' +
                    '');
            });

            // Reverse hide all elements
            reverseHide = function (table, element) {
                var
                    $element = $(element),
                    id = $element.data('id'),
                    children = table.find('tr[data-parent="' + id + '"]');

                if (children.length) {
                    children.each(function (i, e) {
                        reverseHide(table, e);
                    });

                    $element
                        .find('.fa-chevron-down')
                        .removeClass('fa-chevron-down')
                        .addClass('fa-chevron-right');

                    children.hide();
                }
            };
        });

        return this;
    };
})(jQuery);
