<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       http://github.com/usbmis/myfossil
 * @since      0.0.1
 *
 * @package    myFOSSIL_Logging
 * @subpackage myFOSSIL_Logging/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    myFOSSIL_Logging
 * @subpackage myFOSSIL_Logging/includes
 * @author     Brandon Wood <bwood@atmoapps.com>
 */
class myFOSSIL_Logging_Endpoints {

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    0.0.1
	 */
	public function __construct() {
	}

    /**
     * Yields triples for when `messages_message_sent` is triggered.
     *
     * @since 0.0.1
     */
    public function action__bp_messages_message_sent($raw_args = array()) {
        $message = is_object($raw_args) ? (array) $raw_args : $raw_args;

        $thread_id = $message->thread_id;
        $sender_id = $message->sender_id;
        $recipients = $message->recipients;

        // generate log entry that user sent message
        yield array(
            sprintf('user:%d', $sender_id),
            sprintf('sent'),
            sprintf('message:%d', $thread_id)
        );

        // bail if no recipients
        if (empty($recipients)) return;

        // generate log entries for message sent to each recipient
        foreach ($recipients as $r) {
            $_log_sub = sprintf('user:%d', $sender_id);
            $_log_pre = sprintf('messaged');
            $_log_obj = sprintf('user:%d', $r->user_id);
            yield array($_log_sub, $_log_pre, $_log_obj);
        }

        return;
    }


    /**
     * Yields triples for when `bp_activity_after_save` is triggered.
     *
     * @since 0.0.1
     */
    public function action__bp_activity_after_save($activity_obj) {
        $_log_sub = sprintf('user:%d', $activity_obj->user_id);
        $_log_pre = sprintf('%s', $activity_obj->type);
        $_log_obj = sprintf('%s:%d', $activity_obj->component, $activity_obj->item_id);
        yield array($_log_sub, $_log_pre, $_log_obj);
        return;
    }
}
