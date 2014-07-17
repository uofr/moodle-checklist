<?php

defined('MOODLE_INTERNAL') || die();

$plugin->version  = 2013111900;
$plugin->cron     = 0;
$plugin->requires = 2010112400; // Moodle 2.0+
$plugin->release  = '2.x (Build: 2013111900)';
$plugin->maturity = MATURITY_STABLE;
$plugin->dependencies = array('mod_checklist' => 2010041800); // Must have checklist activity module installed
