<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use WebSocket\Client;
class Storeman extends CI_Controller {
	
	public $client = null;
	 public function __construct()
	 {
			parent::__construct();
			// Your own constructor code
			
			$this->client = new Client($this->config->item('iwan_client'));
	 }
	
	
	private function _getStoremanGroupList()
    {
        
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);
        $this->load->driver('cache', array('adapter' => 'file'));
        $method = 'getStoremanGroupList';
        if (!$result = $this->cache->get($method))
        {
            $params_array = array(
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

                $this->cache->save($method, $result, 360); // 1 hour
            } else {
                $result = '';
                $this->output->delete_cache();
            }
        }
        return $result;
    }
	
	private function _getStoremanGroupMember($groupId)
    {
        
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);
        $this->load->driver('cache', array('adapter' => 'file'));
        $method = 'getStoremanGroupMember';
        if (!$result = $this->cache->get($method.'_'.$groupId))
        {
            $params_array = array(
				'groupId' => $groupId,
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

                $this->cache->save($method.'_'.$groupId, $result, 600); // 10 mins
            } else {
                $result = '';
                $this->output->delete_cache();
            }
        }
        return $result;
    }
	
	private function _getStoremanGroupInfo($groupId)
    {
        
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);
        $this->load->driver('cache', array('adapter' => 'file'));
        $method = 'getStoremanGroupInfo';
        if (!$result = $this->cache->get($method.'_'.$groupId))
        {
            $params_array = array(
				'groupId' => $groupId,
				
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

                //$this->cache->save($method, $result, 360); // 1 hour
            } else {
                $result = '';
                $this->output->delete_cache();
            }
        }
        return $result;
    }
	
	private function _getStoremanStakeTotalIncentive()
    {
        
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);
        $this->load->driver('cache', array('adapter' => 'file'));
        $method = 'getStoremanStakeTotalIncentive';
        if (!$result = $this->cache->get($method))
        {
            $params_array = array(
				'wkAddr' => '0x92Dce4f5857CAD9208A2f168445e3670D4f84d74',
				'toBlock' => 11765038,
				
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

                //$this->cache->save($method, $result, 360); // 1 hour
            } else {
                $result = '';
                $this->output->delete_cache();
            }
        }
        return $result;
    }
	
	
	private function _getRewardRatio()
    {
        
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);
        $this->load->driver('cache', array('adapter' => 'file'));
        $method = 'getRewardRatio';
        if (!$result = $this->cache->get($method))
        {
            $params_array = array(
				//'wkAddr' => '0x92Dce4f5857CAD9208A2f168445e3670D4f84d74',
				//'toBlock' => 11765038,
				
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

                //$this->cache->save($method, $result, 360); // 1 hour
            } else {
                $result = '';
                $this->output->delete_cache();
            }
        }
        return $result;
    }
	
	public function seeAPI()
	{
		//echo (1224387189358602196)/WAN_DIGIT;
		echo '<pre>';
		//print_r($this->_getStoremanGroupInfo('0x000000000000000000000000000000000000000000000041726965735f303031'));
		//print_r($this->_getStoremanStakeTotalIncentive());
		//print_r($this->_getStoremanGroupMember('0x000000000000000000000000000000000000000000000041726965735f303031'));
		echo $this->_getRewardRatio();
		
		
	}
	
	public function sync_storeman()
	{
		$groupId = '0x000000000000000000000000000000000000000000000041726965735f303031';
		$this->_getStoremanGroupMember($groupId);
	}
	
	public function index()
	{
		$this->output->cache(15);
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