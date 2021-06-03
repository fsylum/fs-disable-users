<?php

namespace Fsylum\DisableUsers\WP;

use WP_User;
use WP_Error;
use Fsylum\DisableUsers\Helper;
use Fsylum\DisableUsers\Contracts\Service;

class Auth implements Service
{
    public function run()
    {
        add_filter('authenticate', [$this, 'preventLogin'], 20);
        add_filter('allow_password_reset', [$this, 'preventPasswordReset'], 10, 2);
        add_filter( 'wp_is_application_passwords_available_for_user', [$this, 'preventApplicationPasswordCreation'], 10, 2);
    }

    public function preventLogin($user)
    {
        if ($user instanceof WP_User && Helper::isUserDisabled($user->ID)) {
            return new WP_Error('fsdu-user-disabled', __( '<strong>Error</strong>: Your user account is currently disabled.', 'fs-disable-users'));
        }

        return $user;
    }

    public function preventPasswordReset($allow, $user_id)
    {
        return !Helper::isUserDisabled($user_id);
    }

    public function preventApplicationPasswordCreation($available, $user)
    {
        return !Helper::isUserDisabled($user->ID);
    }
}
