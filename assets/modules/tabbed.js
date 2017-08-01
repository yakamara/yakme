
$(document).on('rex:ready', function (event, container) {

    function yakmeTabbedModuleGrid($grid) {
        $grid = $grid.split('-');
        $tabs.find('li:not(.yakme-tabbed-locked)').css('display', 'none');
        for (var i = 0; i < $grid.length; i++) {
            $tabs.find('li:not(.yakme-tabbed-locked)').eq(i).css('display', 'block');
        }
    }

    var $module = container.find('.yakme-tabbed-module');
    if ($module.length > 0) {
        var $tabs = $module.find('#yakme-tabbed-tabs ul.nav-tabs');
        var $tabOrder = $module.find('.yakme-tabbed-order');
        var $tabSettings = $module.find('a#yakme-tabbed-settings');
        var $gridValue = $module.find('.yakme-tabbed-grid-value').val();

        if (typeof $tabOrder !== 'undefined' && $tabOrder.length > 0) {
            $tabs.sortable({
                axis : "x",
                items: '> li:not(.yakme-tabbed-locked)',
                update: function (e, ui) {
                    var csv = [];
                    $tabs.find('li:not(.yakme-tabbed-locked)').each(function(i){
                        csv.push($(this).attr('data-id'));
                    });
                   $tabOrder.val( csv.join() );
                }
            });
        }

        if(typeof $gridValue !== 'undefined' && $gridValue === '') {
            $tabSettings.click();
        } else {
            console.log('Click first child');
            $tabs.find('li:first-child a').click();
        }

        if (typeof $gridValue !== 'undefined') {
            yakmeTabbedModuleGrid($gridValue);
            $('.yakme-tabbed-grid input[type=radio]').change(function() {
                $tabs.find('li:first-child a').click();
                yakmeTabbedModuleGrid(this.value);
            });
        }
    }


    var $module = container.find('.yakme-module');
    if ($module.length > 0) {
        $module.find('[data-yakme-has-content]').each(function(i){
            var $block = $(this);
            var $hasContent = false;
            $block.find('input:text, textarea').each(function() {
                if ($(this).val() != '') {
                    $hasContent = true;
                }
            });

            if ($hasContent) {
                $block.attr('data-yakme-has-content', '1');
            } else {
                $block.attr('data-yakme-has-content', '0');
            }
        });
        $module.find('[data-yakme-has-content] input:text, [data-yakme-has-content] textarea').change(function(i){
            var $element = $(this);
            if ($element.val() != '') {
                $element.parents('[data-yakme-has-content]').attr('data-yakme-has-content', '1');
            } else {
                $element.closest('[data-yakme-has-content]').attr('data-yakme-has-content', '0');
            }
        });


        $module.find('select[data-yakme-selected]').each(function(i){
            var $selected = $(this).attr('data-yakme-selected');
            var $selectedIfEmpty = $(this).attr('data-yakme-selected-ifempty');
            if ($selected != '') {
                $(this).find('option[value="' + $selected + '"]').prop('selected', true);
            } else if ($selectedIfEmpty != '') {
                $(this).find('option[value="' + $selectedIfEmpty + '"]').prop('selected', true);
            }
        });
    }

});
