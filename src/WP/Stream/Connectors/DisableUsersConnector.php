<?php

namespace Fsylum\DisableUsers\WP\Stream\Connectors;

use WP_Stream\Connector;

class DisableUsersConnector extends Connector
{
    public $name = 'fs-disable-users';

    public $actions = [
        'fs_user_enabled',
        'fs_user_disabled',
    ];

    public function get_label()
    {
        return __('User Status', 'fs-disable-users');
    }

    public function get_context_labels()
    {
        return [
            'status' => __('Status', 'fs-disable-users'),
        ];
    }

    public function get_action_labels()
    {
        return [
            'enabled'  => __('Enabled', 'fs-disable-users'),
            'disabled' => __('Disabled', 'fs-disable-users'),
        ];
    }

    public function action_links($links, $record)
    {
        if ($record->object_id ){
            $links[__('Edit User', 'fs-disable-users')] = get_edit_user_link($record->object_id);
        }

        return $links;
    }

    public function callback_fs_user_enabled($user_id)
    {
        $user = get_userdata($user_id);

        /* translators: %1$s: a user display name, %2$s: a username (e.g. "Jane Doe", "administrator") */
        $message = _x(
            'User %1$s (%2$s) is enabled',
            '1: User display name, 2: User login',
            'fs-disable-users'
        );

        $this->log(
            $message,
            [
                'display_name' => $user->display_name,
                'user_login'   => $user->user_login,
            ],
            $user_id,
            'status',
            'enabled'
        );
    }

    public function callback_fs_user_disabled($user_id)
    {
        $user = get_userdata($user_id);

        /* translators: %1$s: a user display name, %2$s: a username (e.g. "Jane Doe", "administrator") */
        $message = _x(
            'User %1$s (%2$s) is disabled',
            '1: User display name, 2: User login',
            'fs-disable-users'
        );

        $this->log(
            $message,
            [
                'display_name' => $user->display_name,
                'user_login'   => $user->user_login,
            ],
            $user_id,
            'status',
            'disabled'
        );
    }
}
