/**
 * This file is part of the OpenPasswd package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

function User()
{
    this.top_container = $('#manage-users');
    this.container = $('#manage-users > div > div');

    this.tpl_admin_edit = $('#tpl-manage-users-edit');
    this.tpl_admin_list = $('#tpl-manage-users-list');
    this.tpl_admin_line = $('#tpl-manage-users-line');

    this.url_list = url_user_list;
    this.url_get = url_user_get;

    this.activeDnd = function() {
        that = this;

        function buildItem() {
            var sorted_ids = $('#enable-groups').sortable('toArray', {
                attribute: 'data-id'
            });

            that.container.find('form input[name=groups]').val(JSON.stringify(sorted_ids));
        }

        function addRemoveIcon(ui)
        {
            ui.item.append('<button type="button" class="remove-item close">&times;</button>');
        }

        function removeRemoveIcon(ui)
        {
            ui.item.find('.remove-item').remove();
        }

        $( "#available-groups, #enable-groups" ).sortable({
            connectWith: ".groups-dnd",
            receive: function(event, ui) {
                if (ui.item.parents('#available-groups').length) {
                    removeRemoveIcon(ui);
                    buildItem();
                } else {
                    addRemoveIcon(ui);
                }
            },
            remove: function(event, ui) {
                if (ui.item.parents('#enable-groups').length) {
                    buildItem();
                }
            },
            update: function(event, ui) {
                if (ui.item.parents('#enable-groups').length) {
                    buildItem();
                }
            }
        }).disableSelection();
    };

    var available_groups_for_new = '';
    this.beforeInsertList = function() {
        that = this;
        $.ajax({
            url: url_group_list,
            type: 'GET',
            dataType: 'json',
            success: function(data, text_status, jq_xhr) {
                available_groups_for_new = '';
                for (var i in data) {
                    available_groups_for_new += '<li class="" data-id="' + data[i].id + '">' + data[i].name + '</li>';
                }
                that.data_add['available-groups'] = available_groups_for_new;
            }
        });
    }

    this.afterInsertAdd = this.activeDnd;
    this.afterInsertUpdate = this.activeDnd;

    this.data_add = {
        legend: language.user.add,
        action: url_user_add,
        name: '',
        username: '',
        groups: '',

        'legend-group': language.user.groups,
        'enable-groups': '',
        'available-groups': available_groups_for_new
    };

    this.data_update = function(slug, data) {
        var enable_groups = '';
        var available_groups = '';
        var groups = new Array();

        for (var i in data.enable_groups) {
            enable_groups += '<li class="" data-id="' + data.enable_groups[i].id + '">' + data.enable_groups[i].name + ' <button type="button" class="remove-item close">&times;</button></li>';
            groups.push(data.enable_groups[i].id);
        }

        for (var j in data.available_groups) {
            available_groups += '<li class="" data-id="' + data.available_groups[j].id + '">' + data.available_groups[j].name + '</li>';
        }

        return {
            legend: language.user.edit.replace('%name%', data.name),
            action: url_user_update.replace(/--slug--/, slug),
            name: data.name,
            username: data.username,
            groups: JSON.stringify(groups),

            'legend-group': language.user.groups,
            'enable-groups': enable_groups,
            'available-groups': available_groups
        }
    }
}


User.prototype = simple_admin_prototype;

var user = new User();