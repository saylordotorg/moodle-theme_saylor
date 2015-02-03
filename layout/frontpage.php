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

// Get the HTML for the settings bits.
//$html = theme_allyou_get_html_for_settings($OUTPUT, $PAGE);

$left = (!right_to_left());  // To know if to add 'pull-right' and 'desktop-first-column' classes in the layout for LTR.

$hasfootnote = (!empty($PAGE->theme->settings->footnote));
$haslogo = (!empty($PAGE->theme->settings->logo));


$hasmarket1 = (!empty($PAGE->theme->settings->market1));
$hasmarket2 = (!empty($PAGE->theme->settings->market2));
$hasmarket3 = (!empty($PAGE->theme->settings->market3));
$hasmarket4 = (!empty($PAGE->theme->settings->market4));

//headeralignment
if (empty($PAGE->theme->settings->headeralign)) {
	$headeralign = "0";
} else {
$headeralign = $PAGE->theme->settings->headeralign;
}

if ($headeralign == 1) {
	$headerclass = "lalign";
} else {
	$headerclass = " ";
}

function Truncate($string, $length, $stopanywhere=false) {
    //truncates a string to a certain char length, stopping on a word if not specified otherwise.
    $string = strip_tags($string);
    if (strlen($string) > $length) {
        //limit hit!
        $string = substr($string,0,($length -3));
        if ($stopanywhere) {
            //stop anywhere
            $string .= '...';
        } else{
            //stop on a word.
            $string = substr($string,0,strrpos($string,' ')).'...';
        }
    }
    return $string;
}

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
    <link href='//fonts.googleapis.com/css?family=Lato:400,700,300' rel='stylesheet' type='text/css'>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body <?php echo $OUTPUT->body_attributes('two-column'); ?>>
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<div id="page" class="container-fluid">

<div id="page-header-wrapper" class="clearfix">
    <header id="page-header" class="clearfix <?php echo "$headerclass"; ?> lalign">
    <?php if ($haslogo) {
                        echo "<img src='".$PAGE->theme->settings->logo."' alt='logo' id='logo' />";
                    } else { ?>
			<img src="<?php echo $OUTPUT->pix_url('logo', 'theme')?>" id="logo">
			<?php } ?>
                   
        <div class="navbar pull-left">
    <nav role="navigation" class="navbar-inner">
        <div class="container-fluid">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="nav-collapse collapse">
                <?php echo $OUTPUT->custom_menu(); ?>
            </div>
        </div>
    </nav>
</div>
        <div id="course-header">
            <?php echo $OUTPUT->course_header(); ?>
        </div>
        
        
<div class="pull-right socials">
				<?php
	        	    echo $OUTPUT->login_info();
    	        	//echo $OUTPUT->lang_menu();
	        	    echo $PAGE->headingmenu;
		        ?>	
</div>
        
        
    </header>
</div>    


<div id="cname" class="container">
<h1>— <?php echo $this->page->course->fullname ?> —</h1>
</div>

<div id="navwrap">
  <div id="page-navbar" class="container clearfix">
            <nav class="breadcrumb-button"><?php echo $OUTPUT->page_heading_button(); ?></nav>
            <?php echo $OUTPUT->navbar(); ?>
    </div>
</div>


 <div id="page-content" class="row-fluid">
    
        <section id="region-main" class="span9<?php if ($left) { echo ' pull-right'; } ?>">
            <?php
            echo $OUTPUT->course_content_header();
            echo $OUTPUT->main_content();
            echo $OUTPUT->course_content_footer();
            ?>
        </section>
        <?php
        $classextra = '';
        if ($left) {
            $classextra = ' desktop-first-column';
        }
        echo $OUTPUT->blocks('side-pre', 'span3'.$classextra);
        ?>
    </div>


    <footer id="page-footer" class="clearfix">
        <div id="course-footer"><?php echo $OUTPUT->course_footer(); ?></div>
        
        <div class="container">
        
        <div class="span4">
        <div align="center"><img border="0" alt="Saylor" src="<?php echo $OUTPUT->pix_url('logo2', 'theme')?>"></div>
        <div class="footer-share">
<a title="Follow us on Facebook" target="_blank" href="http://www.facebook.com/SaylorFoundation" class="fb">Facebook</a>
<a title="Follow us on Twitter" target="_blank" href="http://twitter.com/#!/saylordotorg" class="twitter">Twitter</a>
<a title="Follow us on Google Plus" target="_blank" href="https://plus.google.com/105426497265909285606/posts" class="gplus">Twitter</a>
</div>
        </div>
        
        <div class="span4">
        
        <div class="textwidget"><p><a href="http://creativecommons.org/licenses/by/3.0/" rel="license"><img src="https://i.creativecommons.org/l/by/3.0/80x15.png" style="border-width:0" alt="Creative Commons License"></a><br>&copy; Saylor Foundation 2014. Except where otherwise noted, content on this site is licensed under a <a href="http://creativecommons.org/licenses/by/3.0/" rel="license">Creative Commons Attribution 3.0 Unported License</a>.</p>
<p>Saylor Academy and Saylor.org&reg; are trade names of the Constitution Foundation, a 501(c)(3) organization through which our educational activities are conducted.</p>
<p><a href="/sitemap">Sitemap</a> | <a href="/terms-of-use/">Terms of Use</a> | <a href="/privacy-policy">Privacy Policy</a></p>
</div>   
       </div>
       
       <div class="span3">
       
       <ul class="footer-nav">
<li><a href="/">Home</a></li>
<li><a href="/pathways">Courses</a></li>
<li><a href="/saylor-difference">Saylor Difference</a></li>
<li><a href="/about">About</a></li>
<li><a href="/donate">Donate</a></li>
<li><a href="/frequently-asked-questions">FAQ</a></li>
<li><a href="/feedback">Contact</a></li>
</ul>
       
       </div>
        
        <div class="clearfix row">
        <?php
        //echo $html->footnote;
        echo $OUTPUT->login_info();
       // echo $OUTPUT->home_link();
        echo $OUTPUT->standard_footer_html();
        ?>
        </div>
        
        </div>
        
    </footer>

    <?php echo $OUTPUT->standard_end_of_body_html() ?>

</div>
</body>
</html>
