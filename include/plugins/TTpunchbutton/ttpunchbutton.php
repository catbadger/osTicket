<?php
require_once (INCLUDE_DIR . 'class.plugin.php');
require_once (INCLUDE_DIR . 'class.signal.php');
require_once (INCLUDE_DIR . 'class.app.php');
require_once (INCLUDE_DIR . 'class.dispatcher.php');
require_once (INCLUDE_DIR . 'class.dynamic_forms.php');
require_once (INCLUDE_DIR . 'class.plugin.php');
require_once (INCLUDE_DIR . 'class.signal.php');
require_once (INCLUDE_DIR . 'class.app.php');
require_once (INCLUDE_DIR . 'class.dispatcher.php');
require_once (INCLUDE_DIR . 'class.dynamic_forms.php');
require_once (INCLUDE_DIR . 'class.osticket.php');

require_once ('config.php');

define ( 'PunchButton_PLUGIN_VERSION', '0.01' );



class TTPunchButton extends Plugin{
    public $config_class = 'TTButtonConfig';

    /**
     * TTPunchButton constructor.
     * needed to create a constructor that can get it's own id.
     */
    function __construct(){
        $id = db_result(db_query('SELECT id FROM '.PLUGIN_TABLE.' WHERE install_path="plugins/TTpunchbutton"'));
        parent::__construct($id);
    }

    /**
     * the url set in config.
     * @return string
     */
    function getURL(){
        return $this->getConfig()->get( 'url' );
    }

    /**
     * required by parent class
     */
    function bootstrap(){
        return true;
    }

     /**
      * the script to insert in the head.
      * called in /include/staff/header.inc.php
      * because there's no script hooks for plugins
     */
    function script() {
        //first, build the url
        echo '<!-- trexbutton -->';
        if ( stristr( $_SERVER['PHP_SELF'], '/scp/tickets.php' ) ):
            $extras = '';
            $ticket_id = -1;
            if ( isset( $_GET['id'] ) ) {
                $ticket_id = $_GET['id'];
                $ticket = Ticket::lookup( $_REQUEST['id'] );
                $note = $ticket->getSubject();
                $note .= "\nTicket #: " . $ticket->getNumber();
                $note .= "\nEmail: " . $ticket->getEmail();
                $note .= "\nPhone: " . $ticket->getPhoneNumber();
                $note .= "\nContact: " . $ticket->getName();
                $note .= "\nOrganization: " . $ticket->getOwner()->getOrganization();
                $note = rawurlencode( $note );

                $job_item_id = 758; //email support
                if ( $ticket->getSource() == 'Phone' ) {
                    $job_item_id = 96; //phone support
                }

                $job_id = 4276; // support in live
                $extras = '&job_id='.$job_id.'&job_item_id='.$job_item_id.'&note='.$note;
            }

            //$timetrexurl = 'https://demo.timetrex.com/interface/html5/';
            $url = $this->getURL(). '#!m=Home&sm=InOut&transfer=1'.$extras;
    ?>
        <style>
                #timetrexButton {
                    cursor:pointer;
                }
                #hiddenbox-wrapper {
                    position: fixed;
                    top:0;
                    left:0;
                    width:100%;
                    height:100%;
                    background-color:rgba(0,0,0,0.5);
                    display:none;
                }
                #hiddenbox {
                    height: 55em;
                    width: 80%;
                    position: fixed;
                    top:0;
                    left:10%;
                    background: #fff;
                    margin:5% auto;
                    min-width:960px;
                    border-radius: 5px;
                }
                #closebtn {
                    cursor:pointer;
                    font-size:35px;
                    text-align:right;
                    border-bottom:1px solid #ccc;
                }
            </style>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
            <script>
                var ticket_id = <?php echo $ticket_id; ?>;
                //alert("<?php echo $url; ?>");
                /**
                 * closes the box for timetrex iframe
                 * ensures url is set back to the punch url

                function TTclosebox() {
                    $('#hiddenbox-wrapper').fadeOut('fast');
                    $('#timetrex_iframe').attr('src','<?php echo $url;?>');
                }
                */
                /**
                 * opens the box for timetrex iframe
                 * reloads the iframe every time to ensure accurate punch time

                function TTopenbox() {
                    $('#timetrex_iframe').get(0).contentWindow.location.reload();
                    $('#hiddenbox-wrapper').fadeIn('fast');
                }
                */
                /**
                 * the commented out code is our attempt to open timetrex in an iframe.
                 * this doesn't work because of the CORS policies on the server
                 *
                 * tried
                 * modifying document.domain with javascript
                 * changing the protocols
                 * modifying Content-Security-Policy in a bunch of ways.
                 *
                 * Nothing worked.
                 *
                 */

                function TTrunonce() {

                /**
                    $('#timetrex_iframe document').ready(function () {
                        var last_url = $('#timetrex_iframe').get(0).contentWindow.location.href;
                        setInterval(function () {
                            var new_url = $('#timetrex_iframe').get(0).contentWindow.location.href;
                            if (new_url != last_url) {
                                last_url = new_url;
                                TTclosebox();
                            }
                        }, 1000);
                    });
                */
                    //the button html
                    var button = '<span class="action-button pull-right">' +
                                    '<div id="timetrexButton" ' +
                                    'data-placement="bottom" ' +
                                    'title="TimeTrex Punch" ' +
                                    'href="#" ' +
                                    'data-original-title="TimeTrex Punch">' +
                                        '<i class="icon-dashboard"></i> Punch' +
                                    '</div>' +
                                '</span>';

                    $('.sticky.bar .flush-right').html(button + $('.sticky.bar .flush-right').html());

                    /**
                    //the box html
                    var hiddenbox = '<div id="hiddenbox-wrapper">' +
                                        '<div id="hiddenbox" >' +
                                            '<div id="closebtn"><i class="icon-remove-sign"></i></div>' +
                                            '<iframe id="timetrex_iframe" width="100%" height="100%" style="border:none;"></iframe>' +
                                        '</div>' +
                                    '</div>';

                    $('body').append(hiddenbox);
                    var blob = new Blob(["<?php echo $url; ?>"], { type: "application/xhtml+xml" });
                    $('#timetrex-iframe').src = URL.createObjectURL(blob);
                    **/

                    $('#timetrexButton').click(function(){
                       // TTopenbox();
                        window.open('<?php echo $url; ?>');

                    });
                    /**
                    $('#closebtn, #hiddenbox-wrapper').click(function(){
                        TTclosebox();
                    });

                    **/
                }
                $(document).ready(function(){
                    TTrunonce();
                });

				/**
				 * attach the button on ajax load of ticket.
                 */
                $(document).ajaxComplete(function(){
                    //fixes stale data bug by reloading the page
                    if ( ticket_id == -1 ) {
                        window.location.reload();
                    }
                    TTrunonce();
                });
            </script>
        <?php else: ?>
                <script>
                    $(document).ajaxComplete(function(){
                        //fixes stale data bug by reloading the page
                        window.location.reload();
                    });
                </script>
        <?php endif;
    }//script
}//class

?>