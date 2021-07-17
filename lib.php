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
use local_message\manager;

function local_message_before_footer() {
    global $USER;

    if (!get_config('local_message', 'enabled')) {
        return;
    }

    $manager = new manager();
    $messages = $manager->get_messages($USER->id);

    foreach ($messages as $message) {
        $type = \core\output\notification::NOTIFY_INFO;
        if ($message->messagetype === '0') {
            $type = \core\output\notification::NOTIFY_WARNING;
        }
        if ($message->messagetype === '1') {
            $type = \core\output\notification::NOTIFY_SUCCESS;
        }
        if ($message->messagetype === '2') {
            $type = \core\output\notification::NOTIFY_ERROR;
        }
        \core\notification::add($message->messagetext, $type);

        $manager->mark_message_read($message->id, $USER->id);
    }
}
