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

namespace local_message\form;
use local_message\manager;
use moodleform;

require_once("$CFG->libdir/formslib.php");

class bulkedit extends moodleform {
    public function definition() {
        $mform = $this->_form; // Don't forget the underscore!

        // Display the list of messages with a checkbox.
        $manager = new manager();
        $messages = $manager->get_all_messages();

        $messagegroup = [];
        foreach ($messages as $message) {
            $messagegroup[] = $mform->createElement('advcheckbox', 'messageid' . $message->id, $message->messagetext);
        }
        $mform->addGroup($messagegroup, 'messages', get_string('choose_messages', 'local_message'), '<br>');

        $mform->addElement('static', 'todo', get_string('whattodo', 'local_message'));

        $choices = array();
        $choices['0'] = \core\output\notification::NOTIFY_WARNING;
        $choices['1'] = \core\output\notification::NOTIFY_SUCCESS;
        $choices['2'] = \core\output\notification::NOTIFY_ERROR;
        $choices['3'] = \core\output\notification::NOTIFY_INFO;
        $mform->addElement('select', 'messagetype', get_string('message_type', 'local_message'), $choices);
        $mform->setDefault('messagetype', '3');

        $mform->addElement('advcheckbox', 'deleteall', get_string('delete_all_selected', 'local_message'), get_string('yes'));

        $this->add_action_buttons();
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}
