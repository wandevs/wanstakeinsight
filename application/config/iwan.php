<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$is_mainnet = true;

if ($is_mainnet)
{
    // Mainnet//
    $config['iwan_client'] = '';
    $config['iwan_secret'] = '';

}
else
{
    // Testnet //
    $config['iwan_client'] = '';
    $config['iwan_secret'] = '';
}

$config['iwan_timestamp'] = round(microtime(true) * 1000);

