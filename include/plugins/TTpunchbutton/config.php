<?php
class TTButtonConfig extends PluginConfig {
    function getOptions() {
        return array (
            'url' => new TextboxField ( array (
                'id' => 'url',
                'label' => 'Enter the url of your timetrex installation eg: http://joshr.dev1.office.timetrex.com/timetrex/trunk/interface/html5',
                'configuration' => array (
                    'desc' => 'Enter the url of your timetrex installation',
                    'length'=>400,
                    'size'=>100 )
            ) ),

        );
    }

    function pre_save() {
        return true;
    }
}