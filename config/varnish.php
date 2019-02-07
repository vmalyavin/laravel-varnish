<?php
return [
    // flag
    'is_configured'      => true,
    // Url where varnish listen managment http commands
    'management_uri'     => 'http://example.com',
    // Timeout for http client init
    'management_timeout' => 3,
    // default cache ttl in seconds
    'default_cache_ttl'  => 3600 * 24,
    // cache header name, that contains response cache time for varnish
    'cache_header_time'  => 'X-cache',
    // cache header name, that contains response cache tags
    'cache_header_tags'  => 'X-depends-on',
];