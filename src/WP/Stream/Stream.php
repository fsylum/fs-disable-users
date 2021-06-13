<?php

namespace Fsylum\DisableUsers\WP\Stream;

use Fsylum\DisableUsers\Contracts\Service;
use Fsylum\DisableUsers\WP\Stream\Connectors\DisableUsersConnector;

class Stream implements Service
{
    public function run()
    {
        add_filter('wp_stream_connectors', [$this, 'addConnector']);
    }

    public function addConnector($classes)
    {
        $classes[] = new DisableUsersConnector;

        return $classes;
    }
}
