/**
 * This file is part of the OpenPasswd package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

function Group()
{
    this.top_container = $('#manage-groups');
    this.container = $('#manage-groups > div > div');

    this.tpl_admin_edit = $('#tpl-manage-groups-edit');
    this.tpl_admin_list = $('#tpl-manage-groups-list');
    this.tpl_admin_line = $('#tpl-manage-groups-line');

    this.url_list = url_group_list;
    this.url_get = url_group_get;

    this.data_add = {
        legend: language.group.add,
        action: url_group_add,
        name: '',
        description: ''
    };

    this.data_update = function(slug, data) {
        return {
            legend: language.group.edit.replace('%name%', data.name),
            action: url_group_update.replace(/--slug--/, slug),
            name: data.name,
            description: data.description
        }
    }
}


Group.prototype = simple_admin_prototype;

var group = new Group();