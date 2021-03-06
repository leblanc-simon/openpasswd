/**
* This file is part of the OpenPasswd package.
*
* (c) Simon Leblanc <contact@leblanc-simon.eu>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

function Account()
{
    this.top_container = $('#manage-accounts');
    this.container = $('#manage-accounts > div > div');

    this.tpl_admin_edit = $('#tpl-manage-accounts-edit');
    this.tpl_admin_list = $('#tpl-manage-accounts-list');
    this.tpl_admin_line = $('#tpl-manage-accounts-line');
    this.tpl_show_account = $('#tpl-manage-accounts-show');
    this.tpl_show_line = $('#tpl-manage-accounts-show-line');
    this.tpl_group_list = $('#tpl-manage-groups-list');

    this.url_list = url_account_list;
    this.url_get = url_account_get;

    this.data_pre_add = {
        action: url_account_add
    };

    this.data_add = {
        legend: language.account.add,
        action: url_account_add,
        name: '',
        description: '',
        fields: '',
        'account-types': ''
    };

    this.list = function() {
        this.wait();
        this.reset();
        var that = this;

        $.ajax({
            url: this.url_list,
            type: 'GET',
            dataType: 'json',
            success: function(data, text_status, jq_xhr) {
                that.container.loadTemplate(
                    that.tpl_admin_list,
                    {},
                    {
                        success: function() {
                            data.unshift({
                                slug: '',
                                name: ''
                            });
                            that.container.find('select[name=search]').loadTemplate(
                                that.tpl_admin_line,
                                data,
                                {
                                    success: that.top_container.removeClass('hide'),
                                    error: function() { alert('error'); },
                                    complete: function() {
                                        that.unWait();
                                        that.container.find('select[name=search]').chosen();
                                        that.container.find('select[name=search]').trigger('chosen:activate');
                                    }
                                }
                            );
                        },
                        error: function() { alert('error'); },
                        complete: that.unWait(),
                        afterInsert: function() {

                        }
                    }
                );
            },
            error: function(jq_xhr, text_status, error_thrown) {
                showError(jq_xhr.responseText);
                that.unWait();
            }
        });
    };

    this.prepareForm = function() {
        this.wait();
        this.reset();
        var that = this;

        $.ajax({
            url: url_account_type_list,
            type: 'GET',
            dataType: 'json',
            success: function(data, text_status, jq_xhr) {
                var account_types = '<option></option>';
                for (var i = 0, max = data.length; i < max; i++) {
                    account_types += '<option value="' + data[i].slug + '">' + data[i].name + '</option>';
                }
                that.data_pre_add['account-types'] = account_types;

                that.container.loadTemplate(
                    that.tpl_admin_edit,
                    that.data_pre_add,
                    {
                        success: function() {
                            $.ajax({
                                url: url_group_list,
                                type: 'GET',
                                dataType: 'json',
                                success: function(data, text_status, jq_xhr) {
                                    var tmpDiv = $('<div></div>');
                                    var div_content = '';

                                    $.each(data, function(i, item) {
                                        var template = $('#tpl-manage-groups-checkbox');

                                        var datas_checkbox = {
                                            group_id: item.id,
                                            checkbox_id: 'group_' + item.id,
                                            checkbox_group_id: 'group[' + item.id + ']',
                                            name: item.name,
                                            checked: item.id == '1' ? 'checked' : '',
                                            disabled: item.id == '1' ? 'disabled' : '',
                                            required: item.id == '1' ? 'required' : ''
                                        }

                                        tmpDiv.loadTemplate(
                                            template,
                                            datas_checkbox
                                        );

                                        div_content += tmpDiv.html();
                                    });
                                    that.container.find('#account-main-form .form-group-checkbox').html(div_content);

                                    that.top_container.removeClass('hide');
                                    $('#account-select-type select').on('change', function() { that.addForm(this.value) });
                                }
                            });
                        },
                        error: function() { alert('error'); },
                        complete: that.unWait()
                    }
                );
            }
        })
    };

    this.resetForm = function() {
        $('#account-main-form .main-form').html();
        this.container.find('form').unbind('submit');
    };

    this.addForm = function(type) {
        this.resetForm();
        this.wait();
        var that = this;

        $.ajax({
            url: url_account_type_get.replace(/--slug--/, type),
            type: 'GET',
            dataType: 'json',
            success: function(data, text_status, jq_xhr) {
                var tmpDiv = $('<div></div>');
                var form_content = '';

                $.each(data.enable_fields, function(i, item) {
                    var template = $('#' + form_types.templates[item.type]);

                    var datas_form = {
                        type: item.type,
                        name: item.name,
                        input_id: 'field[' + item.id + ']',
                        description: item.description,
                        required: item.required ? 'required' : '',
                        available_values: form_types.available_values[item.type]
                    }

                    tmpDiv.loadTemplate(
                        template,
                        datas_form
                    );

                    form_content += tmpDiv.html();
                });

                that.container.find('#account-main-form .main-form').html(form_content);
                that.container.find('#account-main-form').removeClass('hide');
                that.container.find('#account-main-form').parent().find('button.hide').removeClass('hide');

                onSubmitForm(
                    that.container.find('form'),
                    function(data, text_status, jq_xhr) {
                        that.list();
                    },
                    null,
                    function(data, text_status, jq_xhr) {
                        var check = false;

                        $('input[type=checkbox]:checked').each(function() {
                            var checkbox_id = parseInt($(this).attr('data-checkbox-id'));

                            if ($.inArray(checkbox_id, enable_groups) !== -1) {
                                check = true;
                                return false
                            }
                        });

                        if (check === true) {
                            return true;
                        }

                        showError(language.error.nb_min_group);
                        return false;
                    }
                );

                that.unWait();
            },
            error: function(jq_xhr, text_status, error_thrown) {
                showError(jq_xhr.responseText);
                that.unWait();
            }
        });
    };



    this.show = function(slug) {
        if ('' === slug) {
            return;
        }

        this.resetForm();
        this.wait();
        var that = this;

        $.ajax({
            url: url_account_show.replace(/--slug--/, slug),
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#account-details').loadTemplate(
                    that.tpl_show_account,
                    data.account,
                    {
                        success: function() {
                            $('#account-details .fields').loadTemplate(
                                that.tpl_show_line,
                                data.fields
                            );

                            $('#account-details .fields div[data-type="url"]').each(function(i, item) {
                                var value = item.innerHTML;
                                if (value.match(/:\/\//) === null) {
                                    value = 'http://' + value;
                                }

                                value = '<a href="'+ value + '">' + value + '</a>';
                                item.innerHTML = value;
                            });
                        },
                        complete: that.unWait()
                    }
                );
            }
        });
    };
}

Account.prototype = simple_admin_prototype;

var account = new Account();

$(document).ready(function(){
    $(document).on('click', '#manage-accounts .create', function() {
        account.prepareForm();
        return false;
    });

    $(document).on('change', 'select[name="search"]', function() {
        var slug = $(this).val();
        account.show(slug);
        return false;
    });

    $(document).on('click', '.row-show', function() {
        $(this).find('.col-md-8').selectText();
    });

});