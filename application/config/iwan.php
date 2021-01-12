<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$is_mainnet = true;

if ($is_mainnet)
{
    // Mainnet//
    $config['iwan_client'] = 'wss://api.wanchain.org:8443/ws/v3/46a4d77fb79a2f5669165ab96a668ae79f52f39fc6e2bba8f465f24d9f9e12e6';
    $config['iwan_secret'] = '3668222136ae7fd8760e454230054ac8ee56d5aef916135cc329eacc1986527f';

}
else
{
    // Testnet //
    $config['iwan_client'] = 'wss://apitest.wanchain.org:8443/ws/v3/8ad255a80bed21854d828c26fe0d6dd8f4834042bb16b9fcaa2e6bb2a99da1b5';
    $config['iwan_secret'] = '1019e1e72627732dcbedf94d2242f3ec4c6838b6a64e9df51d193851142feae9';
}

$config['iwan_timestamp'] = round(microtime(true) * 1000);

