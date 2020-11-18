<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use WebSocket\Client;
class Storeman extends CI_Controller {
	
	private function _getStoremanGroupList()
    {
        $client = new Client($this->config->item('iwan_client'));
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

            $client->send($query_string);
            $result = json_decode($client->receive(), true);

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
        $client = new Client($this->config->item('iwan_client'));
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

            $client->send($query_string);
            $result = json_decode($client->receive(), true);

            if (isset($result['result']) && $result['result']) {

                $result = $result['result'];

                $this->cache->save($method.'_'.$groupId, $result, 360); // 1 hour
            } else {
                $result = '';
                $this->output->delete_cache();
            }
        }
        return $result;
    }
	
	private function _getStoremanGroupInfo($groupId)
    {
        $client = new Client($this->config->item('iwan_client'));
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

            $client->send($query_string);
            $result = json_decode($client->receive(), true);

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
        $client = new Client($this->config->item('iwan_client'));
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);
        $this->load->driver('cache', array('adapter' => 'file'));
        $method = 'getStoremanStakeTotalIncentive';
        if (!$result = $this->cache->get($method))
        {
            $params_array = array(
				//'wkAddr' => '0x5C770cBf582D770b93cA90AdaD7E6BD33fAbC44C',
				//'fromBlock' => 1604635200,
				//'toBlock' => 1605240000,
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
		print_r($this->_getStoremanGroupInfo('0x000000000000000000000000000000000000000000746573746e65745f303038'));
		print_r($this->_getStoremanStakeTotalIncentive());
		print_r($this->_getStoremanGroupMember('0x000000000000000000000000000000000000000000746573746e65745f303038'));
		
		
		
	}
	
	public function index()
	{
		//echo '<pre>';
		$groupId = '0x000000000000000000000000000000000000000000746573746e65745f303039';
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