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
        legend: 'Ajouter un nouveau groupe',
        action: url_group_add,
        name: '',
        description: ''
    };

    this.data_update = function(slug, data) {
        return {
            legend: 'Modifier le groupe &quot;' + data.name + '&quot;',
            action: url_group_update.replace(/--slug--/, slug),
            name: data.name,
            description: data.description
        }
    }
}


Group.prototype = simple_admin_prototype;

var group = new Group();