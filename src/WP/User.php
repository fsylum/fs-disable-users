<?php

namespace Fsylum\DisableUsers\WP;

use Fsylum\DisableUsers\Helper;
use Fsylum\DisableUsers\Contracts\Service;

class User implements Service
{
    const QS_KEY                = 'fsdu-action';
    const NONCE_KEY             = 'fsdu-single-action-nonce';
    const ACTION_BULK_ENABLE    = 'fsdu-enable-users';
    const ACTION_BULK_DISABLE   = 'fsdu-disable-users';
    const ACTION_SINGLE_ENABLE  = 'fsdu-enable-user';
    const ACTION_SINGLE_DISABLE = 'fsdu-disable-user';

    public function run()
    {
        add_filter('user_row_actions', [$this, 'addActionLink'], 10, 2);
        add_filter('bulk_actions-users', [$this, 'addBulkAction']);
        add_filter('handle_bulk_actions-users', [$this, 'handleBulkAction'], 10, 3);
        add_action('admin_notices', [$this, 'showNotice']);
        add_action('admin_action_' . self::ACTION_SINGLE_ENABLE, [$this, 'enableSingleUser']);
        add_action('admin_action_' . self::ACTION_SINGLE_DISABLE, [$this, 'disableSingleUser']);
        add_action('personal_options', [$this, 'addActionLinkToEditUser']);
    }

    public function addActionLink($actions, $user)
    {
        if ($user->ID === get_current_user_id()) {
            return $actions;
        }

        $is_user_disabled = Helper::isUserDisabled($user->ID);
        $action_url       = $this->generateSingleActionUrl($user, $is_user_disabled ? self::ACTION_SINGLE_ENABLE : self::ACTION_SINGLE_DISABLE);

        if ($is_user_disabled) {
            $actions['fsdu-enable-user'] = sprintf(
                '<a href="%s">%s</a>',
                $action_url,
                __('Enable', 'fs-disable-users')
            );
        } else {
            $actions['fsdu-disable-user'] = sprintf(
                '<a href="%s" class="delete">%s</a>',
                $action_url,
                __('Disable', 'fs-disable-users')
            );
        }

        return $actions;
    }

    public function addBulkAction($actions)
    {
        $actions[self::ACTION_BULK_ENABLE]  = __('Enable', 'fs-disable-users');
        $actions[self::ACTION_BULK_DISABLE] = __('Disable', 'fs-disable-users');

        return $actions;
    }

    public function handleBulkAction($sendback, $doaction, $items)
    {
        if (!in_array($doaction, [self::ACTION_BULK_ENABLE, self::ACTION_BULK_DISABLE])) {
            return $sendback;
        }

        if (!current_user_can('edit_users')) {
            return $sendback;
        }

        switch ($doaction) {
            case self::ACTION_BULK_ENABLE:
                Helper::enableUsers($items);
                break;

            case self::ACTION_BULK_DISABLE:
                Helper::disableUsers($items);
                break;
        }

        return add_query_arg([
            self::QS_KEY => $doaction === self::ACTION_BULK_ENABLE ? 'bulk-enabled' : 'bulk-disabled',
        ], $sendback);
    }

    public function showNotice()
    {
        if (!isset($_GET[self::QS_KEY])) {
            return;
        }

        switch (sanitize_text_field($_GET[self::QS_KEY])) {
            case 'enabled':
                $message = __('Selected user have been enabled.', 'fs-disable-users');
                break;

            case 'disabled':
                $message = __('Selected user have been disabled.', 'fs-disable-users');
                break;

            case 'bulk-enabled':
                $message = __('Selected users have been enabled.', 'fs-disable-users');
                break;

            case 'bulk-disabled':
                $message = __('Selected users have been disabled.', 'fs-disable-users');
                break;
        }

        printf(
            '<div class="updated notice is-dismissible"><p>%s</p></div>',
            $message
        );
    }

    public function enableSingleUser()
    {
        if (!current_user_can('edit_users')) {
            wp_die(__('You do not have enough privileges to enable this user.', 'fs-disable-users'));
        }

        if (empty($user_id = absint($_GET['id']))) {
            wp_die(__('Invalid user.', 'fs-disable-users'));
        }

        if ($user_id === get_current_user_id()) {
            wp_die(__('You can\'t enable your own user.', 'fs-disable-users'));
        }

        check_admin_referer(self::NONCE_KEY);

        Helper::enableUsers([$user_id]);

        $sendback = add_query_arg([
            self::QS_KEY => 'enabled',
        ], wp_get_referer());

        wp_safe_redirect($sendback);
        exit;
    }

    public function disableSingleUser()
    {
        if (!current_user_can('edit_users')) {
            wp_die(__('You do not have enough privileges to disable this user.', 'fs-disable-users'));
        }

        if (empty($user_id = absint($_GET['id']))) {
            wp_die(__('Invalid user.', 'fs-disable-users'));
        }

        if ($user_id === get_current_user_id()) {
            wp_die(__('You can\'t disable your own user.', 'fs-disable-users'));
        }

        check_admin_referer(self::NONCE_KEY);

        Helper::disableUsers([$user_id]);

        $sendback = add_query_arg([
            self::QS_KEY => 'disabled',
        ], wp_get_referer());

        wp_safe_redirect($sendback);
        exit;
    }

    public function addActionLinkToEditUser($user)
    {
        $is_user_disabled = Helper::isUserDisabled($user->ID);
        $action_url       = $this->generateSingleActionUrl($user, $is_user_disabled ? self::ACTION_SINGLE_ENABLE : self::ACTION_SINGLE_DISABLE);
        ?>
        <tr class="fs-disable-users-wrap">
            <th scope="row"><?php _e('Disable User', 'fs-disable-users'); ?></th>

            <?php if ($is_user_disabled): ?>
                <td><a class="button button-secondary" href="<?php echo esc_url($action_url); ?>"><?php _e('Enable', 'fs-disable-users'); ?></a></td>
            <?php else: ?>
                <td><a class="button button-delete" href="<?php echo esc_url($action_url); ?>"><?php _e('Disable', 'fs-disable-users'); ?></a></td>
            <?php endif; ?>
        </tr>
        <?php
    }

    private function generateSingleActionUrl($user, $action)
    {
        $url = add_query_arg([
            'action' => $action,
            'id'     => $user->ID,
        ], admin_url('users.php'));

        $url = wp_nonce_url($url, self::NONCE_KEY);

        return $url;
    }
}
