<?php

namespace Fsylum\DisableUsers\WP;

use Fsylum\DisableUsers\Contracts\Service;

class Asset implements Service
{
    public function run()
    {
        add_action('admin_enqueue_scripts', [$this, 'loadAssets']);
    }

    public function loadAssets(string $hook)
    {
        if (!in_array($hook, ['users.php', 'user-edit.php'])) {
            return;
        }

        wp_enqueue_style(
            'fsdu-admin',
            FS_DISABLE_USERS_PLUGIN_URL . '/assets/dist/css/admin.css',
            [],
            wp_get_environment_type() === 'production' ? FS_DISABLE_USERS_VERSION : time()
        );
    }
}
