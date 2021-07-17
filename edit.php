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

use local_message\form\edit;
use local_message\manager;

require_once(__DIR__ . '/../../config.php');

require_login();
$context = context_system::instance();
require_capability('local/message:managemessages', $context);

$PAGE->set_url(new moodle_url('/local/message/edit.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Edit');

$messageid = optional_param('messageid', null, PARAM_INT);

// We want to display our form.
$mform = new edit();

if ($mform->is_cancelled()) {
    // Go back to manage.php page
    redirect($CFG->wwwroot . '/local/message/manage.php', get_string('cancelled_form', 'local_message'));

} else if ($fromform = $mform->get_data()) {
    $manager = new manager();

    if ($fromform->id) {
        // We are updating an existing message.
        $manager->update_message($fromform->id, $fromform->messagetext, $fromform->messagetype);
        redirect($CFG->wwwroot . '/local/message/manage.php', get_string('updated_form', 'local_message') . $fromform->messagetext);
    }

    $manager->create_message($fromform->messagetext, $fromform->messagetype);

    // Go back to manage.php page
    redirect($CFG->wwwroot . '/local/message/manage.php', get_string('created_form', 'local_message') . $fromform->messagetext);
}

if ($messageid) {
    // Add extra data to the form.
    global $DB;
    $manager = new manager();
    $message = $manager->get_message($messageid);
    if (!$message) {
        throw new invalid_parameter_exception('Message not found');
    }
    $mform->set_data($message);
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
