<?php

namespace Fsylum\DisableUsers;

class Helper
{
    const METAKEY = 'fs_disable_users_status';

    public static function isUserDisabled($user_id)
    {
        return (bool) get_user_meta(absint($user_id), self::METAKEY, true);
    }

    public static function disableUsers($user_ids = [])
    {
        $user_ids        = array_map('absint', $user_ids);
        $current_user_id = get_current_user_id();

        foreach ($user_ids as $user_id) {
            if ($user_id === $current_user_id) {
                continue;
            }

            update_user_meta($user_id, self::METAKEY, 1);
        }
    }

    public static function enableUsers($user_ids = [])
    {
        $user_ids        = array_map('absint', $user_ids);
        $current_user_id = get_current_user_id();

        foreach ($user_ids as $user_id) {
            if ($user_id === $current_user_id) {
                continue;
            }

            delete_user_meta($user_id, self::METAKEY);
        }
    }
}
