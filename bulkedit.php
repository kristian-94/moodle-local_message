<?php
// This file is part of Moodle Course Rollover Plugin
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package     local_message
 * @author      Kristian
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_message\form\bulkedit;
use local_message\manager;

require_once(__DIR__ . '/../../config.php');

require_login();
$context = context_system::instance();
require_capability('local/message:managemessages', $context);

$PAGE->set_url(new moodle_url('/local/message/bulkedit.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('bulk_edit', 'local_message'));
$PAGE->set_heading(get_string('bulk_edit_messages', 'local_message'));

$messageid = optional_param('messageid', null, PARAM_INT);

// We want to display our form.
$mform = new bulkedit();
$manager = new manager();

if ($mform->is_cancelled()) {
    // Go back to manage.php page
    redirect($CFG->wwwroot . '/local/message/manage.php', get_string('cancelled_form', 'local_message'));
} else if ($fromform = $mform->get_data()) {
    $messages = $fromform->messages;
    $messageids = [];
    foreach ($messages as $key => $enabled) {
        if ($enabled == true) {
            $messageids[] = substr($key, 9);
        }
    }

    if ($messageids) {
        if ($fromform->deleteall == true) {
            $manager->delete_messages($messageids);
        } else {
            $manager->update_messages($messageids, $fromform->messagetype);
        }
    }

    redirect($CFG->wwwroot . '/local/message/manage.php', get_string('bulk_edit_successful', 'local_message'));
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
