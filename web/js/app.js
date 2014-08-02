/**
 * This file is part of the OpenPasswd package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$(document).ready(function(){
    // Go to the the page on load
    loadPage();

    var no_action_for_hash = false;

    // Header
    $('header a').click(function() {
        no_action_for_hash = true;
        document.location.hash = $(this).attr('href').replace(/^#/, '');
        loadPage($(this).attr('href'));
        return false;
    });

    // Simple administrative operation
    $('[id^=manage-]').each(function() {
        var object = $(this).attr('data-object');

        if (typeof window[object] === undefined) {
            return;
        }

        var page = $(this).attr('id').replace(/manage-/, '');

        $(document).on('click', '#' + $(this).attr('id') + ' tbody a', function() {
            no_action_for_hash = true;
            document.location.hash = page + '/' + $(this).attr('data-slug');
            window[object].updateForm($(this).attr('data-slug'));
            return false;
        });

        $(document).on('click', '#' + $(this).attr('id') + ' .new', function() {
            no_action_for_hash = true;
            document.location.hash = page + '!new';
            window[object].addForm();
            return false;
        });

        $(document).on('click', '#' + $(this).attr('id') + ' button[type=reset]', function() {
            no_action_for_hash = true;
            document.location.hash = page;
            loadPage(page);
            return false;
        });
    });

    // Navigation
    $(window).on('hashchange', function() {
        if (no_action_for_hash === true) {
            no_action_for_hash = false;
            return;
        }

        loadPage();
    });

    // Double list
    $(document).on('click', '.remove-item', function(){
        var item = $(this).parent();
        var source = '#' + item.parent().attr('id');
        var destination = source.replace('enable', 'available');
        item.find('.remove-item').remove()
        item.detach().prependTo(destination);
        $(source).sortable('refresh');
        $(destination).sortable('refresh');

        var sorted_ids = $(source).sortable('toArray', { attribute: 'data-id' });
        $(source).parents('form').find('input[name=' + source.replace('#', '').replace('enable-', '') + ']').val(JSON.stringify(sorted_ids));
    });
});