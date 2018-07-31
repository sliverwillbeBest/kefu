<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Memcached settings
| -------------------------------------------------------------------------
| Your Memcached servers can be specified below.
|
|	See: https://codeigniter.com/user_guide/libraries/caching.html#memcached
|
*/
$config['socket_type'] = 'tcp';
$config['host'] = '127.0.0.1';
$config['password'] = '12345';
$config['port'] = 6379;
$config['timeout'] = 0;
