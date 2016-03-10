<?php



trait DebugLog
{

    /**
     * Debugging output level.
     *
     * @since 1.0.0
     */
    public static $debug = 0;

    /**
     * Debugging output target.
     *
     * @since 1.0.0
     */
    public static $display = false;


    protected static function debug_out($msg, $lvl = 0)
    {
        if (self::$debug >= $lvl) {
            if (PHP_SAPI === "cli") {
                print "$msg\n";
            } else {
                if (self::$display) {
                    print "$msg<br>\n";
                } else {
                    trigger_error($msg, E_USER_NOTICE);
                }
            }
        }
    }

    protected static function debug_dump($msg, $attr, $lvl = 0)
    {
        if (self::$debug >= $lvl) {
            if (PHP_SAPI === "cli") {
                print "$msg:\n";
                var_dump($attr);
            } else {
                if (self::$display) {
                    print "$msg:<br>\n<pre>";
                    var_dump($attr);
                    print "</pre>\n";
                } else {
                    trigger_error($msg, E_USER_NOTICE);
                    ob_start();
                    var_dump($mixed);
                    $content = ob_get_contents();
                    ob_end_clean();
                    trigger_error($content, E_USER_NOTICE);
                }
            }
        }
    }

}

?>