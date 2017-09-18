<?php
// This file is part of Moodle - http://moodle.org/
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
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package   theme_bootstrapbase
 * @copyright 2012
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Check the file is being called internally from within Moodle.
defined('MOODLE_INTERNAL') || die();

// course renderer
require_once($CFG->dirroot . "/theme/boost/classes/output/core_renderer.php");
require_once($CFG->dirroot . "/course/renderer.php");
require_once($CFG->dirroot . "/completion/completion_completion.php");
require_once($CFG->dirroot . "/blocks/course_overview/renderer.php");

class theme_saylor_core_renderer extends theme_boost\output\core_renderer
{
    /*
     * This renders the bootstrap top menu.
     *
     * This renderer is needed to enable the Bootstrap style navigation.
     */
    protected function render_custom_menu(custom_menu $menu) {
        global $CFG;

        $langs = get_string_manager()->get_list_of_translations();
        $haslangmenu = $this->lang_menu() != '';

        if (!$menu->has_children() && !$haslangmenu) {
            return '';
        }

        if ($haslangmenu) {
            $strlang = get_string('language');
            $currentlang = current_language();
            if (isset($langs[$currentlang])) {
                $currentlang = $langs[$currentlang];
            } else {
                $currentlang = $strlang;
            }
            $this->language = $menu->add($currentlang, new moodle_url('#'), $strlang, 10000);
            foreach ($langs as $langtype => $langname) {
                $this->language->add($langname, new moodle_url($this->page->url, array('lang' => $langtype)), $langname);
            }
        }

        $content = '';
        foreach ($menu->get_children() as $item) {
            $context = $item->export_for_template($this);
            $content .= $this->render_from_template('core/custom_menu_item', $context);
        }

        return $content;
    }

    /*
    * This code shows an enroll button in main course view to logged in user or Login/sign up link when suer is not logged in.
    */
    public function saylor_custom_enroll_button() {
        global $COURSE, $PAGE;

        // show nothing if user is already on enroll page.
        if ($PAGE->pagetype == 'enrol-index') {
                return "";
        }

        $output = html_writer::start_tag('div', array('id' => 'enroll-button-container', 'class' => 'enroll-container'));
        $output .= html_writer::start_tag('div', array('id' => 'main-enroll-button', 'class' => 'center-block'));
        $coursecontext = context_course::instance($COURSE->id);

        if (isguestuser() || !isloggedin()) {
            $link = new moodle_url('/login/index.php');
            $output .= get_string('loginorsignupmessage', 'theme_saylor', $link->out());
        } elseif (isloggedin($coursecontext) && !is_enrolled($coursecontext)) {
            $link = new moodle_url('/enrol/index.php', array('id' => $COURSE->id));
            $output .= $this->single_button($link->out(), get_string('enrolme', 'core_enrol'));
        };

        // Adding div that closes the main-enroll-button or the login/signup message.
        $output .= html_writer::end_tag('div');

        // Adding div that closes the enroll-button-container.
        $output .= html_writer::end_tag('div');

        return $output;
    }

}

class theme_saylor_block_course_overview_renderer extends \block_course_overview_renderer {

    /**
     * Construct contents of course_overview block
     *
     * Override
     *
     * @param  array $courses   list of courses in sorted order
     * @param  array $overviews list of course overviews
     * @return string html to be displayed in course_overview block
     */
    public function course_overview($courses, $overviews) {
        global $USER;
        $html = '';
        $config = get_config('block_course_overview');
        if ($config->showcategories != BLOCKS_COURSE_OVERVIEW_SHOWCATEGORIES_NONE) {
            global $CFG;
            include_once($CFG->libdir.'/coursecatlib.php');
        }
        $ismovingcourse = false;
        $courseordernumber = 0;
        $maxcourses = count($courses);
        $userediting = false;
        // Intialise string/icon etc if user is editing and courses > 1
        if ($this->page->user_is_editing() && (count($courses) > 1)) {
            $userediting = true;
            $this->page->requires->js_init_call('M.block_course_overview.add_handles');

            // Check if course is moving
            $ismovingcourse = optional_param('movecourse', false, PARAM_BOOL);
            $movingcourseid = optional_param('courseid', 0, PARAM_INT);
        }

        // Render first movehere icon.
        if ($ismovingcourse) {
            // Remove movecourse param from url.
            $this->page->ensure_param_not_in_url('movecourse');

            // Show moving course notice, so user knows what is being moved.
            $html .= $this->output->box_start('notice');
            $a = new stdClass();
            $a->fullname = $courses[$movingcourseid]->fullname;
            $a->cancellink = html_writer::link($this->page->url, get_string('cancel'));
            $html .= get_string('movingcourse', 'block_course_overview', $a);
            $html .= $this->output->box_end();

            $moveurl = new moodle_url(
                '/blocks/course_overview/move.php',
                array('sesskey' => sesskey(), 'moveto' => 0, 'courseid' => $movingcourseid)
            );
            // Create move icon, so it can be used.
            $movetofirsticon = html_writer::empty_tag(
                'img',
                array('src' => $this->output->image_url('movehere'),
                        'alt' => get_string('movetofirst', 'block_course_overview', $courses[$movingcourseid]->fullname),
                'title' => get_string('movehere'))
            );
            $moveurl = html_writer::link($moveurl, $movetofirsticon);
            $html .= html_writer::tag('div', $moveurl, array('class' => 'movehere'));
        }

        $html .= html_writer::start_span('inprogresscoursebox') . 'In Progress Courses' . html_writer::end_span();

        // Active course box
        foreach ($courses as $key => $course) {
            // If moving course, then don't show course which needs to be moved.
            if ($ismovingcourse && ($course->id == $movingcourseid)) {
                continue;
            }
            // Build params to get course completion info
            $params = array (
                'userid' => $USER->id,
                'course' => $course->id
                );
            // Create completion_completion object
            $ccompletion = new completion_completion($params);

            // If course has already been completed, skip.
            if ($ccompletion->is_complete()) {
                continue;
            }

            $courseordernumber++;

            $html .= self::render_course($course, $config, $userediting, $ismovingcourse, $courseordernumber);
        }

        // Separate active/completed course boxes. Should these be in separate divs?
        $html .= html_writer::empty_tag('br', null);
        $html .= html_writer::empty_tag('hr', null);
        $html .= html_writer::empty_tag('br', null);

        $html .= html_writer::start_span('completedcoursebox') . 'Completed Courses' . html_writer::end_span();

        // Completed course box
        foreach ($courses as $key => $course) {
            // If moving course, then don't show course which needs to be moved.
            if ($ismovingcourse && ($course->id == $movingcourseid)) {
                continue;
            }

            // Build params to get course completion info
            $params = array (
                'userid' => $USER->id,
                'course' => $course->id
                );
            // Create completion_completion object
            $ccompletion = new completion_completion($params);

            // If course has NOT been completed (is active), skip.
            if (!$ccompletion->is_complete()) {
                continue;
            }

            $courseordernumber++;

            $html .= self::render_course($course, $config, $userediting, $ismovingcourse, $courseordernumber);
        }

        // Wrap course list in a div and return.
        return html_writer::tag('div', $html, array('class' => 'course_list'));
    }

    /**
     * Renders a course for the coursebox
     *
     *
     * @param  course object $course
     * @return string HTML fragment
     */
    protected function render_course($course, $config, $userediting, $ismovingcourse, $courseordernumber) {
        $html = $this->output->box_start('coursebox', "course-{$course->id}");
        $html .= html_writer::start_tag('div', array('class' => 'course_title'));
        // If user is editing, then add move icons.
        if ($userediting && !$ismovingcourse) {
            $moveicon = html_writer::empty_tag(
                'img',
                array('src' => $this->image_url('t/move')->out(false),
                        'alt' => get_string('movecourse', 'block_course_overview', $course->fullname),
                'title' => get_string('move'))
            );
            $moveurl = new moodle_url($this->page->url, array('sesskey' => sesskey(), 'movecourse' => 1, 'courseid' => $course->id));
            $moveurl = html_writer::link($moveurl, $moveicon);
            $html .= html_writer::tag('div', $moveurl, array('class' => 'move'));
        }

        // No need to pass title through s() here as it will be done automatically by html_writer.
        $attributes = array('title' => $course->fullname);
        if ($course->id > 0) {
            if (empty($course->visible)) {
                $attributes['class'] = 'dimmed';
            }
            $courseurl = new moodle_url('/course/view.php', array('id' => $course->id));
            $coursefullname = format_string(get_course_display_name_for_list($course), true, $course->id);
            $link = html_writer::link($courseurl, $coursefullname, $attributes);
            $html .= $this->output->heading($link, 2, 'title');
        } else {
            $html .= $this->output->heading(
                html_writer::link(
                    new moodle_url('/auth/mnet/jump.php', array('hostid' => $course->hostid, 'wantsurl' => '/course/view.php?id='.$course->remoteid)),
                    format_string($course->shortname, true),
                    $attributes
                ) . ' (' . format_string($course->hostname) . ')',
                2,
                'title'
            );
        }
        $html .= $this->output->box('', 'flush');
        $html .= html_writer::end_tag('div');

        if (!empty($config->showchildren) && ($course->id > 0)) {
            // List children here.
            if ($children = block_course_overview_get_child_shortnames($course->id)) {
                $html .= html_writer::tag('span', $children, array('class' => 'coursechildren'));
            }
        }

        // If user is moving courses, then down't show overview.
        if (isset($overviews[$course->id]) && !$ismovingcourse) {
            $html .= $this->activity_display($course->id, $overviews[$course->id]);
        }

        if ($config->showcategories != BLOCKS_COURSE_OVERVIEW_SHOWCATEGORIES_NONE) {
            // List category parent or categories path here.
            $currentcategory = coursecat::get($course->category, IGNORE_MISSING);
            if ($currentcategory !== null) {
                $html .= html_writer::start_tag('div', array('class' => 'categorypath'));
                if ($config->showcategories == BLOCKS_COURSE_OVERVIEW_SHOWCATEGORIES_FULL_PATH) {
                    foreach ($currentcategory->get_parents() as $categoryid) {
                        $category = coursecat::get($categoryid, IGNORE_MISSING);
                        if ($category !== null) {
                            $html .= $category->get_formatted_name().' / ';
                        }
                    }
                }
                $html .= $currentcategory->get_formatted_name();
                $html .= html_writer::end_tag('div');
            }
        }

        $html .= $this->output->box('', 'flush');
        $html .= $this->output->box_end();
        if ($ismovingcourse) {
            $moveurl = new moodle_url(
                '/blocks/course_overview/move.php',
                array('sesskey' => sesskey(), 'moveto' => $courseordernumber, 'courseid' => $movingcourseid)
            );
            $a = new stdClass();
            $a->movingcoursename = $courses[$movingcourseid]->fullname;
            $a->currentcoursename = $course->fullname;
            $movehereicon = html_writer::empty_tag(
                'img',
                array('src' => $this->output->image_url('movehere'),
                        'alt' => get_string('moveafterhere', 'block_course_overview', $a),
                'title' => get_string('movehere'))
            );
            $moveurl = html_writer::link($moveurl, $movehereicon);
            $html .= html_writer::tag('div', $moveurl, array('class' => 'movehere'));
        }

        return $html;
    }
}

class theme_saylor_core_course_renderer extends \core_course_renderer
{
    // Change searchcriteria to only focus on courses from category 2.
    protected function coursecat_courses(coursecat_helper $chelper, $courses, $totalcount = null) {
        global $CFG;
        if ($totalcount === null) {
            $totalcount = count($courses);
        }
        if (!$totalcount) {
            // Courses count is cached during courses retrieval.
            return '';
        }

        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_AUTO) {
            // In 'auto' course display mode we analyse if number of courses is more or less than $CFG->courseswithsummarieslimit.
            if ($totalcount <= $CFG->courseswithsummarieslimit) {
                $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED);
            } else {
                $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_COLLAPSED);
            }
        }

        // Prepare content of paging bar if it is needed.
        $paginationurl = $chelper->get_courses_display_option('paginationurl');
        $paginationallowall = $chelper->get_courses_display_option('paginationallowall');
        if ($totalcount > count($courses)) {
            // there are more results that can fit on one page.
            if ($paginationurl) {
                // the option paginationurl was specified, display pagingbar.
                $perpage = $chelper->get_courses_display_option('limit', $CFG->coursesperpage);
                $page = $chelper->get_courses_display_option('offset') / $perpage;
                $pagingbar = $this->paging_bar($totalcount, $page, $perpage,
                        $paginationurl->out(false, array('perpage' => $perpage)));
                if ($paginationallowall) {
                    $pagingbar .= html_writer::tag('div', html_writer::link($paginationurl->out(false, array('perpage' => 'all')),
                            get_string('showall', '', $totalcount)), array('class' => 'paging paging-showall'));
                }
            } else if ($viewmoreurl = $chelper->get_courses_display_option('viewmoreurl')) {
                // the option for 'View more' link was specified, display more link.
                $viewmoretext = $chelper->get_courses_display_option('viewmoretext', new lang_string('viewmore'));
                $morelink = html_writer::tag('div', html_writer::link($viewmoreurl, $viewmoretext),
                        array('class' => 'paging paging-morelink'));
            }
        } else if (($totalcount > $CFG->coursesperpage) && $paginationurl && $paginationallowall) {
            // there are more than one page of results and we are in 'view all' mode, suggest to go back to paginated view mode.
            $pagingbar = html_writer::tag('div', html_writer::link($paginationurl->out(false, array('perpage' => $CFG->coursesperpage)),
                get_string('showperpage', '', $CFG->coursesperpage)), array('class' => 'paging paging-showperpage'));
        }

        // Display list of courses.
        $attributes = $chelper->get_and_erase_attributes('courses');
        $content = html_writer::start_tag('div', $attributes);

        if (!empty($pagingbar)) {
            $content .= $pagingbar;
        }

        $coursecount = 0;
        // First, create whitelist of courses in cat 2.
        $options['recursive'] = true;
        $options['coursecontacts'] = false;
        $options['summary'] = false;
        $options['sort']['idnumber'] = 1;

        $cat2courselist = coursecat::get(2)->get_courses($options);
        // Check all courses and put those with id 2 in whitelist.
        foreach ($cat2courselist as $cat2course) {
            $id = $cat2course->__get('id');
            $cat2courses[$id] = $id;
        };

        // Get list of courses and check if each course is in category 2.
        foreach ($courses as $course) {
            $courseisincat2 = false; // False = 0
            $coursecount ++;
            // Checking if course is in whitelist.
            foreach ($cat2courses as $cat2course) {
                if ($cat2course == $course->id) {
                    $courseisincat2 = true;
                    break;
                }
            }
            if ($courseisincat2 == false) {
                continue;
            }

            $classes = ($coursecount % 2) ? 'odd' : 'even';
            if ($coursecount == 1) {
                $classes .= ' first';
            }
            if ($coursecount >= count($courses)) {
                $classes .= ' last';
            }
            $content .= $this->coursecat_coursebox($chelper, $course, $classes);
        }

        if (!empty($pagingbar)) {
            $content .= $pagingbar;
        }
        if (!empty($morelink)) {
            $content .= $morelink;
        }

        $content .= html_writer::end_tag('div'); // .courses
        return $content;
    }
}

