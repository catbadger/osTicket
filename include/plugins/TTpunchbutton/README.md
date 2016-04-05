TimeTrex Punch Button plugin for osTicket

=================
CREDITS
=================

Written by Josh Richet at TimeTrex
aka catbadger on github

=================
INSTALLATION
=================
1 - copy the trex.btn folder to [osTicket]/include/plugins
2 - open [osTicket]/include/staff/header.inc.php
3 - In osTicket, go to Admin Panel > Manage > Plugins and click the Punch Button
4 - In the provided field enter your timetrex server url (ex: http://ondemand0.timetrex.com)
5 - right above the </head> tag, add the following:
<?php
    if(class_exists('TTPunchButton')) {
        $trexButton = new TTPunchButton( FALSE );
        $trexButton->script();
    }
?>
6 - If your timetrex installation is a local one, ensure that you have disabled the SAMEORIGIN policy
7 - Profit!!!