<?php

// This file is part of the Checklist plugin for Moodle - http://moodle.org/
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
 * This page prints a particular instance of checklist
 *
 * @author  David Smith <moodle@davosmith.co.uk>
 * @package mod/checklist
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

global $DB;

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$checklistid  = optional_param('checklist', 0, PARAM_INT);  // checklist instance ID

$url = new moodle_url('/mod/checklist/view.php');
if ($id) {
    if (! $cm = get_coursemodule_from_id('checklist', $id)) {
        error('Course Module ID was incorrect');
    }

    if (! $course = $DB->get_record('course', array('id' => $cm->course) )) {
        error('Course is misconfigured');
    }

    if (! $checklist = $DB->get_record('checklist', array('id' => $cm->instance) )) {
        error('Course module is incorrect');
    }
    $url->param('id', $id);

} else if ($checklistid) {
    if (! $checklist = $DB->get_record('checklist', array('id' => $checklistid) )) {
        error('Course module is incorrect');
    }
    if (! $course = $DB->get_record('course', array('id' => $checklist->course) )) {
        error('Course is misconfigured');
    }
    if (! $cm = get_coursemodule_from_instance('checklist', $checklist->id, $course->id)) {
        error('Course Module ID was incorrect');
    }
    $url->param('checklist', $checklistid);

} else {
    error('You must specify a course_module ID or an instance ID');
}

$PAGE->set_url($url);
require_login($course, true, $cm);

if ($CFG->version < 2011120100) {
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
} else {
    $context = context_module::instance($cm->id);
}
$userid = 0;
if (has_capability('mod/checklist:updateown', $context)) {
    $userid = $USER->id;
}

$chk = new checklist_class($cm->id, $userid, $checklist, $cm, $course);

$chk->view();
