<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use WebSocket\Client;
class Wanswap_api extends CI_Controller {
	
	 public $client = null;
	 public $idx = 0;
	 public function __construct()
	 {
			parent::__construct();
			// Your own constructor code
			$this->client = new Client($this->config->item('iwan_client'));
			$this->idx = rand(1,1000000000);
	 }
	 
	 
	
	private function _getTokenSupply($address,$chain='ETH')
    {
        $this->idx++;
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
                'id' => $this->idx,
            );
            $signature = base64_encode(hash_hmac('sha256', json_encode($signature_message), $secret, true));
            $params_array["signature"] = $signature;
            $query_array = array(
                'jsonrpc' => '2.0',
                'method' => $method,
                'params' => $params_array,
                'id' => $this->idx,
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
					$this->client = new Client($this->config->item('iwan_client'));
					$this->idx = rand(1,1000000000);
					return 0;
				}
			}
			catch(Exception $e)
			{
				$this->client = new Client($this->config->item('iwan_client'));
				$this->idx = rand(1,1000000000);
				return 0;
			}
        }
        return $result;
    }
	
	private function _getTokenBalance($address,$scAddress)
    {
		//$this->client = new Client($this->config->item('iwan_client'));
		$this->idx++;
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
                'id' => $this->idx,
            );
            $signature = base64_encode(hash_hmac('sha256', json_encode($signature_message), $secret, true));
            $params_array["signature"] = $signature;
            $query_array = array(
                'jsonrpc' => '2.0',
                'method' => $method,
                'params' => $params_array,
                'id' => $this->idx,
            );

            $query_string = json_encode($query_array);

            $this->client->send($query_string);
			//echo $this->client->receive();
            $result = json_decode($this->client->receive(), true);

			try
			{
				if (isset($result['result']) && $result['result']) {
					$result = $result['result'];
					$this->cache->save('API_'.$method.'_'.md5($address.$scAddress), $result, 45); // 60 SECS
				} else {
					$this->client = new Client($this->config->item('iwan_client'));
					$this->idx = rand(1,1000000000);
					return 0;
				}
			}
			catch(Exception $e)
			{
				$this->client = new Client($this->config->item('iwan_client'));
				$this->idx = rand(1,1000000000);
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
		
		// wanBTC-wanETH //
		$list[6] = array(
			'pair_address'=>'0x8663aa051be78cc78a54fe111eb308450fa17f03',
			'base_name'=>'Wrapped ETH@Wanchain',
			'base_symbol'=>'wanETH',
			'base_address'=>'0xe3ae74d1518a76715ab4c7bedf1af73893cd435a',
			'base_decimal'=>WAN_DIGIT,
			'quote_name'=>'Wrapped BTC@Wanchain',
			'quote_symbol'=>'wanBTC',
			'quote_address'=>'0xd15e200060fc17ef90546ad93c1c61bfefdc89c7',
			'quote_decimal'=>100000000,
		);
		
		// wanUSDT-wanUSDC //
		$list[7] = array(
			'pair_address'=>'0x22d41262d4587ab2ac32d67cfeef7449d566920d',
			'base_name'=>'Wrapped USD Coin@Wanchain',
			'base_symbol'=>'wanUSDC',
			'base_address'=>'0x52a9cea01c4cbdd669883e41758b8eb8e8e2b34b',
			'base_decimal'=>1000000,
			'quote_name'=>'Wrapped Tether USD@Wanchain',
			'quote_symbol'=>'wanUSDT',
			'quote_address'=>'0x11e77e27af5539872efed10abaa0b408cfd9fbbd',
			'quote_decimal'=>1000000,
		);
		
		// wanLINK-WASP //
		$list[8] = array(
			'pair_address'=>'0x517fdb64a96addff784773fea9d222fa1bd0c342',
			'base_name'=>'WASP',
			'base_symbol'=>'WASP',
			'base_address'=>'0x8b9f9f4aa70b1b0d586be8adfb19c1ac38e05e9a',
			'base_decimal'=>WAN_DIGIT,
			'quote_name'=>'Wrapped Chainlink@Wanchain',
			'quote_symbol'=>'wanLINK',
			'quote_address'=>'0x06DA85475F9d2Ae79af300dE474968cd5A4FDE61',
			'quote_decimal'=>WAN_DIGIT,
		);
		
		// wanUNI-WASP //
		$list[9] = array(
			'pair_address'=>'0xac8cd8ff1379af20b69e0b39253ad3d5ab2051b5',
			'base_name'=>'WASP',
			'base_symbol'=>'WASP',
			'base_address'=>'0x8b9f9f4aa70b1b0d586be8adfb19c1ac38e05e9a',
			'base_decimal'=>WAN_DIGIT,
			'quote_name'=>'Wrapped Uniswap@Wanchain',
			'quote_symbol'=>'wanUNI',
			'quote_address'=>'0x73eAA7431b11B1E7a7D5310de470de09883529df',
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
			// BTC //
			$amount = $this->_getTokenSupply('0xD15E200060Fc17ef90546ad93c1C61BfeFDC89C7','WAN')/100000000;
			echo $amount.'<br>';
			// EOS //
			$amount = $this->_getTokenSupply('0x81862B7622ceD0deFb652aDDD4E0C110205b0040','WAN')/10000;
			echo $amount.'<br>';
			// BTC @ ETH //
			$amount = $this->_getTokenSupply('0x058a55925627980dbb6d6d39f8dad5de5be16764','ETH')/100000000;
			echo $amount.'<br>';
		
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
		if (floor($wasp_amount)<=0 || floor($wan_amount) <= 0)
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

	public function wasp_supply()
	{
		$wasp_supply = $this->_getTokenSupply('0x8b9f9f4aa70b1b0d586be8adfb19c1ac38e05e9a','WAN')/WAN_DIGIT;
		if (floor($wasp_supply) == 0)die();
		header('Content-Type: application/json');
		echo json_encode(array('wasp_supply'=>$wasp_supply));
	}
	public function cmc()
	{
		error_reporting(0);
		$list = $this->_pair_list();
		
		$api_result = array();
		$this->load->driver('cache', array('adapter' => 'file'));
		$count_pair = count($list);
		$this->load->database();
		foreach($list as $pair)
		{
			
			
			$token0 = $this->_getTokenBalance($pair['pair_address'],$pair['quote_address'])/$pair['quote_decimal'];
			$token1 = $this->_getTokenBalance($pair['pair_address'],$pair['base_address'])/$pair['base_decimal'];
			if (floor($token0) <= 0 || floor($token1) <= 0) continue;
			if (floor($token0) >= 999999999999 || floor($token1) >= 999999999999)  continue;
			
			
			
			$exchange_rate = $token0/$token1;
			
			
			
            if (!$previous_stat = $this->cache->get('wanswap_pair_'.$pair['pair_address']))
			{
				$this->cache->save('wanswap_pair_'.$pair['pair_address'], $exchange_rate, 86400); // 1 days
			}
			else
			{
				$percentChange = (1 - $previous_stat / $exchange_rate) * 100;
				
				
				if (floor(abs($percentChange)) < 20)
				{
				
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
				
				$api_result[$pair['pair_address']]['percent_diff'] = abs($percentChange);
				$this->cache->save('wanswap_pair_'.$pair['pair_address'], $exchange_rate, 86400); // 1 days
				}
			}
		}
		
		if ($count_pair != count($api_result))
		{
			die();
		}
		
		else{
			if (rand(1,4) == 1)
				{
					foreach($api_result as $result)
					{
						if (rand(1,12) == 1)
						{
							$this->db->replace('wanswap_stats',array(
								'base_symbol'=>$result['base_symbol'],
								'quote_symbol'=>$result['quote_symbol'],
								'base_volume'=>$result['base_volume'],
								'quote_volume'=>$result['quote_volume'],
								'last_price' =>$result['last_price'],
								'timestamp'=>date('Y-m-d H:i:s')
							));
						}

					}
				}
		}
		
		
		header('Content-Type: application/json');
		echo json_encode($api_result);
		$this->client->close();
	}
	
	
	
	private function _getprice_pair()
	{
		// connect via SSL, but don't check cert
		$handle = curl_init('https://min-api.cryptocompare.com/data/pricemulti?fsyms=BTC,ETH,EOS,USDT,USDC,WAN,FNX,LINK,UNI&tsyms=USD');
		curl_setopt($handle, CURLOPT_VERBOSE, true);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		$content = curl_exec($handle);

		return $content; // show target page
	}
	public function get_tvl()
	{
		$price = json_decode($this->_getprice_pair(),TRUE);
		$list = $this->_pair_list();
		$this->load->database();
		$all_pair_supply = array();
		$wasp_rate = 0;
		foreach($list as $pair)
		{
			$last_result = $this->db->select('quote_volume,base_volume,last_price')
					->where('quote_symbol',$pair['quote_symbol'])
					->where('base_symbol',$pair['base_symbol'])
					->get('wanswap_stats')->row_array();
					
			if($pair['quote_symbol'] == 'WASP')
			$wasp_rate = $last_result['last_price'];
			
			if (!isset($all_pair_supply[$pair['quote_symbol']])) $all_pair_supply[$pair['quote_symbol']] = 0;
			$all_pair_supply[$pair['quote_symbol']] += $last_result['quote_volume'];	
			
			if (!isset($all_pair_supply[$pair['base_symbol']])) $all_pair_supply[$pair['base_symbol']] = 0;
			$all_pair_supply[$pair['base_symbol']] += $last_result['base_volume'];	
			
		}
		
		//print_r($price);
		$sum_tvl = 0;
		foreach ($all_pair_supply as $symbol=>$amount)
		{
			//echo str_replace('wan','',$symbol);
			if ($symbol == 'WASP')
			{
				$sum_tvl += $amount*($price['WAN']['USD']/$wasp_rate);
				//echo $symbol.': '.$amount*($price['WAN']['USD']/$wasp_rate).'<br/>';
				continue;
			}
			$sum_tvl += $amount*($price[str_replace('wan','',$symbol)]['USD']);
			//echo $symbol.': '.$amount*($price[str_replace('wan','',$symbol)]['USD']).'<br/>';
		}
		header('Content-Type: application/json');
		echo json_encode(array('result'=>round($sum_tvl,2)));
		
		
	}
	
	
	public function tickers()
	{
		$list = $this->_pair_list();
		$this->load->database();
		foreach($list as $pair)
		{
			
			$pairs[] = array(
				'ticker_id'=>$pair['quote_symbol'].'_'.$pair['base_symbol'],
				'base'=>$pair['base_symbol'],
				'target'=>$pair['quote_symbol']
			);
		}
	}
	public function pairs()
	{
		$list = $this->_pair_list();
		$pairs = array();
		foreach($list as $pair)
		{
			$pairs[] = array(
				'ticker_id'=>$pair['quote_symbol'].'_'.$pair['base_symbol'],
				'base'=>$pair['base_symbol'],
				'target'=>$pair['quote_symbol']
			);
		}
		header('Content-Type: application/json');
		echo json_encode($pairs);
	}
	
}