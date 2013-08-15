function wait()
{
    $('#loading').removeClass('hide');
}

function unWait()
{
    $('#loading').addClass('hide');
}

function showUserAlert(type, message, title)
{
    html = '<div class="alert alert-' + type + ' alert-dismissable"></div>';

    if (typeof title !== undefined && title) {
        title = $('<strong></strong>').text(title + ' ');
    } else {
        title = '';
    }

    html = $(html)
            .text(message)
            .prepend(title)
            .append('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');

    $('#info-user').html($(html));
    $('#info-user .alert').alert();
}

function showInfo(message, title)
{
    showUserAlert('info', message, title);
}

function showSuccess(message, title)
{
    showUserAlert('success', message, title);
}

function showError(message, title)
{
    showUserAlert('danger', message, title);
}

function loadPage(hash)
{
    hash = hash || window.location.hash;

    if (hash.charAt(0) === '#') {
        hash = hash.replace(/^#/, '');
    }

    $('.page').addClass('hide');

    if (hash === '') {
        // homepage
    } else {
        var page = $('#manage-' + hash);

        if (page.length === 1) {
            var object = page.attr('data-object');
            if (typeof window[object] !== undefined) {
                window[object].list();
            }
        }
    }
}

function onSubmitForm(form)
{
    form.on('submit', function() {
        var form = $(this);
        var url = form.attr('action');
        var method = form.attr('method');

        if (!url || !method) {
            showError('Une erreur s\'est produite vous empêchant de soumettre le formulaire');
            return false;
        }

        wait();

        $.ajax({
            url: url,
            type: method,
            dataType: 'json',
            data: form.serialize(),
            statusCode: {
                200: function(data, text_status, jq_xhr) {
                    showSuccess(data.message);
                },
                201: function(data, text_status, jq_xhr) {
                    showSuccess(data.message);
                },
                400: function(jq_xhr, text_status, error_thrown) {
                    var response = $.parseJSON(jq_xhr.responseText);
                    if (response && response.description) {
                        showError(response.description);
                    } else {
                        showError(jq_xhr.responseText);
                    }
                },
                401: function(jq_xhr, text_status, error_thrown) {
                    var response = $.parseJSON(jq_xhr.responseText);
                    if (response && response.description) {
                        showError(response.description);
                    } else {
                        showError(jq_xhr.responseText);
                    }
                },
                403: function(jq_xhr, text_status, error_thrown) {
                    var response = $.parseJSON(jq_xhr.responseText);
                    if (response && response.description) {
                        showError(response.description);
                    } else {
                        showError(jq_xhr.responseText);
                    }
                },
                500: function(jq_xhr, text_status, error_thrown) {
                    var response = $.parseJSON(jq_xhr.responseText);
                    if (response && response.description) {
                        showError(response.description);
                    } else {
                        showError(jq_xhr.responseText);
                    }
                }
            },
            complete: unWait
        });

        return false;
    });
}