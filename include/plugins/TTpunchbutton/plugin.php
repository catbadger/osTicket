<?php

/**
 * Provides a button for agents to click to link them to time for current client.
 *
 * @author
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(__file__).'/include');
return array(
    'timetrex:ttpunchbutton', # notrans
    'version' => '0.1',
    'name' => 'Punch Button',
    'author' => 'Josh Richet from Timetrex',
    'description' => 'Provides a way for customer support to click through to transfer billable hours to correct client in Timetrex.',
    'url' => 'http://timetrex.com',
    'plugin' => 'ttpunchbutton.php:TTPunchButton'
);
?>