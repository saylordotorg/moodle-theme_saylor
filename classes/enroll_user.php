<?php

require_once(__DIR__ . '/../../../config.php');

$courseid = required_param('courseid', PARAM_INT);

require_login();

$context = context_course::instance($courseid);

$enrol = enrol_get_plugin('self');
if ($enrol) {
    $instances = enrol_get_instances($courseid, true);
    foreach ($instances as $instance) {
        if ($instance->enrol === 'self') {
            $enrol->enrol_self($instance, $USER->id);
            break;
        }
    }
}

redirect(new moodle_url('/course/view.php', ['id' => $courseid]));