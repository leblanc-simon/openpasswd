/**
 * This file is part of the OpenPasswd package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

function Field()
{
    this.top_container = $('#manage-fields');
    this.container = $('#manage-fields > div > div');

    this.tpl_admin_edit = $('#tpl-manage-fields-edit');
    this.tpl_admin_list = $('#tpl-manage-fields-list');
    this.tpl_admin_line = $('#tpl-manage-fields-line');

    this.url_list = url_field_list;
    this.url_get = url_field_get;

    this.data_add = {
        legend: language.field.add,
        action: url_field_add,
        name: '',
        description: '',
        crypt: '',
        required: '',
        type_text: 'checked',
        type_textarea: '',
        type_date: '',
        type_numeric: '',
        type_email: '',
        type_url: ''
    };

    this.data_update = function(slug, data) {
        return {
            legend: language.field.edit.replace('%name%', data.name),
            action: url_field_update.replace(/--slug--/, slug),
            name: data.name,
            description: data.description,
            crypt: data.crypt == 1 ? 'checked' : '',
            required: data.required == 1 ? 'checked' : '',
            type_text: data.type === 'text' ? 'checked' : '',
            type_textarea: data.type === 'textarea' ? 'checked' : '',
            type_date: data.type === 'date' ? 'checked' : '',
            type_numeric: data.type === 'numeric' ? 'checked' : '',
            type_email: data.type === 'email' ? 'checked' : '',
            type_url: data.type === 'url' ? 'checked' : ''
        }
    }
}


Field.prototype = simple_admin_prototype;

var field = new Field();