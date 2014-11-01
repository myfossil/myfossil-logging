<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://github.com/usbmis/myfossil
 * @since      0.0.1
 *
 * @package    myFOSSIL_Logging
 * @subpackage myFOSSIL_Logging/admin/partials
 */
?>

<?php
function myfossil_logging__home_page() { 
    ?>
    <div class="wrap">
        <h2>myFOSSIL Logging</h2>
    </div>
<?php } 

function myfossil_logging__settings_page() { 
    ?>
    <div class="wrap">
        <h2>myFOSSIL Logging Settings</h2>
        <form method="post" action="options.php"> 
            <?php settings_fields('myfossil-logging'); ?>
            <?php do_settings_sections('myfossil-logging'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Log Directory</th>
                    <td>
                        <input type="text" name="log_directory" value="<?php echo esc_attr(get_option('log_directory')); ?>" />
                        <p>Directory on server to log files, do not include trailing slash</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Rotate Logs</th>
                    <td>
                        <select name="log_rotate">
                        <?php
                        $log_rotate_options = array('hourly', 'daily', 'monthly', 'never');
                        foreach ($log_rotate_options as $opt) {
                            $v = $opt;
                            $s = get_option('log_rotate') == $v ? " selected=\"selected\"" : "";
                            $disp = ucfirst($v);
                            ?>
                            <option value="<?=$v ?>"<?=$s ?>><?=$disp ?></option>
                            <?php
                        }
                        ?>
                        </select>
                        <p>How often to rotate logs on disk</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php } ?>
