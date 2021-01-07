<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use WebSocket\Client;
class Wanswap_api extends CI_Controller {
	
	 public $client = null;
	 public function __construct()
	 {
			parent::__construct();
			// Your own constructor code
			$this->client = new Client($this->config->item('iwan_client'));
	 }
	
	private function _getTokenSupply($address,$chain='ETH')
    {
        
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);
        $this->load->driver('cache', array('adapter' => 'file'));
        $method = 'getTokenSupply';
        if (!$result = $this->cache->get('API_'.$method.'_'.$address))
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

			try
			{
				if (isset($result['result']) && $result['result']) {
					$result = $result['result'];
					$this->cache->save('API_'.$method.'_'.$address, $result, 45); // 60 SECS
				} else {
					return 0;
				}
			}
			catch(Exception $e)
			{
				return 0;
			}
        }
        return $result;
    }
	
	private function _getTokenBalance($address,$scAddress)
    {
		//$this->client = new Client($this->config->item('iwan_client'));
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);
        $this->load->driver('cache', array('adapter' => 'file'));
        $method = 'getTokenBalance';
        if (!$result = $this->cache->get('API_'.$method.'_'.md5($address.$scAddress)))
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

			try
			{
				if (isset($result['result']) && $result['result']) {
					$result = $result['result'];
					$this->cache->save('API_'.$method.'_'.md5($address.$scAddress), $result, 45); // 60 SECS
				} else {
					return 0;
				}
			}
			catch(Exception $e)
			{
				return 0;
			}
        }
        return $result;
    }
	private function _pair_list()
	{
		// WASP-WAN //
		$list[0] = array(
			'pair_address'=>'0x29239a9b93a78decec6e0dd58ddbb854b7ffb0af',
			'base_name'=>'WAN',
			'base_symbol'=>'WAN',
			'base_address'=>'0xdabd997ae5e4799be47d6e69d9431615cba28f48',
			'base_decimal'=>WAN_DIGIT,
			'quote_name'=>'WASP',
			'quote_symbol'=>'WASP',
			'quote_address'=>'0x8b9f9f4aa70b1b0d586be8adfb19c1ac38e05e9a',
			'quote_decimal'=>WAN_DIGIT,
		);
		
		// wanUSDT-WAN //
		$list[1] = array(
			'pair_address'=>'0x0a886dc4d584d55e9a1fa7eb0821762296b4ec0e',
			'base_name'=>'WAN',
			'base_symbol'=>'WAN',
			'base_address'=>'0xdabd997ae5e4799be47d6e69d9431615cba28f48',
			'base_decimal'=>WAN_DIGIT,
			'quote_name'=>'Wrapped Tether USD@Wanchain',
			'quote_symbol'=>'wanUSDT',
			'quote_address'=>'0x11e77e27af5539872efed10abaa0b408cfd9fbbd',
			'quote_decimal'=>1000000,
		);
		
		// wanEOS-WAN //
		$list[2] = array(
			'pair_address'=>'0xb0f36b469dda3917abbc8520f4cf80a5d1e9e9e2',
			'base_name'=>'WAN',
			'base_symbol'=>'WAN',
			'base_address'=>'0xdabd997ae5e4799be47d6e69d9431615cba28f48',
			'base_decimal'=>WAN_DIGIT,
			'quote_name'=>'Wrapped EOS@Wanchain',
			'quote_symbol'=>'wanEOS',
			'quote_address'=>'0x81862B7622ceD0deFb652aDDD4E0C110205b0040',
			'quote_decimal'=>10000,
		);
		
		// wanBTC-WAN //
		$list[3] = array(
			'pair_address'=>'0x1b430c10cce0ee6c544175be66cbe9f738d946bc',
			'base_name'=>'WAN',
			'base_symbol'=>'WAN',
			'base_address'=>'0xdabd997ae5e4799be47d6e69d9431615cba28f48',
			'base_decimal'=>WAN_DIGIT,
			'quote_name'=>'Wrapped BTC@Wanchain',
			'quote_symbol'=>'wanBTC',
			'quote_address'=>'0xd15e200060fc17ef90546ad93c1c61bfefdc89c7',
			'quote_decimal'=>100000000,
		);
		
		// wanETH-WAN //
		$list[4] = array(
			'pair_address'=>'0xb1b5dada5795f174f1f62ede70edb4365fb07fb1',
			'base_name'=>'WAN',
			'base_symbol'=>'WAN',
			'base_address'=>'0xdabd997ae5e4799be47d6e69d9431615cba28f48',
			'base_decimal'=>WAN_DIGIT,
			'quote_name'=>'Wrapped ETH@Wanchain',
			'quote_symbol'=>'wanETH',
			'quote_address'=>'0xe3ae74d1518a76715ab4c7bedf1af73893cd435a',
			'quote_decimal'=>WAN_DIGIT,
		);
		
		// FNX-WAN //
		$list[5] = array(
			'pair_address'=>'0x4bbbaaa14725d157bf9dde1e13f73c3f96343f3d',
			'base_name'=>'WAN',
			'base_symbol'=>'WAN',
			'base_address'=>'0xdabd997ae5e4799be47d6e69d9431615cba28f48',
			'base_decimal'=>WAN_DIGIT,
			'quote_name'=>'FNX@Wanchain',
			'quote_symbol'=>'FNX',
			'quote_address'=>'0xc6f4465a6a521124c8e3096b62575c157999d361',
			'quote_decimal'=>WAN_DIGIT,
		);

		return $list;
		
	}
	private function _getprice()
	{
		// connect via SSL, but don't check cert
		$handle=curl_init('https://min-api.cryptocompare.com/data/pricemulti?fsyms=WAN&tsyms=USD');
		curl_setopt($handle, CURLOPT_VERBOSE, true);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		$content = curl_exec($handle);

		return $content; // show target page
	}
	
	public function test()
	{
		set_time_limit (360);
		// wanBTC //
		for($i=1;$i<=100;$i++)
		{
			$amount = $this->_getTokenSupply('0xD15E200060Fc17ef90546ad93c1C61BfeFDC89C7','WAN')/100000000;
			echo $amount;
		
		}
	}
	
	
	public function sync_wasp_stat()
	{
		$list = $this->_pair_list();
		$this->load->database();
		$pair = $list[0];
		
		$price = json_decode($this->_getprice(),true);
		$timestamp = date('Y-m-d H:i',time());
		
		$wasp_amount = $this->_getTokenBalance($pair['pair_address'],$pair['quote_address'])/$pair['quote_decimal'];
		$wan_amount = $this->_getTokenBalance($pair['pair_address'],$pair['base_address'])/$pair['base_decimal'];
		if ($wasp_amount==0 || $wan_amount == 0)
		{
			$this->client->close();
			die();
		}
		
		
		$exchange_rate = $wasp_amount/$wan_amount; // to WAN
		
		
		
		// Get Lastest Wasp amount //
		$wasp_lastest_amount = $this->db->select('wasp_amount')->order_by('id', 'desc')->limit(1)->get('wasp_stats')->row_array();
		$wasp_lastest_amount = $wasp_lastest_amount['wasp_amount'];
		
		$volume_changed = abs(round($wasp_lastest_amount,18)-round($wasp_amount,18));
		
		
		if ($exchange_rate == 1)
		{
			$this->client->close();
			die();
		}
		if ($wasp_amount == $wan_amount)
		{
			$this->client->close();
			die();
		}
		
		
		$this->db->insert('wasp_stats',array(
			'wasp_amount'=>$wasp_amount,
			'wan_amount'=>$wan_amount,
			'exchange_rate'=>$exchange_rate,
			'wasp_price'=>$price['WAN']['USD']/$exchange_rate,
			'volume_changed'=>$volume_changed,
			'timestamp'=>$timestamp,
		));
		
		$this->client->close();
		die();
	}
	
	public function cmc()
	{
		error_reporting(0);
		$list = $this->_pair_list();
		
		$api_result = array();
		foreach($list as $pair)
		{
			$token0 = $this->_getTokenBalance($pair['pair_address'],$pair['quote_address'])/$pair['quote_decimal'];
			$token1 = $this->_getTokenBalance($pair['pair_address'],$pair['base_address'])/$pair['base_decimal'];
			if ($token0 == 0 || $token1 == 0) continue;
			$exchange_rate = $token0/$token1;
			$api_result[$pair['pair_address']] = array(
				'base_id'=>$pair['base_address'],
				'base_name'=>$pair['base_name'],
				'base_symbol'=>$pair['base_symbol'],
				'quote_id'=>$pair['quote_address'],
				'quote_name'=>$pair['quote_name'],
				'quote_symbol'=>$pair['quote_symbol'],
				'last_price'=>$exchange_rate,
				'base_volume'=>$token1,
				'quote_volume'=>$token0,
			);
		}
		header('Content-Type: application/json');
		echo json_encode($api_result);
		$this->client->close();
	}
	
	
}