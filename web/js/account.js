function Account()
{
    this.top_container = $('#manage-accounts');
    this.container = $('#manage-accounts > div > div');

    this.tpl_admin_edit = $('#tpl-manage-accounts-edit');
    this.tpl_admin_list = $('#tpl-manage-accounts-list');
    this.tpl_admin_line = $('#tpl-manage-accounts-line');

    this.url_list = url_account_list;
    this.url_get = url_account_get;

    this.data_pre_add = {
        action: url_account_add
    };

    this.data_add = {
        legend: 'Ajouter un nouveau compte',
        action: url_account_add,
        name: '',
        description: '',
        fields: '',

        'account-types': 'Liste des champs'
    };

    this.list = function() {
        this.wait();
        this.reset();
        that = this;

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
                            that.container.find('select[name=search]').loadTemplate(
                                that.tpl_admin_line,
                                data,
                                {
                                    success: that.top_container.removeClass('hide'),
                                    error: function() { alert('error'); },
                                    complete: function() {
                                        that.unWait();
                                        that.container.find('select[name=search]').chosen();
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
        that = this;

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
                            that.top_container.removeClass('hide');
                            $('#account-select-type select').on('change', function() { that.addForm(this.value) });
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
    };

    this.addForm = function(type) {
        this.resetForm();
        this.wait();
        that = this;

        $.ajax({
            url: url_account_type_get.replace(/--slug--/, type),
            type: 'GET',
            dataType: 'json',
            success: function(data, text_status, jq_xhr) {
                console.log(data);

                var tmpDiv = $('<div></div>');
                var form_content = '';

                $.each(data.enable_fields, function(i, item) {
                    var template = $('#tpl-manage-accounts-input');

                    if (item.type === 'textarea') {
                        template = $('#tpl-manage-accounts-textarea');
                    }

                    var datas_form = {
                        type: item.type,
                        name: item.name,
                        input_id: 'field[' + item.id + ']',
                        description: item.description,
                        required: item.required ? 'required' : ''
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

                onSubmitForm(that.container.find('form'), function(data, text_status, jq_xhr) {
                    that.list();
                });

                that.unWait();
            },
            error: function(jq_xhr, text_status, error_thrown) {
                showError(jq_xhr.responseText);
                that.unWait();
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
});