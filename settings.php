<?php

if ($hassiteconfig) { // needs this condition or there is error on login page

    $ADMIN->add('localplugins', new admin_category('local_message_category', get_string('pluginname', 'local_message')));

    $settings = new admin_settingpage('local_message', get_string('pluginname', 'local_message'));
    $ADMIN->add('local_message_category', $settings);

    $settings->add(new admin_setting_configcheckbox('local_message/enabled',
        get_string('setting_enable', 'local_message'), get_string('setting_enable_desc', 'local_message'), '1'));

    $ADMIN->add('local_message_category', new admin_externalpage('local_message_manage', get_string('manage', 'local_message'),
        $CFG->wwwroot . '/local/message/manage.php'));
}
