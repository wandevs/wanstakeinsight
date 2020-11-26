<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use WebSocket\Client;
class Token extends CI_Controller {
	
	private function _getTokenSupply($address,$chain='ETH')
    {
        $client = new Client($this->config->item('iwan_client'));
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

            $client->send($query_string);
            $result = json_decode($client->receive(), true);

            if (isset($result['result']) && $result['result']) {

                $result = $result['result'];

                $this->cache->save($method.'_'.$address, $result, 360); // 1 hour
            } else {
                $result = '';
                $this->output->delete_cache();
            }
        }
        return $result;
    }
	
	
	
	public function seeAPI()
	{
		?>
		<style>
		body
		{
			background:#f4f3ef;
			padding:50px;
		}
	table.cinereousTable {
		
  border: 6px solid #948473;
  background-color: #FFE3C6;
  width: 50%;
  text-align: center;
  font-family:verdana;
}
table.cinereousTable td, table.cinereousTable th {
  border: 1px solid #948473;
  padding: 4px 4px;
}
table.cinereousTable tbody td {
  font-size: 24px;
}
table.cinereousTable thead {
  background: #948473;
  background: -moz-linear-gradient(top, #afa396 0%, #9e9081 66%, #948473 100%);
  background: -webkit-linear-gradient(top, #afa396 0%, #9e9081 66%, #948473 100%);
  background: linear-gradient(to bottom, #afa396 0%, #9e9081 66%, #948473 100%);
  
}
table.cinereousTable thead th {
  font-size: 24px;
  font-weight: bold;
  color: #F0F0F0;
  text-align: left;
  border-left: 2px solid #948473;
}
table.cinereousTable thead th:first-child {
  border-left: none;
}

		</style>
		<?php
		
		function custom_format($number)
		{
			$tmp = floor($number);
			$digit = $number - $tmp;
			$tmp = number_format($tmp);
			return $tmp.'.'.substr(str_replace('0.','',$digit.''),0,4);
		}
		
		echo '<table class="cinereousTable">';
		echo '<tr><td colspan="2" style="text-align:center;font-weight:bold">ERC20 Tokens@Ethereum</td></tr>';
		echo '<tr><td><b>WAN</b></td><td>'.custom_format($this->_getTokenSupply('0x135B810e48e4307AB2a59ea294A6f1724781bD3C')/WAN_DIGIT).'</td></tr>';
		echo '<tr><td><b>wanBTC</b></td><td>'.custom_format($this->_getTokenSupply('0x058a55925627980dbb6d6d39f8dad5de5be16764')/10000000).'</td></tr>';
		echo '<tr><td><b>wanEOS</b></td><td>'.custom_format($this->_getTokenSupply('0x11167f7889ae34E2C6b15c9226D0b320C45d629D')/10000).'</td></tr>';
		echo '</table><br/><br/>';
		
		echo '<table class="cinereousTable">';
		echo '<tr><td colspan="2" style="text-align:center;font-weight:bold">WRC20 Tokens@Wanchain</td></tr>';
		echo '<tr><td><b>FNX</b></td><td>'.custom_format($this->_getTokenSupply('0xc6f4465a6A521124c8E3096b62575C157999d361','WAN')/WAN_DIGIT).'</td></tr>';
		
		echo '<tr><td><b>wanETH</b></td><td>'.custom_format($this->_getTokenSupply('0xe3Ae74d1518a76715Ab4c7bEdf1AF73893CD435a','WAN')/WAN_DIGIT).'</td></tr>';
		echo '<tr><td><b>wanBTC</b></td><td>'.custom_format($this->_getTokenSupply('0xD15E200060Fc17ef90546ad93c1C61BfeFDC89C7','WAN')/10000000).'</td></tr>';
		echo '<tr><td><b>wanEOS</b></td><td>'.custom_format($this->_getTokenSupply('0x81862B7622ceD0deFb652aDDD4E0C110205b0040','WAN')/10000).'</td></tr>';
		
		echo '<tr><td><b>wanUSDT</b></td><td>'.custom_format($this->_getTokenSupply('0x11E77e27aF5539872EFeD10ABAa0B408CFD9Fbbd','WAN')/1000000).'</td></tr>';
		
		echo '</table>';
		
		
	}
	
	public function index()
	{
		//echo '<pre>';
		$groupId = '0x000000000000000000000000000000000000000000000041726965735f303031';
		$groupName = 'Aries_001';
		$storemen = $this->_getStoremanGroupMember($groupId);
		$total_selfstaked = 0;
		$total_deposit = 0;
		$total_partnerDeposit = 0;
		
		$total_delegated = 0;
		$delegated_count = 0;
		$highest_delegated = 0;
		$avg_delegated = array();
		
		foreach($storemen as $storeman)
		{
			$total_deposit += $storeman['deposit'];
			$total_partnerDeposit += $storeman['partnerDeposit'];
			$total_selfstaked += $storeman['deposit']+$storeman['partnerDeposit'];
			$total_delegated += $storeman['delegateDeposit'];
			$delegated_count += $storeman['delegatorCount'];
			$avg_delegated[] = $storeman['delegateDeposit'];
			if ($storeman['delegateDeposit'] > $highest_delegated)
			{
				$highest_delegated = $storeman['delegateDeposit'];
			}
		}
		$view['groupId'] = $groupId ;
		$view['groupName'] = $groupName ;
		$view['web_title'] = 'STOREMEN';
		$view['storemen'] = $storemen;
		$view['total_selfstaked'] = $total_selfstaked/ WAN_DIGIT;
		$view['total_deposit'] = $total_deposit/ WAN_DIGIT;
		$view['total_partnerDeposit'] = $total_partnerDeposit/ WAN_DIGIT;
		$view['total_delegated'] = $total_delegated/ WAN_DIGIT;
		$view['delegated_count'] = $delegated_count;
		$view['highest_delegated'] = $highest_delegated/ WAN_DIGIT;
		$view['avg_delegated'] = round(array_sum(array_filter($avg_delegated))/count($avg_delegated)/ WAN_DIGIT);
		
        $this->load->view('storeman',$view);
	}
	
}