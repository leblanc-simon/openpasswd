/**
 * This file is part of the OpenPasswd package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    setTimeout(function() {
        $('#info-user .alert').alert('close');
    }, 3000)
}

function showError(message, title)
{
    showUserAlert('danger', message, title);
}

function loadPage(hash)
{
    hash = hash || window.location.hash;
    var slug = null;
    var is_new = false;

    if (hash.charAt(0) === '#') {
        hash = hash.replace(/^#/, '');
        if (hash.indexOf('/') != -1) {
            var split = hash.split('/');
            hash = split[0];
            slug = split[1];
        } else if (hash.indexOf('!new') != -1) {
            is_new = true;
            hash = hash.replace('!new', '');
        }
    }

    $('.page').addClass('hide');
    $('header .navbar-nav li').removeClass('active');

    if (hash === '') {
        // homepage

        $('header li a[href=#]').parent().addClass('active');
    } else {
        var page = $('#manage-' + hash);

        if (page.length === 1) {
            var object = page.attr('data-object');
            if (typeof window[object] !== undefined) {
                if (slug !== null) {
                    window[object].updateForm(slug);
                } else if (is_new === true) {
                    window[object].addForm();
                } else {
                    window[object].list();
                }
                $('header li a[href=#' + hash + ']').parent().addClass('active');
            }
        }
    }
}

function onSubmitForm(form, callbackAfterSuccess, callbackAfterError, callbackBefore)
{
    form.on('submit', function() {
        var form = $(this);
        var url = form.attr('action');
        var method = form.attr('method');

        if (!url || !method) {
            showError(language.error.submit_form);
            return false;
        }

        if (typeof callbackBefore != 'undefined') {
            if (callbackBefore(form) === false) {
                return false;
            }
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
                    if (callbackAfterSuccess) {
                        callbackAfterSuccess(data, text_status, jq_xhr);
                    }
                },
                201: function(data, text_status, jq_xhr) {
                    showSuccess(data.message);
                    if (callbackAfterSuccess) {
                        callbackAfterSuccess(data, text_status, jq_xhr);
                    }
                },
                400: function(jq_xhr, text_status, error_thrown) {
                    callOnSubmitError(jq_xhr, text_status, error_thrown, callbackAfterError);
                },
                401: function(jq_xhr, text_status, error_thrown) {
                    callOnSubmitError(jq_xhr, text_status, error_thrown, callbackAfterError);
                },
                403: function(jq_xhr, text_status, error_thrown) {
                    callOnSubmitError(jq_xhr, text_status, error_thrown, callbackAfterError);
                },
                500: function(jq_xhr, text_status, error_thrown) {
                    callOnSubmitError(jq_xhr, text_status, error_thrown, callbackAfterError);
                }
            },
            complete: unWait
        });

        return false;
    });
}

function callOnSubmitError(jq_xhr, text_status, error_thrown, callbackAfterError)
{
    try {
        var response = $.parseJSON(jq_xhr.responseText);
    } catch (e) {}

    if (response && response.description) {
        showError(response.description);
    } else {
        showError(jq_xhr.responseText);
    }
    if (callbackAfterError) {
        callbackAfterError(jq_xhr, text_status, error_thrown);
    }
}

jQuery.fn.selectText = function(){
    var doc = document
        , element = this[0]
        , range, selection
        ;
    if (doc.body.createTextRange) {
        range = document.body.createTextRange();
        range.moveToElementText(element);
        range.select();
    } else if (window.getSelection) {
        selection = window.getSelection();
        range = document.createRange();
        range.selectNodeContents(element);
        selection.removeAllRanges();
        selection.addRange(range);
    }
};