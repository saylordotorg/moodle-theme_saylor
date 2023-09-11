<?php

require_once(__DIR__ . '/../../../config.php');

$courseid = required_param('courseid', PARAM_INT);

require_login();

$context = context_course::instance($courseid);
require_capability('moodle/course:enrolreview', $context);

$enrol = enrol_get_plugin('self');
if ($enrol) {
    $instances = enrol_get_instances($courseid, true);
    foreach ($instances as $instance) {
        if ($instance->enrol === 'self') {
            $enrol->enrol_self($instance);
            break;
        }
    }
}

redirect(new moodle_url('/course/view.php', ['id' => $courseid]));