<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use WebSocket\Client;
class Token extends CI_Controller {
	public $client = null;
	 public function __construct()
	 {
			parent::__construct();
			// Your own constructor code
			
			$this->client = new Client($this->config->item('iwan_client'));
	 }
	private function _getprice()
	{
		// connect via SSL, but don't check cert
		$handle=curl_init('https://min-api.cryptocompare.com/data/pricemulti?fsyms=BTC,ETH,EOS,USDT,USDC,WAN,FNX&tsyms=USD,CNY');
		curl_setopt($handle, CURLOPT_VERBOSE, true);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		$content = curl_exec($handle);

		return $content; // show target page
	}
	
	private function _getTokenSupply($address,$chain='ETH')
    {
        
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);
        $this->load->driver('cache', array('adapter' => 'file'));
        $method = 'getTokenSupply';
        if (!$result = $this->cache->get($method.'_'.$address))
        {
            $params_array = array(
				'chainType' => $chain,
				'tokenScAddr' => $address,
                'timestamp' => $this->config->item('iwan_timestamp')
            );
            $signature_message = array(
                'jsonrpc' => '2.0',
                'method' => $method,
                'params' => $params_array,
                'id' => 0,
            );
            $signature = base64_encode(hash_hmac('sha256', json_encode($signature_message), $secret, true));
            $params_array["signature"] = $signature;
            $query_array = array(
                'jsonrpc' => '2.0',
                'method' => $method,
                'params' => $params_array,
                'id' => 0,
            );

            $query_string = json_encode($query_array);

            $this->client->send($query_string);
            $result = json_decode($this->client->receive(), true);

            if (isset($result['result']) && $result['result']) {

                $result = $result['result'];

                $this->cache->save($method.'_'.$address, $result, 300); // 5 mins
            } else {
                $result = '';
                $this->output->delete_cache();
            }
        }
        return $result;
    }
	
	private function _getTokenBalance($address,$scAddress)
    {
        
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);
        $this->load->driver('cache', array('adapter' => 'file'));
        $method = 'getTokenBalance';
        if (!$result = $this->cache->get($method.'_'.md5($address.$scAddress)))
        {
            $params_array = array(
				'address' => $address,
				'tokenScAddr' => $scAddress,
                'timestamp' => $this->config->item('iwan_timestamp')
            );
            $signature_message = array(
                'jsonrpc' => '2.0',
                'method' => $method,
                'params' => $params_array,
                'id' => 0,
            );
            $signature = base64_encode(hash_hmac('sha256', json_encode($signature_message), $secret, true));
            $params_array["signature"] = $signature;
            $query_array = array(
                'jsonrpc' => '2.0',
                'method' => $method,
                'params' => $params_array,
                'id' => 0,
            );

            $query_string = json_encode($query_array);

            $this->client->send($query_string);
            $result = json_decode($this->client->receive(), true);

            if (isset($result['result']) && $result['result']) {

                $result = $result['result'];

                $this->cache->save($method.'_'.md5($address.$scAddress), $result, 300); // 5 min
            } else {
                $result = '';
                $this->output->delete_cache();
            }
        }
        return $result;
    }
	
	function wasp_text()
	{
		function custom_format($number)
		{
			$tmp = floor($number);
			$digit = $number - $tmp;
			$tmp = number_format($tmp);
			return $tmp.'.'.substr(str_replace('0.','',$digit.''),0,6);
		}
		$price = json_decode($this->_getprice(),true);
		$token0 = $this->_getTokenBalance('0x29239a9B93A78decEc6E0Dd58ddBb854B7fFB0af','0x8b9f9f4aa70b1b0d586be8adfb19c1ac38e05e9a')/WAN_DIGIT;
		sleep(1);
		
		
		$token1 = $this->_getTokenBalance('0x29239a9B93A78decEc6E0Dd58ddBb854B7fFB0af','0xdabd997ae5e4799be47d6e69d9431615cba28f48')/WAN_DIGIT;
		sleep(1);
		$wasp_supply = $this->_getTokenSupply('0x8b9f9f4aa70b1b0d586be8adfb19c1ac38e05e9a','WAN')/WAN_DIGIT;
		
		echo 'Price & Supply<br/>-------------------<br/>';
		echo 'Supply = '.number_format($wasp_supply).' WASP<br/>';
		echo '1 WAN = '. number_format($token0/$token1,2).' WASP<br/>';
		$rate = $token0/$token1;
		$wan_reflect = $token0/$rate;
		echo '1 WASP = '.number_format($price['WAN']['USD']/$rate,5).' USD<br/><br/>';
		echo 'Liquidity Pool<br/>-------------------<br/>';
		echo 'Pool Size = '. number_format($wan_reflect*$price['WAN']['USD']+$token1*$price['WAN']['USD']).' USD<br/>';
		echo number_format($token0).' WASP | ';
		echo number_format($token1).' WAN';
		
		echo '<br/>-------------------<br/>'.date('Y-m-d H:i',time()).'Z';
	}
	
	
	private function insert_db($asset_name,$asset_amount,$asset_price,$chain,$timestamp)
		{
			if (!isset($asset_amount) || !$asset_amount)
			return;
			$this->db->insert('crosschain_stats',array(
				'asset_name'=>$asset_name,
				'asset_amount'=>$asset_amount,
				'asset_price'=>$asset_price,
				'asset_tvl'=>$asset_amount*$asset_price,
				'chain'=>$chain,
				'timestamp'=>$timestamp
			));
		}
	public function sync()
	{
		
		$this->load->database();
		
		$price = json_decode($this->_getprice(),true);
		$timestamp = date('Y-m-d H:i',time());
		
		//=============Wanchain============//
		// WWan //
		$amount = $this->_getTokenSupply('0xDABd997Ae5e4799be47D6e69d9431615cbA28F48','WAN')/WAN_DIGIT;
		$this->insert_db('WWAN',$amount,$price['WAN']['USD'],'wan',$timestamp);
		sleep(3);
		// wanETH //
		$amount = $this->_getTokenSupply('0xe3Ae74d1518a76715Ab4c7bEdf1AF73893CD435a','WAN')/WAN_DIGIT;
		$this->insert_db('wanETH',$amount,$price['ETH']['USD'],'wan',$timestamp);
		sleep(3);
		// wanBTC //
		$amount = $this->_getTokenSupply('0xD15E200060Fc17ef90546ad93c1C61BfeFDC89C7','WAN')/100000000;
		$this->insert_db('wanBTC',$amount,$price['BTC']['USD'],'wan',$timestamp);
		sleep(3);
		// wanEOS //
		$amount = $this->_getTokenSupply('0x81862B7622ceD0deFb652aDDD4E0C110205b0040','WAN')/10000;
		$this->insert_db('wanEOS',$amount,$price['EOS']['USD'],'wan',$timestamp);
		sleep(3);
		// wanUSDT //
		$amount = $this->_getTokenSupply('0x11E77e27aF5539872EFeD10ABAa0B408CFD9Fbbd','WAN')/1000000;
		$this->insert_db('wanUSDT',$amount,$price['USDT']['USD'],'wan',$timestamp);
		sleep(3);
		// wanUSDC //
		$amount = $this->_getTokenSupply('0x52a9cea01C4cbdD669883E41758b8Eb8E8e2b34B','WAN')/1000000;
		$this->insert_db('wanUSDC',$amount,$price['USDC']['USD'],'wan',$timestamp);
		sleep(3);
		//============Ethereum=============//
		// WAN //
		$amount = $this->_getTokenSupply('0x135B810e48e4307AB2a59ea294A6f1724781bD3C','ETH')/WAN_DIGIT;
		$this->insert_db('WAN',$amount,$price['WAN']['USD'],'eth',$timestamp);
		sleep(3);
		// wanBTC //
		$amount = $this->_getTokenSupply('0x058a55925627980dbb6d6d39f8dad5de5be16764','ETH')/100000000;
		$this->insert_db('wanBTC',$amount,$price['BTC']['USD'],'eth',$timestamp);
		sleep(3);
		// wanEOS //
		$amount = $this->_getTokenSupply('0x11167f7889ae34E2C6b15c9226D0b320C45d629D','ETH')/10000;
		$this->insert_db('wanEOS',$amount,$price['EOS']['USD'],'eth',$timestamp);
		sleep(3);
		
	}
	
	
	public function index()
	{
		$this->output->cache(30);
		$this->load->database();
		$wanchain_assets = array('WWAN','wanETH','wanBTC','wanEOS','wanUSDT','wanUSDC');
		$ethereum_assets = array('WAN','wanBTC','wanEOS');
		$view['asset_icons'] = array(
			'WWAN' => 'https://raw.githubusercontent.com/wanswap/token-list/main/icons/wwan.png',
			'WAN' => 'https://raw.githubusercontent.com/wanswap/token-list/main/icons/wwan.png',
			'wanETH' => 'https://raw.githubusercontent.com/wanswap/token-list/main/icons/wanETH.png',
			'wanBTC' => 'https://raw.githubusercontent.com/wanswap/token-list/main/icons/wanBTC.png',
			'wanEOS' => 'https://raw.githubusercontent.com/wanswap/token-list/main/icons/wanEOS.png',
			'wanUSDT' => 'https://raw.githubusercontent.com/wanswap/token-list/main/icons/wanUSDT.png',
			'wanUSDC' => 'https://raw.githubusercontent.com/wanswap/token-list/main/icons/wanUSDC.png'
		);
		
		$view['wanchain_tvl'] = 0;
		foreach($wanchain_assets as $asset)
		{
			$sql = "SELECT id,asset_name,asset_amount,asset_price,asset_tvl,timestamp FROM crosschain_stats WHERE asset_name='".$asset."'  AND chain='wan' ORDER BY timestamp DESC LIMIT 1";
			$row = $this->db->query($sql)->row_array();
			
			// Get 24 hours //
			$sql = "SELECT asset_amount FROM crosschain_stats WHERE asset_name='".$asset."'  AND chain='wan' AND timestamp >= '".date('Y-m-d H:i:s',time()-86400)."'  ORDER BY timestamp ASC LIMIT 1";
			$row2 = $this->db->query($sql)->row_array();
			$row['last_24hrs_amount'] = $row2['asset_amount'];
			
			// Get 7 Days //
			$sql = "SELECT asset_amount FROM crosschain_stats WHERE asset_name='".$asset."'  AND chain='wan' AND timestamp >= '".date('Y-m-d H:i:s',time()-604800)."'  ORDER BY timestamp ASC LIMIT 1";
			$row2 = $this->db->query($sql)->row_array();
			$row['last_7days_amount'] = $row2['asset_amount'];
			
			// Get 30 days //
			$sql = "SELECT asset_amount FROM crosschain_stats WHERE asset_name='".$asset."'  AND chain='wan' AND timestamp >= '".date('Y-m-d H:i:s',time()-2592000)."'  ORDER BY timestamp ASC LIMIT 1";
			$row2 = $this->db->query($sql)->row_array();
			$row['last_30days_amount'] = $row2['asset_amount'];
			
			$view['wanchain_tvl'] += $row['asset_tvl'];
			$view['wanchain_stats'][] = $row;
		}
		
		
		uasort($view['wanchain_stats'], function ($a, $b) {
             return $b['asset_tvl'] - $a['asset_tvl'];
        });
		
		$view['ethereum_tvl'] = 0;
		foreach($ethereum_assets as $asset)
		{
			$sql = "SELECT id,asset_name,asset_amount,asset_price,asset_tvl,timestamp FROM crosschain_stats WHERE asset_name='".$asset."'  AND chain='eth' ORDER BY timestamp DESC LIMIT 1";
			$row = $this->db->query($sql)->row_array();
			
			// Get 24 hours //
			$sql = "SELECT asset_amount FROM crosschain_stats WHERE asset_name='".$asset."'  AND chain='eth' AND timestamp >= '".date('Y-m-d H:i:s',time()-86400)."'  ORDER BY timestamp ASC LIMIT 1";
			$row2 = $this->db->query($sql)->row_array();
			$row['last_24hrs_amount'] = $row2['asset_amount'];
			
			// Get 7 Days //
			$sql = "SELECT asset_amount FROM crosschain_stats WHERE asset_name='".$asset."'  AND chain='eth' AND timestamp >= '".date('Y-m-d H:i:s',time()-604800)."'  ORDER BY timestamp ASC LIMIT 1";
			$row2 = $this->db->query($sql)->row_array();
			$row['last_7days_amount'] = $row2['asset_amount'];
			
			// Get 30 days //
			$sql = "SELECT asset_amount FROM crosschain_stats WHERE asset_name='".$asset."'  AND chain='eth' AND timestamp >= '".date('Y-m-d H:i:s',time()-2592000)."'  ORDER BY timestamp ASC LIMIT 1";
			$row2 = $this->db->query($sql)->row_array();
			$row['last_30days_amount'] = $row2['asset_amount'];
			
			
			$view['ethereum_tvl'] += $row['asset_tvl'];
			$view['ethereum_stats'][] = $row;
		}
		uasort($view['ethereum_stats'], function ($a, $b) {
             return $b['asset_tvl'] - $a['asset_tvl'];
        });
		
		
		
		
		$view['wanchain_asset_count'] = count($wanchain_assets);
		$view['ethereum_asset_count'] = count($ethereum_assets);
		$view['web_title'] = 'CONVERTED ASSETS';
        $this->load->view('token',$view);
	}
	
	// Cron call for pre-cached
	function sync_wasp()
	{
		$this->_getTokenBalance('0x29239a9B93A78decEc6E0Dd58ddBb854B7fFB0af','0x8b9f9f4aa70b1b0d586be8adfb19c1ac38e05e9a');
		sleep(1);
		$this->_getTokenBalance('0x29239a9B93A78decEc6E0Dd58ddBb854B7fFB0af','0xdabd997ae5e4799be47d6e69d9431615cba28f48');
		sleep(1);
		$this->_getTokenSupply('0x8b9f9f4aa70b1b0d586be8adfb19c1ac38e05e9a','WAN');
		sleep(1);
		$this->_getTokenBalance('0x7e5Fe1E587a5C38b4a4a9BA38A35096f8ea35AAc','0x8b9f9f4aa70b1b0d586be8adfb19c1ac38e05e9a');
		sleep(1);
		$this->_getTokenBalance('0x0000000000000000000000000000000000000001','0x8b9f9f4aa70b1b0d586be8adfb19c1ac38e05e9a');
		sleep(1);
		$this->_getTokenBalance('0x93f98C2216B181846e1C92e7Deb06911373e1f37','0x8b9f9f4aa70b1b0d586be8adfb19c1ac38e05e9a');
		sleep(1);
		$this->_getTokenSupply('0xDABd997Ae5e4799be47D6e69d9431615cbA28F48','WAN');
	}
	
	function wasp()
	{
		//$this->output->cache(10);
		function custom_format($number)
		{
			$tmp = floor($number);
			$digit = $number - $tmp;
			$tmp = number_format($tmp);
			return $tmp.'.'.substr(str_replace('0.','',$digit.''),0,4);
		}
		$price = json_decode($this->_getprice(),true);
		$token0 = $this->_getTokenBalance('0x29239a9B93A78decEc6E0Dd58ddBb854B7fFB0af','0x8b9f9f4aa70b1b0d586be8adfb19c1ac38e05e9a')/WAN_DIGIT;
		
		$token1 = $this->_getTokenBalance('0x29239a9B93A78decEc6E0Dd58ddBb854B7fFB0af','0xdabd997ae5e4799be47d6e69d9431615cba28f48')/WAN_DIGIT;
		
		$wasp_supply = $this->_getTokenSupply('0x8b9f9f4aa70b1b0d586be8adfb19c1ac38e05e9a','WAN')/WAN_DIGIT;
		
		$unclaim = $this->_getTokenBalance('0x7e5Fe1E587a5C38b4a4a9BA38A35096f8ea35AAc','0x8b9f9f4aa70b1b0d586be8adfb19c1ac38e05e9a')/WAN_DIGIT;
		
		$burned = $this->_getTokenBalance('0x0000000000000000000000000000000000000001','0x8b9f9f4aa70b1b0d586be8adfb19c1ac38e05e9a')/WAN_DIGIT;
		
		// HIVE //
		$wasp_hive = $this->_getTokenBalance('0x93f98C2216B181846e1C92e7Deb06911373e1f37','0x8b9f9f4aa70b1b0d586be8adfb19c1ac38e05e9a')/WAN_DIGIT;
		
		
		
		$wwan_supply = $this->_getTokenSupply('0xDABd997Ae5e4799be47D6e69d9431615cbA28F48','WAN')/WAN_DIGIT;
		
		$rate = $token0/$token1;
		$wan_reflect = $token0/$rate;
		$view['wasp_price'] = number_format($price['WAN']['USD']/$rate,4);
		
		$view['wasp_supply'] = number_format($wasp_supply);
		$view['wasp_unclaimed'] = number_format($unclaim);
		$view['wasp_burned'] = number_format($burned);
		$view['wasp_burned_percent'] = number_format($burned*100/$wasp_supply,2);
		$view['wasp_hive'] = number_format($wasp_hive);
		$view['wasp_hive_size'] = number_format($wasp_hive*$view['wasp_price']);
		$view['wasp_hive_percent'] = number_format($wasp_hive*100/$wasp_supply,2);
		
		$view['exchange_rate'] = number_format($token0/$token1,2);
		
		$view['pool_size'] = number_format($wan_reflect*$price['WAN']['USD']+$token1*$price['WAN']['USD']);
		$view['pool_wasp'] = number_format($token0);
		$view['pool_wasp_percentage'] = number_format($token0*100/$wasp_supply,2);
		$view['pool_wan'] = number_format($token1);
		$view['pool_wan_percentage'] = number_format($token1*100/$wwan_supply,2);
		$view['timestamp'] = date('Y-m-d H:i',time()).'Z';
		
		// Get Chart data //
		$this->load->database();
		$sql ='SELECT id, MAX( wasp_price ) as wasp_price , SUM( volume_changed ) as volume_changed , timestamp
FROM wasp_stats
GROUP BY HOUR( timestamp ) ORDER BY id ASC';
//$sql ='SELECT exchange_rate, wasp_price, volume_changed , timestamp FROM wasp_stats';
		$query = $this->db->query($sql);
		$chart_data = $query->result_array();
		$view['chart_data'] = $chart_data;
		
		// Gett MAX MIN AVG //
		$sql ='SELECT MAX(wasp_price) as max_price, MIN(wasp_price) as min_price, AVG(wasp_price) as avg_price, SUM(volume_changed) as sum_volume FROM wasp_stats';
		$query = $this->db->query($sql);
		$day_summary = $query->row_array();
		$view['day_summary'] = $day_summary;
		$view['web_title'] = '$WASP TOKEN';
        $this->load->view('wasp',$view);
	}
	
}