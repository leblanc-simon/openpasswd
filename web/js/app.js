$(document).ready(function(){
    // Go to the the page on load
    loadPage();

    // Header
    $('header a').click(function() {
        loadPage($(this).attr('href'));
        return true;
    });

    // Simple administrative operation
    $('[id^=manage-]').each(function() {
        var object = $(this).attr('data-object');
        if (typeof window[object] === undefined) {
            return;
        }

        $(document).on('click', '#' + $(this).attr('id') + ' tbody a', function() {
            window[object].updateForm($(this).attr('data-slug'));
            return false;
        });

        $(document).on('click', '#' + $(this).attr('id') + ' .new', function() {
            window[object].addForm();
            return false;
        });

        $(document).on('click', '#' + $(this).attr('id') + ' button[type=reset]', function() {
            var page = $(this).parents('[id^=manage-]').attr('id').replace(/manage-/, '');
            loadPage(page);
            return false;
        });
    });
});