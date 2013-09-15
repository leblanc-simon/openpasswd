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
});