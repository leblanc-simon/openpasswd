function AccountType()
{
    this.top_container = $('#manage-account-types');
    this.container = $('#manage-account-types > div > div');

    this.tpl_admin_edit = $('#tpl-manage-account-types-edit');
    this.tpl_admin_list = $('#tpl-manage-account-types-list');
    this.tpl_admin_line = $('#tpl-manage-account-types-line');

    this.url_list = url_account_type_list;
    this.url_get = url_account_type_get;

    this.activeDnd = function() {
        that = this;

        function buildItem() {
            var sorted_ids = $('#enable-fields').sortable('toArray', {
                attribute: 'data-id'
            });

            that.container.find('form input[name=fields]').val(JSON.stringify(sorted_ids));
        }

        $( "#available-fields, #enable-fields" ).sortable({
            connectWith: ".fields-dnd",
            receive: function(event, ui) {
                if (ui.item.parents('#available-fields').length) {
                    buildItem();
                }
            },
            remove: function(event, ui) {
                if (ui.item.parents('#enable-fields').length) {
                    buildItem();
                }
            },
            change: function(event, ui) {
                if (ui.item.parents('#enable-fields').length) {
                    buildItem();
                }
            }
        }).disableSelection();
    };

    var available_fields_for_new = '';
    this.beforeInsertList = function() {
        that = this;
        $.ajax({
            url: url_field_list,
            type: 'GET',
            dataType: 'json',
            success: function(data, text_status, jq_xhr) {
                available_fields_for_new = '';
                for (var i in data) {
                    available_fields_for_new += '<li class="" data-id="' + data[i].id + '">' + data[i].name + '</li>';
                }
                that.data_add['available-fields'] = available_fields_for_new;
            }
        });
    }

    this.afterInsertAdd = this.activeDnd;
    this.afterInsertUpdate = this.activeDnd;

    this.data_add = {
        legend: 'Ajouter un nouveau type de compte',
        action: url_account_type_add,
        name: '',
        description: '',
        fields: '',

        'legend-field': 'Liste des champs',
        'enable-fields': '',
        'available-fields': available_fields_for_new
    };

    this.data_update = function(slug, data) {
        var enable_fields = '';
        var available_fields = '';
        var fields = new Array();

        for (var i in data.enable_fields) {
            enable_fields += '<li class="" data-id="' + data.enable_fields[i].id + '">' + data.enable_fields[i].name + '</li>';
            fields.push(data.enable_fields[i].id);
        }

        for (var j in data.available_fields) {
            available_fields += '<li class="" data-id="' + data.available_fields[j].id + '">' + data.available_fields[j].name + '</li>';
        }

        return {
            legend: 'Modifier le type de compte &quot;' + data.name + '&quot;',
            action: url_account_type_update.replace(/--slug--/, slug),
            name: data.name,
            description: data.description,
            fields: JSON.stringify(fields),

            'legend-field': 'Liste des champs',
            'enable-fields': enable_fields,
            'available-fields': available_fields
        }
    }
}


AccountType.prototype = simple_admin_prototype;

var account_type = new AccountType();