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

function course_share_buttons() {
    global $CFG, $PAGE;
            if (isset($CFG->shariffbuttons)) {
                // Set some variables that we want for the course pages here.
                // URL to share
                $shareurl = $CFG->wwwroot.'/course/view.php?id='.$PAGE->course->id; 
                // Title of the page to use when sharing. Can show up as title for Twitter/WhatsApp or in message body text.
                $sharetitle = $PAGE->course->fullname;


                $share_buttons_output = '<div class="shariff"';
                if (!empty($CFG->shariffbuttons['backendurl'])) {
                    $share_buttons_output = $share_buttons_output.' data-backend-url="'.$CFG->shariffbuttons['backendurl'].'"';
                }
                if (!empty($CFG->shariffbuttons['lang'])) {
                    $share_buttons_output = $share_buttons_output.' data-lang="'.$CFG->shariffbuttons['lang'].'"';
                }
                if (!empty($CFG->shariffbuttons['mailbody'])) {
                    $share_buttons_output = $share_buttons_output.' data-mail-body="'.$CFG->shariffbuttons['mailbody'].'"';
                }
                if (!empty($CFG->shariffbuttons['mailsubject'])) {
                    $share_buttons_output = $share_buttons_output.' data-mail-subject="'.$CFG->shariffbuttons['mailsubject'].'"';
                }
                if (!empty($CFG->shariffbuttons['mailurl'])) {
                    $share_buttons_output = $share_buttons_output.'data-mail-url="'.$CFG->shariffbuttons['mailurl'].'"';
                }
                if (!empty($CFG->shariffbuttons['mediaurl'])) {
                    $share_buttons_output = $share_buttons_output.'data-media-url="'.$CFG->shariffbuttons['mediaurl'].'"';
                }
                if (!empty($CFG->shariffbuttons['orientation'])) {
                    $share_buttons_output = $share_buttons_output.' data-orientation="'.$CFG->shariffbuttons['orientation'].'"';
                }
                if (!empty($CFG->shariffbuttons['referertrack'])) {
                    $share_buttons_output = $share_buttons_output.' data-referrer-track="'.$CFG->shariffbuttons['referertrack'].'"';
                }
                if (!empty($CFG->shariffbuttons['services'])) {
                    $share_buttons_output = $share_buttons_output.' data-services="'.$CFG->shariffbuttons['services'].'"';
                }
                if (!empty($CFG->shariffbuttons['theme'])) {
                    $share_buttons_output = $share_buttons_output.' data-theme="'.$CFG->shariffbuttons['theme'].'"';
                }
                if (!empty($CFG->shariffbuttons['title'])) {
                    $share_buttons_output = $share_buttons_output.' data-title="'.$CFG->shariffbuttons['title'].'"';
                }
                else {
                    // Set the title to $sharetitle (defined above) unless we globally override in config.php.
                    $share_buttons_output = $share_buttons_output.' data-title="'.$sharetitle.'"';
                }
                if (!empty($CFG->shariffbuttons['twittervia'])) {
                    $share_buttons_output = $share_buttons_output.' data-twitter-via="'.$CFG->shariffbuttons['twittervia'].'"';
                }
                if (!empty($CFG->shariffbuttons['url'])) {
                    $share_buttons_output = $share_buttons_output.' data-url="'.$CFG->shariffbuttons['url'].'"';
                }
                else {
                    // For these pages, we want the link shared to be that of the course page.
                    $share_buttons_output = $share_buttons_output.' data-url="'.$shareurl.'"';
                }

                $share_buttons_output = $share_buttons_output.'></div>';
            }
        return $share_buttons_output;
}

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
    <link href='//fonts.googleapis.com/css?family=Lato:400,700,300' rel='stylesheet' type='text/css'>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body <?php echo $OUTPUT->body_attributes('two-column'); ?>>
    <?php echo $OUTPUT->standard_top_of_body_html() ?>

    <div id="page" class="container-fluid">
        <div id="page-header-wrapper" class="clearfix">
            <header id="page-header" class="clearfix <?php echo "$headerclass"; ?> lalign">
                <div class="logo-div">
                    <a class="logo-img" style="background-image: url('<?php echo $OUTPUT->pix_url('logo', 'theme')?>');" href="/"></a>
                </div> 
                           
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

                    <!-- share buttons -->
                    <div id="share-buttons">
                        <br><p>Share this course!</p>
                        <?php echo course_share_buttons(); ?>
                    </div>
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
            <div align="center" class="logo">
                <a class="logo" style="background-image: url('<?php echo $OUTPUT->pix_url('logo2', 'theme')?>');" href="/"></a>
            </div>
            <div class="footer-share">
                <a title="Facebook | /SaylorFoundation" target="_blank" href="https://www.facebook.com/SaylorFoundation" class="fa fa-facebook fa-2x"></a>
                <a title="Twitter | @saylordotorg" target="_blank" href="https://twitter.com/saylordotorg" class="fa fa-twitter fa-2x"></a>
                <a title="Google+ | +SaylorAcademy" target="_blank" href="https://plus.google.com/105426497265909285606/posts" class="fa fa-google-plus fa-2x"></a>
                <a title="Instagram | @saylordotorg" target="_blank" href="https://wwwinstagram.com/saylordotorg" class="fa fa-instagram fa-2x"></a>
                <a title="GitHub | /saylordotorg" target="_blank" href="https://github.com/saylordotorg" class="fa fa-github fa-2x"></a>
            </div>
        </div>
        <div class="span4">
        <div class="textwidget"><p><a href="http://creativecommons.org/licenses/by/3.0/" rel="license"><img src="<?php echo $OUTPUT->pix_url('ccby', 'theme')?>" style="border-width:0" alt="Creative Commons License"></a><br>&copy; Saylor Academy 2010-2016. Except where otherwise noted, content on this site is licensed under a <a href="http://creativecommons.org/licenses/by/3.0/" rel="license">Creative Commons Attribution 3.0 Unported License</a>.</p>
<p>Saylor Academy and Saylor.org&reg; are trade names of the Constitution Foundation, a 501(c)(3) organization through which our educational activities are conducted.</p>
<p><a href="http://www.saylor.org/sitemap">Sitemap</a> | <a href="http://www.saylor.org/terms-of-use">Terms of Use</a> | <a href="http://www.saylor.org/privacy-policy">Privacy Policy</a></p>
</div>   
       </div>
       
       <div class="span3">
       
       <ul class="footer-nav">
<li><a href="/">Home</a></li>
<li><a href="http://saylor.org/about">About</a></li>
<li><a href="http://saylor.org/donate">Donate</a></li>
<li><a href="https://sayloracademy.zendesk.com">Help</a></li>
<li><a href="http://www.saylor.org/blog">Blog</a></li>
<li><a href="http://saylor.org/contact">Contact</a></li>
<li><a href="http://www.saylor.org/search">Search</a></li>
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
    <!-- Start of Saylor Academy Zendesk Widget script -->
    <script>/*<![CDATA[*/window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="javascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(c){n=document.domain,r.src='javascript:var d=document.open();d.domain="'+n+'";void(0);',o=s}o.open()._l=function(){var o=this.createElement("script");n&&(this.domain=n),o.id="js-iframe-async",o.src=e,this.t=+new Date,this.zendeskHost=t,this.zEQueue=a,this.body.appendChild(o)},o.write('<body onload="document._l();">'),o.close()}("//assets.zendesk.com/embeddable_framework/main.js","sayloracademy.zendesk.com");/*]]>*/</script>
    <!-- End of Saylor Academy Zendesk Widget script -->
    <script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-16530955-7', 'auto');
ga('send', 'pageview');
</script>
</body>
</html>