# wanstakeinsight
Wanstakeinsight.com source code

REQUIREMENT
- PHP 5.4+

SETUP
1. Edit file in application/config.php
$config['base_url'] = 'Your URL path is here'; 
with your developement path such as "http://wanstakeinsight.local/"

2. Edit file in application/iwan.php
$is_mainnet=true; 
be "false" if you want to use TESTNET
$config['iwan_client'] = 'wss://api.wanchain.org:8443/ws/v3/foobar'; // Replace "foobar" with client key
$config['iwan_secret'] = 'foobar'; // Replace "foobar" with client secret

3. Don't forget to chmod 777 on application/cache

HOW CLEAR CACHE
https://www.wanstakeinsight.com/welcome/clearPageCache/fennecisafox

You can change password at application/Welcome.php (Line: 8)

That's it!