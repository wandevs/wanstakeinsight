<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use WebSocket\Client;
class Welcome extends CI_Controller {

    public function clearPageCache($pass='')
    {
        if ($pass=='fennecisafox') // Change Password Here . . .
        {
        $this->output->delete_cache('/');
        $this->output->delete_cache('/selected-validators');
        $this->output->delete_cache('/reward');
        $this->output->delete_cache('/chart');
            $this->load->driver('cache', array('adapter' => 'file'));
            $this->cache->delete('stake_guide');
        echo 'Done';
        }
        else{
            show_404();
        }
    }

    private function _getLeaderGroupByEpoch($epoch_id)
    {

        $client = new Client($this->config->item('iwan_client'));
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);
        $this->load->driver('cache', array('adapter' => 'file'));

        $method = 'getLeaderGroupByEpochID';
        $epoch = $epoch_id;
        $result = $this->cache->get($method.'_'.$epoch);
        if (!$result)
        {

            $params_array = array(
                //'chainType'=>'WAN',
                'epochID'=>$epoch,
                //'address'=>'0x2a2fee5d3aefdcddd8247e3ea094a591323f3879',
                'timestamp'=>$this->config->item('iwan_timestamp')
            );

            //print_r($params_array);
            $signature_message = array(
                'jsonrpc'=>'2.0',
                'method'=>$method,
                'params'=>$params_array,
                'id'=>0,
            );
            $signature = base64_encode(hash_hmac('sha256', json_encode($signature_message), $secret,true));
            $params_array["signature"] = $signature;
            $query_array = array(
                'jsonrpc'=>'2.0',
                'method'=>$method,
                'params'=>$params_array,
                'id'=>0,
            );

            $query_string = json_encode($query_array);

			try
				{
					$client->send($query_string);
				}
				catch (exception $e)
				{
					$client->send($query_string);
					//echo $this->_api_error();
				}
            $result = json_decode($client->receive(),true);


            // Save into the cache for 1 days
            if (isset($result['result']) && $result['result'])
            {
                $result['result']['epochId'] = $epoch;
                $result = serialize($result['result']);
                $this->cache->save($method.'_'.$epoch, $result, 2592000);
                sleep(1);
            }
            else{

                $result = '';
                $this->output->delete_cache();
            }
        }



        return $result;
    }

    private function _getGekcoPrice()
    {


        //The URL we are connecting to.
        $url = 'https://api.coingecko.com/api/v3/coins/wanchain/tickers';

        //Initiate cURL.
        $ch = curl_init($url);

        //Disable CURLOPT_SSL_VERIFYHOST and CURLOPT_SSL_VERIFYPEER by
        //setting them to false.
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER , TRUE);
        //Execute the request.
        return curl_exec($ch);

    }

    private function _getEpochIncentivePayDetail($epoch_dec=1)
    {
        $epochinfo = $this->_getCurrentEpochInfo();
        if ($epochinfo=='')
        {
            return '';
        }

        $epochinfo = unserialize($epochinfo);
        $client = new Client($this->config->item('iwan_client'));
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);
        $this->load->driver('cache', array('adapter' => 'file'));

        $epoch = $epochinfo['epochId']-$epoch_dec;
        $method = 'getEpochIncentivePayDetail';

        $result = $this->cache->get($method.'_'.$epoch);
        if (!$result)
        {

            $params_array = array(
                //'chainType'=>'WAN',
                'epochID'=>$epoch,
                //'address'=>'0x2a2fee5d3aefdcddd8247e3ea094a591323f3879',
                'timestamp'=>$this->config->item('iwan_timestamp')
            );
            $signature_message = array(
                'jsonrpc'=>'2.0',
                'method'=>$method,
                'params'=>$params_array,
                'id'=>0,
            );
            $signature = base64_encode(hash_hmac('sha256', json_encode($signature_message), $secret,true));
            $params_array["signature"] = $signature;
            $query_array = array(
                'jsonrpc'=>'2.0',
                'method'=>$method,
                'params'=>$params_array,
                'id'=>0,
            );

            $query_string = json_encode($query_array);
            try
				{
					$client->send($query_string);
				}
				catch (exception $e)
				{
					$client->send($query_string);
					//echo $this->_api_error();
				}
            $result = json_decode($client->receive(),true);

            // Save into the cache for 1 days
            if (isset($result['result']) && $result['result'])
            {
                $result['result']['epochId'] = $epoch;
                $result = serialize($result['result']);
                $this->cache->save($method.'_'.$epoch, $result, 2592000);
            }
            else{
                $result = '';
                $this->output->delete_cache();
            }
        }
        return $result;
    }

    private function _getCurrentEpochInfo()
    {

        $client = new Client($this->config->item('iwan_client'));
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);
        $this->load->driver('cache', array('adapter' => 'file'));

        $method = 'getCurrentEpochInfo';
        if ( ! $result = $this->cache->get($method))
        {
            $params_array = array(
                'timestamp'=>$this->config->item('iwan_timestamp')
            );
            $signature_message = array(
                'jsonrpc'=>'2.0',
                'method'=>$method,
                'params'=>$params_array,
                'id'=>0,
            );
            $signature = base64_encode(hash_hmac('sha256', json_encode($signature_message), $secret,true));
            $params_array["signature"] = $signature;
            $query_array = array(
                'jsonrpc'=>'2.0',
                'method'=>$method,
                'params'=>$params_array,
                'id'=>0,
            );

            $query_string = json_encode($query_array);
			try
			{
				$client->send($query_string);
			}
			catch (exception $e)
			{
				$client->send($query_string);
					//echo $this->_api_error();
			}
            $result = json_decode($client->receive(),true);

            // Save into the cache for 5 minutes
            if (isset($result['result']) && $result['result'])
            {
                $result = serialize($result['result']);
                $this->cache->save($method, $result, 600);
            }
            else{
                $result = '';
                $this->output->delete_cache();
            }
        }

        return $result;
    }

    private function _getLeaderGroup($epoch_dec=0)
    {
        $epochinfo = $this->_getCurrentEpochInfo();
        if ($epochinfo=='')
        {
            return '';
        }

        $epochinfo = unserialize($epochinfo);
        $client = new Client($this->config->item('iwan_client'));
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);
        $this->load->driver('cache', array('adapter' => 'file'));

        $method = 'getLeaderGroupByEpochID';
        $epoch = $epochinfo['epochId']-$epoch_dec;
        $result = $this->cache->get($method.'_'.$epoch);
        if (!$result)
        {

            $params_array = array(
                //'chainType'=>'WAN',
                'epochID'=>$epoch,
                //'address'=>'0x2a2fee5d3aefdcddd8247e3ea094a591323f3879',
                'timestamp'=>$this->config->item('iwan_timestamp')
            );

            //print_r($params_array);
            $signature_message = array(
                'jsonrpc'=>'2.0',
                'method'=>$method,
                'params'=>$params_array,
                'id'=>0,
            );
            $signature = base64_encode(hash_hmac('sha256', json_encode($signature_message), $secret,true));
            $params_array["signature"] = $signature;
            $query_array = array(
                'jsonrpc'=>'2.0',
                'method'=>$method,
                'params'=>$params_array,
                'id'=>0,
            );

            $query_string = json_encode($query_array);

            //print_r($query_string);
            //die();

            try
				{
					$client->send($query_string);
				}
				catch (exception $e)
				{
					$client->send($query_string);
					//echo $this->_api_error();
				}
            $result = json_decode($client->receive(),true);

            // Save into the cache for 1 days
            if (isset($result['result']) && $result['result'])
            {
                $result['result']['epochId'] = $epoch;
                $result = serialize($result['result']);
                $this->cache->save($method.'_'.$epoch, $result, 2592000);
            }
            else{

                $result = '';
                $this->output->delete_cache();
            }
        }

        return $result;
    }

	private function _getCurrentStakerInfo()
    {
        $client = new Client($this->config->item('iwan_client'));
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);
        $this->load->driver('cache', array('adapter' => 'file'));

        $method = 'getCurrentStakerInfo';
        if ( ! $result = $this->cache->get($method))
        {
            $params_array = array(
                //'chainType'=>'WAN',

                //'address'=>'0x2a2fee5d3aefdcddd8247e3ea094a591323f3879',
                'timestamp'=>$this->config->item('iwan_timestamp')
            );
            $signature_message = array(
                'jsonrpc'=>'2.0',
                'method'=>$method,
                'params'=>$params_array,
                'id'=>0,
            );
            $signature = base64_encode(hash_hmac('sha256', json_encode($signature_message), $secret,true));
            $params_array["signature"] = $signature;
            $query_array = array(
                'jsonrpc'=>'2.0',
                'method'=>$method,
                'params'=>$params_array,
                'id'=>0,
            );

            $query_string = json_encode($query_array);
			
            try
				{
					$client->send($query_string);
				}
				catch (exception $e)
				{
					$client->send($query_string);
					//echo $this->_api_error();
				}
            $result = json_decode($client->receive(),true);

            // Save into the cache for 1 hour
            if (isset($result['result']) && $result['result'])
            {
                $result = serialize($result['result']);
                $this->cache->save($method, $result, 3600);
            }
            else{
                $result = '';
                $this->output->delete_cache();
            }
        }
        return $result;
    }

    private function _getStakerInfo($block_height)
    {
        $client = new Client($this->config->item('iwan_client'));
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);
        $this->load->driver('cache', array('adapter' => 'file'));

        $method = 'getStakerInfo';
        if ( ! $result = $this->cache->get($method.'_'.$block_height))
        {
            $params_array = array(
                //'chainType'=>'WAN',
                'blockNumber'=>$block_height,
                //'address'=>'0x2a2fee5d3aefdcddd8247e3ea094a591323f3879',
                'timestamp'=>$this->config->item('iwan_timestamp')
            );
            $signature_message = array(
                'jsonrpc'=>'2.0',
                'method'=>$method,
                'params'=>$params_array,
                'id'=>0,
            );
            $signature = base64_encode(hash_hmac('sha256', json_encode($signature_message), $secret,true));
            $params_array["signature"] = $signature;
            $query_array = array(
                'jsonrpc'=>'2.0',
                'method'=>$method,
                'params'=>$params_array,
                'id'=>0,
            );

            $query_string = json_encode($query_array);
            try
				{
					$client->send($query_string);
				}
				catch (exception $e)
				{
					$client->send($query_string);
					//echo $this->_api_error();
				}
            $result = json_decode($client->receive(),true);

            // Save into the cache for 5 minutes
            if (isset($result['result']) && $result['result'])
            {
                $result = serialize($result['result']);
                $this->cache->save($method.'_'.$block_height, $result, 2592000);
            }
            else{
                $result = '';
                $this->output->delete_cache();
            }
        }
        return $result;
    }

    private function _getBlockIncentive($epochId)
    {
        $client = new Client($this->config->item('iwan_client'));
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);

        $method = 'getEpochIncentiveBlockNumber';
        $this->load->driver('cache', array('adapter' => 'file'));
        if (!$result = $this->cache->get($method.'_'.$epochId))
        {
                $params_array = array(
                    //'chainType'=>'WAN',
                    'epochID' => $epochId,
                    //'address'=>'0x2a2fee5d3aefdcddd8247e3ea094a591323f3879',
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

                try
				{
					$client->send($query_string);
				}
				catch (exception $e)
				{
					$client->send($query_string);
					//echo $this->_api_error();
				}
                $result = json_decode($client->receive(), true);

                if (isset($result['result']) && $result['result']) {

                    $result = $result['result'];
                    $this->cache->save($method . '_' . $epochId, $result, 2592000);
                } else {
                    $result = '';
                    $this->output->delete_cache();
                }
            }
         return $result;
    }

    public function only_selfstake()
    {
        function get_validator_info($address, $validator_info_list)
        {

            if (isset($validator_info_list[$address])) {
                return $validator_info_list[$address];
            }
            return $address;
        }

        // Get Current Info //
        $currentStakerInfo = unserialize($this->_getCurrentStakerInfo());
        $validator_list = array();
        $total_stake = 0;
        foreach ($currentStakerInfo as $row) {
            if ($row['address'] == '0x4ee67553ab5fa994bc6a9cefecc93ff134083343') continue;
            if ($row['address'] == '0x89d91c8a8a17b6c9ebeadcb6dcc1bac664335186') continue;
            $selfstake = round(hexdec($row['amount']) / WAN_DIGIT, 18);
            $total_stake+=$selfstake;
            $validator_list[$row['address']] = $selfstake;
        }

        uasort($validator_list, function ($a, $b) {
            return $b - $a;
        });
        $validator_info_list = $this->config->item('validator_list');
        echo '<table>';

            foreach ($validator_list as $address=>$selfstake)
            {
                echo '<tr>';
                    $info = get_validator_info($address,$validator_info_list);

                    echo '<td>';
                         echo (isset($info['name'])?$info['name']:$info);
                    echo '</td>';

                    echo '<td>';
                    echo number_format($selfstake).' WAN';
                    echo '</td>';

                echo '<td>';
                echo number_format($selfstake*100/$total_stake,2).'%';
                echo '</td>';

                echo '</tr>';
            }

        echo '</table>';
    }

    public function stake_guide()
    {
		error_reporting(0);
        function get_validator_info($address, $validator_info_list)
        {

            if (isset($validator_info_list[$address])) {
                return $validator_info_list[$address];
            }
            return $address;
        }


        $invest = floor($this->input->get('amount'));
        if ($invest <= 100) {
            $invest = 100;
        }





            $this->load->driver('cache', array('adapter' => 'file'));
            if (!$view = $this->cache->get('stake_guide')):

                $epochinfo = $this->_getCurrentEpochInfo();
                $epochinfo = unserialize($epochinfo);
                $epoch_stop = $epochinfo['epochId'] - 1;
                $epoch_start = $epoch_stop - 29;

                $view['epoch_start'] = $epoch_start;
                $view['epoch_stop'] = $epoch_stop;
                //error_reporting(0);
                set_time_limit(0);
                // Start at 18141 //
                $validators = array();
                $delegating = array();
                $delegating_reward = array();
                $delegate_ratio = array();


                // Get Current Info //
                $currentStakerInfo = unserialize($this->_getCurrentStakerInfo());
                $delegating_validator_list = array();
                foreach ($currentStakerInfo as $row) {
                    $selfstake = round(hexdec($row['amount']) / WAN_DIGIT, 18);
                    foreach ($row['partners'] as $p_row) {
                        $selfstake += round(hexdec($p_row['amount']) / WAN_DIGIT, 18);
                    }


                    //echo $selfstake.'<br/>';
                    $selfstake = '' . $selfstake;
                    if ($selfstake < 50000 || $row['feeRate'] == 10000) {
                        continue;
                    }

                    $clientstake = 0;
                    foreach ($row['clients'] as $c_row) {
                        $clientstake += round(hexdec($c_row['amount']) / WAN_DIGIT, 18);
                    }

                    $delegating_validator_list[$row['address']]['selfstake'] = $selfstake;
                    $delegating_validator_list[$row['address']]['clientstake'] = $clientstake;
                }

                //echo '<pre>';
                //print_r($delegating_validator_list);
                //die();


                for ($epoch = $epoch_start; $epoch <= $epoch_stop; $epoch++) {

                    // Get Stake & Fee //
                    $block_height = $this->_getBlockIncentive((int)$epoch);
                    if ($block_height) {

                        $StakerInfo = unserialize($this->_getStakerInfo($block_height));


                        foreach ($StakerInfo as $staker) {
                            foreach ($staker['clients'] as $client) {
                                if (!isset($delegating[$staker['address']][$epoch])) {
                                    $delegating[$staker['address']][$epoch] = 0;
                                    $delegate_ratio[$staker['address']][$epoch] = 0;
                                    $delegating_reward[$staker['address']][$epoch] = 0;
                                }
                                $delegating[$staker['address']][$epoch] += hexdec($client['amount']) / WAN_DIGIT;
                            }
                        }

                    }


                    $client = new Client($this->config->item('iwan_client'));
                    $secret = $this->config->item('iwan_secret');
                    $timestamp = round(microtime(true) * 1000);


                    $method = 'getEpochIncentivePayDetail';

                    $result = $this->cache->get($method . '_' . $epoch);
                    if (!$result) {

                        $params_array = array(
                            //'chainType'=>'WAN',
                            'epochID' => $epoch,
                            //'address'=>'0x2a2fee5d3aefdcddd8247e3ea094a591323f3879',
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
                        try
						{
							$client->send($query_string);
						}
						catch (exception $e)
						{
							$client->send($query_string);
					//echo $this->_api_error();
						}
                        $result = json_decode($client->receive(), true);

                        // Save into the cache for 1 days
                        if (isset($result['result']) && $result['result']) {
                            $result['result']['epochId'] = $epoch;
                            $result = serialize($result['result']);

                            $this->cache->save($method . '_' . $epoch, $result, 2592000);
                        } else {
                            $result = '';

                        }
                    }

                    // Do select //
                    $pay_reward = unserialize($result);

                    if ($pay_reward) {
                        $epoch_selected = array();
                        foreach ($pay_reward as $reward) {
                            if (!isset($reward['address']) || !$reward['address']) continue;

                            if (!isset($validators[$reward['address']])) {
                                $validators[$reward['address']]['validator_reward'] = 0;
                                $validators[$reward['address']]['delegator_reward'] = 0;
                                $validators[$reward['address']]['epoch_selected'] = 0;
                                $validators[$reward['address']]['working_selected'] = 0;
                                $validators[$reward['address']]['EL'] = 0;
                                $validators[$reward['address']]['RNP'] = 0;
                                $validators[$reward['address']]['SL'] = 0;


                            }
                            if (!isset($epoch_selected[$reward['address']])) {
                                $epoch_selected[$reward['address']] = 0;
                            }

                            $validators[$reward['address']]['validator_reward'] += hexdec($reward['incentive']) / WAN_DIGIT;
                            $epoch_selected[$reward['address']]++;


                            if (isset($reward['delegators'])) {
                                foreach ($reward['delegators'] as $de_reward) {

                                    $validators[$reward['address']]['delegator_reward'] += hexdec($de_reward['incentive']) / WAN_DIGIT;
                                    if (!isset($delegating_reward[$reward['address']][$epoch])) {
                                        $delegating_reward[$reward['address']][$epoch] = 0;
                                    }
                                    $delegating_reward[$reward['address']][$epoch] += hexdec($de_reward['incentive']) / WAN_DIGIT;
                                }
                            }


                        }

                        foreach ($epoch_selected as $address => $selected) {
                            if ($selected > 0) {
                                $validators[$address]['epoch_selected']++;
                                $validators[$address]['working_selected'] += $selected;
                            }
                        }

                        // Get EL RNP SL //

                        $leaders = unserialize($this->_getLeaderGroupByEpoch((int)$epoch));
                        foreach ($leaders as $leader) {
                            if (!isset($validators[$leader['secAddr']])) continue; // case of foundation
                            if ($leader['type'] == 0) {
                                $validators[$leader['secAddr']]['EL']++;
                            } else {
                                $validators[$leader['secAddr']]['RNP']++;
                            }
                        }


                    }

                }

                // Calculate to total incentive //
                $total_validator_reward = 0;
                $total_delegator_reward = 0;
                foreach ($validators as $validator) {
                    $total_validator_reward += $validator['validator_reward'];
                    $total_delegator_reward += $validator['delegator_reward'];

                }


                //echo '<pre>';
                //print_r($delegating_reward);


                foreach ($delegating as $address => $delegate_epoch) {
                    foreach ($delegate_epoch as $epoch => $delegated) {
                        // Calculate Ratio //

                        $delegate_ratio[$address][$epoch] = $delegating_reward[$address][$epoch] / $delegated * 1000000;

                    }
                }

                // Recal Ratio & SL//
                $predict_ratio = 0;
                $count_predict_ratio = 0;
                $total_el = 0;
                $total_rnp = 0;
                $total_sl = 0;
                foreach ($validators as $address => $validator) {
                    if (!isset($delegate_ratio[$address])) {
                        $delegate_ratio[$address] = array();
                    }
                    $validators[$address]['delegate_ratio'] = (array_sum($delegate_ratio[$address]));

                    // Predict //
                    $predict_ratio += array_sum($delegate_ratio[$address]);
                    foreach ($delegate_ratio[$address] as $row) {
                        $count_predict_ratio++;
                    }


                    // $validators[$address]['SL'] = $validators[$address]['EL'];
                    // $total_sl +=$validators[$address]['SL'];
                    $total_el += $validators[$address]['EL'];
                    $total_rnp += $validators[$address]['RNP'];
                    //echo $address.' - '.$validators[$address]['SL'];
                    //die();
                }


                $validator_info_list = $this->config->item('validator_list');
                ?>

                <?php

                $stategy1 = array();
                foreach ($validators as $address => $validator) {
                    if (!isset($delegating_validator_list[$address])) continue;
                    // For Stategy 1//
                    //if ($validator['epoch_selected'] < 25) continue;
                    $info = get_validator_info($address, $validator_info_list);
                    $stategy1[$address]['name'] = (isset($info['name']) ? $info['name'] : $info);
                    $stategy1[$address]['selfstake'] = $delegating_validator_list[$address]['selfstake'];
                    $stategy1[$address]['clientstake'] = $delegating_validator_list[$address]['clientstake'];
                    $stategy1[$address]['epoch'] = $validator['epoch_selected'];
                    $stategy1[$address]['el'] = $validator['EL'];
                    $stategy1[$address]['rnp'] = $validator['RNP'];
                    $stategy1[$address]['est_reward'] = $validator['delegate_ratio'];
                }

                $stategy2 = array();
                foreach ($validators as $address => $validator) {
                    if (!isset($delegating_validator_list[$address])) continue;
                    // For Stategy 2//
                    $info = get_validator_info($address, $validator_info_list);
                    $stategy2[$address]['name'] = (isset($info['name']) ? $info['name'] : $info);
                    //$stategy2[$address]['selfstake'] = $delegating_validator_list[$address]['selfstake'];
                    //$stategy2[$address]['clientstake'] = $delegating_validator_list[$address]['clientstake'];
                    $stategy2[$address]['epoch'] = $validator['epoch_selected'];
                    $stategy2[$address]['el'] = $validator['EL'];
                    $stategy2[$address]['rnp'] = $validator['RNP'];
                    $stategy2[$address]['est_reward'] = $validator['delegate_ratio'];
                }
                uasort($stategy2, function ($a, $b) {
                    return $b['est_reward'] - $a['est_reward'];
                });

                //echo '<pre>';
                //print_r($stategy2);
                //die();

                //echo '<pre>';
                //print_r($stategy1);
                //die();
                $view['stategy1'] = $stategy1;
                $view['stategy2'] = $stategy2;
                $view['validator_info_list'] = $validator_info_list;
                $this->cache->save('stake_guide', $view, 21600); // 6 hours
        endif;

        $view['invest'] = $invest;



        $view['web_title'] = 'Staking Guide';
        $this->load->view('stake_guide',$view);
    }


	public function index()
	{
	    error_reporting(0);
        $this->output->cache(60);
        // Validator Address //
        // Delegated amount //
        // Highest Delegated  //
        // Average Delegated
        // Sum of Partner In //
        // Fee Rate //
        // Max Fee Rate //

        // Pending Stake Out//
        $validator_list = array();
        $non_delegate_validator_list = array();

        $view['max_fee_rate_list'] = array();
        $view['fee_rate_list'] = array();
        $view['delegate_amount_list'] = array();
        $view['total_staked'] = 0;
        $view['pending_stake_out_list'] = array();
        $view['non_delegate_validator_amount_list'] = array();
        $view['delegate_validator_amount_list'] = array();
        $view['total_voting_power'] =0;


        $result = unserialize($this->_getCurrentStakerInfo());

        foreach($result as $row)
        {
            //echo '<pre>';
            //print_r($row);
            //echo '</pre>';
            //die();

            $selfstake = round(hexdec($row['amount'])/WAN_DIGIT,18);
            foreach($row['partners'] as $p_row)
            {
                $selfstake+=round(hexdec($p_row['amount'])/WAN_DIGIT,18);
            }

            //echo $selfstake.'<br/>';
            $selfstake = ''.$selfstake;
            if ($selfstake < 50000 || $row['feeRate']==10000)
            {

               // echo '>'.$selfstake.'<br/>';
                $view['non_delegate_validator_amount_list'][$row['address']] = $selfstake;
                $view['total_staked'] += $selfstake;


                // Calculate non d validator
                $non_delegate_validator_list[$row['address']]['address'] = $row['address'];
                $selfstake = round(hexdec($row['amount'])/WAN_DIGIT,18);
                $non_delegate_validator_list[$row['address']]['selfStake'] = $selfstake;
                $non_delegate_validator_list[$row['address']]['partnerAmount'] = array();
                $non_delegate_validator_list[$row['address']]['sumVotingPower'] = round(hexdec($row['votingPower'])/WAN_DIGIT,18);

                $non_delegate_validator_list[$row['address']]['stakingEpoch'] = $row['stakingEpoch'];
                $non_delegate_validator_list[$row['address']]['lockEpochs'] = $row['lockEpochs'];
                $non_delegate_validator_list[$row['address']]['nextLockEpochs'] = $row['nextLockEpochs'];


                // Sum Voting Power //
                foreach($row['partners'] as $p_row)
                {

                    $non_delegate_validator_list[$row['address']]['partnerAmount'][] = round(hexdec($p_row['amount'])/WAN_DIGIT,18);
                    $selfstake+=round(hexdec($p_row['amount'])/WAN_DIGIT,18);
                    $non_delegate_validator_list[$row['address']]['sumVotingPower'] += round(hexdec($p_row['votingPower'])/WAN_DIGIT,18);

                    $view['total_voting_power'] += hexdec($p_row['votingPower'])/WAN_DIGIT;
                }
                foreach($row['clients'] as $c_row)
                {
                    $view['total_voting_power'] += hexdec($c_row['votingPower'])/WAN_DIGIT;
                }
                $view['total_voting_power'] += hexdec($row['votingPower'])/WAN_DIGIT;


                continue;
            }

            $validator_list[$row['address']]['address'] = $row['address'];
            $view['fee_rate_list'][] = $validator_list[$row['address']]['feeRate'] = $row['feeRate'];
            $view['max_fee_rate_list'][] = $validator_list[$row['address']]['maxFeeRate'] = $row['maxFeeRate'];

            // Reset selfstake //
            $selfstake = round(hexdec($row['amount'])/WAN_DIGIT,18);
            $validator_list[$row['address']]['selfStake'] = $selfstake;

            $validator_list[$row['address']]['partnerAmount'] = array();

            $validator_list[$row['address']]['sumVotingPower'] = round(hexdec($row['votingPower'])/WAN_DIGIT,18);
           // die();

            // Epoch //
            $validator_list[$row['address']]['stakingEpoch'] = $row['stakingEpoch'];
            $validator_list[$row['address']]['lockEpochs'] = $row['lockEpochs'];
            $validator_list[$row['address']]['nextLockEpochs'] = $row['nextLockEpochs'];

            foreach($row['partners'] as $p_row)
            {
                $validator_list[$row['address']]['partnerAmount'][] = round(hexdec($p_row['amount'])/WAN_DIGIT,18);
                $selfstake+=round(hexdec($p_row['amount'])/WAN_DIGIT,18);
                $validator_list[$row['address']]['sumVotingPower'] += round(hexdec($p_row['votingPower'])/WAN_DIGIT,18);
                $view['total_voting_power'] += hexdec($p_row['votingPower'])/WAN_DIGIT;
            }

            $view['delegate_validator_amount_list'][] = $selfstake;
            $view['total_staked'] += $selfstake;

            foreach($row['clients'] as $c_row)
            {
                if ($c_row['quitEpoch'] != 0)
                {
                    $view['pending_stake_out_list'][] = round(hexdec($c_row['amount'])/WAN_DIGIT,18);
                    $validator_list[$row['address']]['stake_out'][] = round(hexdec($c_row['amount'])/WAN_DIGIT,18);
                }
                $view['total_staked'] += $view['delegate_amount_list'][] = $validator_list[$row['address']]['delegatorAmount'][] = round(hexdec($c_row['amount'])/WAN_DIGIT,18);
                $validator_list[$row['address']]['sumVotingPower'] += round(hexdec($c_row['votingPower'])/WAN_DIGIT,18);
                $view['total_voting_power'] += hexdec($c_row['votingPower'])/WAN_DIGIT;
            }

            $view['total_voting_power'] += hexdec($row['votingPower'])/WAN_DIGIT;

            if (isset($validator_list[$row['address']]['delegatorAmount']))
            {
                $validator_list[$row['address']]['sumDelegatorAmount'] = array_sum($validator_list[$row['address']]['delegatorAmount']);
            }
            else{
                $validator_list[$row['address']]['sumDelegatorAmount'] = 0;
            }
        }
        //die();

        // Sort by stake //
        usort($validator_list, function($a, $b) {
            return $b['sumDelegatorAmount'] - $a['sumDelegatorAmount'];
        });

        // Sort by stake //
        usort($non_delegate_validator_list, function($a, $b) {
            return $b['sumVotingPower'] - $a['sumVotingPower'];
        });

        $view['validator_list'] = $validator_list;

        $view['non_delegate_validator_list'] = $non_delegate_validator_list;
        $view['validator_info_list'] = $this->config->item('validator_list');
       // echo count($validator_list);
       // die();
        //print_r($delegator_list);
       // echo '<pre>';
      // print_r($view['non_delegate_validator_amount_list']);

     // die();

        $epochinfo = $this->_getCurrentEpochInfo();

        $epochinfo = unserialize($epochinfo);
        $view['current_epoch_id'] = $epochinfo['epochId'];

        $view['web_title'] = 'HOME';
		$this->load->view('welcome_message',$view);
	}

    public function selected_validators()
    {
        error_reporting(0);
        $this->output->cache(15);

        $result = unserialize($this->_getLeaderGroup());

        $view['EL_list'] = array();
        $view['RNP_list'] = array();
        $view['selected_validators'] = array();
        $view['current_epoch'] = $result['epochId'];
        unset($result['epochId']);
        foreach($result as $leader)
        {

            // Init //
            if (!isset($view['selected_validators'][$leader['secAddr']]))
            {
                $view['selected_validators'][$leader['secAddr']]=array(
                    'COUNT'=>0,
                    'EL'=>0,
                    'RNP'=>0
                );
            }

            $view['selected_validators'][$leader['secAddr']]['COUNT']++;
            if ($leader['type']==0) // EL Leader
            {
                if (!isset($view['EL_list'][$leader['secAddr']]))
                {
                    $view['EL_list'][$leader['secAddr']] = 0;
                }
                $view['EL_list'][$leader['secAddr']]++;
                $view['selected_validators'][$leader['secAddr']]['EL']++;
            }
            else{ // RNP
                if (!isset($view['RNP_list'][$leader['secAddr']]))
                {
                    $view['RNP_list'][$leader['secAddr']] = 0;
                }
                $view['RNP_list'][$leader['secAddr']]++;
                $view['selected_validators'][$leader['secAddr']]['RNP']++;
            }

        }

        // Sort by stake //
        uasort($view['selected_validators'], function($a, $b) {
            return $b['COUNT'] - $a['COUNT'];
        });

        $view['SL_list'] = array();
        $result_previous = unserialize($this->_getLeaderGroup(1));
        foreach($result_previous as $leader)
        {
            if ($leader['type']==0) // EL Leader
            {
                if (!isset($view['SL_list'][$leader['secAddr']]))
                {
                    $view['SL_list'][$leader['secAddr']] = 0;
                }
                $view['SL_list'][$leader['secAddr']]++;

            }
        }

        //echo '<pre>';
       // print_r($view['SL_list']);
       // echo '</pre>';
        //die();


        //print_r($view['RNP_list']);
       // print_r($view['EL_list']);
        //echo '<pre>';
       // print_r($view['selected_validators']);

        //die();
        $this->load->config('foundations');
        $view['foundation_list'] = $this->config->item('foundation_list');

        $view['selected_foundation'] = array();
        foreach($view['selected_validators'] as $key=>$row)
        {
            if (in_array($key,$view['foundation_list']))
            {
                $view['selected_foundation'][$key] = $view['selected_validators'][$key];
                unset($view['selected_validators'][$key]);
            }
        }

        //echo '<pre>';
        // print_r($view['selected_validators']);
        //die();

        $foundation_count = 0;

        //    'COUNT'=>0,
        //    'EL'=>0,
        //    'RNP'=>0

        $view['selected_validators']['foundation'] = array(
            'COUNT'=>0,
            'EL'=>0,
            'RNP'=>0,
            'FOUNDATION_COUNT'=>0
        );

        foreach($view['selected_foundation'] as $key=>$foundation)
        {
            $view['selected_validators']['foundation']['COUNT'] += $foundation['COUNT'];
            $view['selected_validators']['foundation']['EL'] += $foundation['EL'];
            $view['selected_validators']['foundation']['RNP'] += $foundation['RNP'];
            $view['selected_validators']['foundation']['FOUNDATION_COUNT']++;
        }

       // $view['selected_validators'] = array_merge($view['selected_validators'],$view['selected_foundation']);
        $view['total_selected'] = array_sum($view['EL_list'])+array_sum($view['RNP_list']);

        //echo '<pre>';
        //print_r($view['selected_validators']);
        //die();
        $view['web_title'] = 'SELECTED VALIATORS';
        $view['validator_info_list'] = $this->config->item('validator_list');
        $this->load->view('selected_validator',$view);
    }

    function test_cache()
    {
        $pay_reward = $this->_getEpochIncentivePayDetail(0);
        print_r($pay_reward);
    }

    function surprise()
    {

    }

    public function reward()
    {
        error_reporting(0);
        $this->output->cache(15);

        //$selected_result = unserialize($this->_getLeaderGroup());

        $epoch_dec = 1;
        $pay_reward = $this->_getEpochIncentivePayDetail($epoch_dec);


        if (!$pay_reward)
        {

            $epoch_dec = 2;
            $pay_reward = $this->_getEpochIncentivePayDetail($epoch_dec);
        }


        // Debug //
        /*
        $pay_reward = (unserialize($pay_reward));

        $pay_reward_yesterday = unserialize($this->_getEpochIncentivePayDetail($epoch_dec+2));
        // Find yesterday select for SL //
        $leader = unserialize($this->_getLeaderGroup($epoch_dec+2));
        $select_only = '0xfc2730f75330bb75cb28fcff12f0aea5b6e433e1';
        $count_EL = 0;
        foreach($leader as $row)
        {
            if ($row['secAddr'] != $select_only) continue;
            if ($row['type']==0) // EL //
            {
                $count_EL++;
            }
        }
        echo 'All Epoch:'.$leader['epochId'].' - # EL'.$count_EL.'<br/>';
        //die();

        foreach($pay_reward_yesterday as $row)
        {
            if ($row['address'] != $select_only) continue;
            // sum of all delegatoors //
            $tmp = 0;
            foreach($row['delegators'] as $delegator)
            {
                $tmp += hexdec($delegator['incentive']);
            }
            echo $row['address'].'->'.number_format(hexdec($row['incentive'])/WAN_DIGIT,2).'->'.number_format($tmp/WAN_DIGIT,2).'<br/>';
        }

        echo '----------------------------------------<br/>';

        echo 'Epoch:'.$pay_reward['epochId'].'<br/>';
        foreach($pay_reward as $row)
        {
            if ($row['address'] != $select_only) continue;
            // sum of all delegatoors //
            $tmp = 0;
            foreach($row['delegators'] as $delegator)
            {
                $tmp += hexdec($delegator['incentive']);
            }
            echo $row['address'].'->'.number_format(hexdec($row['incentive'])/WAN_DIGIT,2).'->'.number_format($tmp/WAN_DIGIT,2).'<br/>';
        }
        die();
        */


        $leader = unserialize($this->_getLeaderGroup($epoch_dec));
        $view['current_epoch'] = $leader['epochId']+$epoch_dec;
        $view['reward_epoch'] = $leader['epochId'];

        $block_height = $this->_getBlockIncentive($leader['epochId']);
        if ($block_height)
        {

            $StakerInfo = unserialize($this->_getStakerInfo($block_height));
        }
        //echo '<pre>';
        //print_r($StakerInfo);
        //die();


        $all_validator_stake = array();
        //$total_delegator_stake = 0;
        foreach($StakerInfo as $staker)
        {
            if (!isset($all_validator_stake[$staker['address']]['delegated_amount']))
            {
                $all_validator_stake[$staker['address']]['delegated_amount'] = 0;
                $all_validator_stake[$staker['address']]['delegated_fee_amount'] = 0;
                $all_validator_stake[$staker['address']]['validator_stake_amount'] = 0;
                $all_validator_stake[$staker['address']]['feeRate'] = $staker['feeRate'];
            }

            foreach($staker['clients'] as $client)
            {
                //echo (hexdec($client['amount'])/WAN_DIGIT).'<br/>';
                $all_validator_stake[$staker['address']]['delegated_amount'] += (hexdec($client['amount'])/WAN_DIGIT);
                //$total_delegator_stake += (hexdec($client['amount'])/WAN_DIGIT);

            }
            foreach($staker['partners'] as $partner)
            {
                //echo (hexdec($client['amount'])/WAN_DIGIT).'<br/>';
                $all_validator_stake[$staker['address']]['validator_stake_amount'] += (hexdec($partner['amount'])/WAN_DIGIT);
                //$total_delegator_stake += (hexdec($client['amount'])/WAN_DIGIT);
            }

            $all_validator_stake[$staker['address']]['validator_stake_amount']+=(hexdec($staker['amount'])/WAN_DIGIT);

        }

        //echo '<pre>';
        //print_r($leader);
        foreach($leader as $row)
        {
            if ($row['type']==0)
            {
                $view['leader']['EL'][] = $row['secAddr'];
            }
            else{
                $view['leader']['RNP'][] = $row['secAddr'];
            }
        }

        $leader = unserialize($this->_getLeaderGroup($epoch_dec+1));
        $leader_yesterday_list = array();
        foreach ($leader as $row)
        {
            if (!isset($leader_yesterday_list[$row['secAddr']]))
            {
                $leader_yesterday_list[$row['secAddr']] = array(
                    'RNP'=>0,
                    'EL'=>0
                );
            }
            if ($row['type']==0)
            {
                $leader_yesterday_list[$row['secAddr']]['EL']++;
            }
            else{
                $leader_yesterday_list[$row['secAddr']]['RNP']++;
            }
        }
        $view['leader_yesterday_list'] = $leader_yesterday_list;

        $pay_reward = unserialize($pay_reward);
        $total_delegators = 0;
        $total_incentive = 0;
        $total_validator_incentive = 0;
        $total_validators = 0;
    /*
        echo '<pre>';
        //print_r($pay_reward);//0x7c611c6da96e3e4f5bfc3116fbcb60a1460117c9
        foreach($pay_reward as $reward)
        {
            if ($reward['address'] != '0x7c611c6da96e3e4f5bfc3116fbcb60a1460117c9') continue;
            if (isset($reward['delegators']))
                foreach($reward['delegators'] as $delegator)
                {
                    echo $delegator['address'].' -> '.round(hexdec($delegator['incentive'])/WAN_DIGIT,18).'<br/>';
                }
        }
        die();
*/

        foreach($pay_reward as $reward)
        {
            if (isset($reward['delegators']))
                foreach($reward['delegators'] as $delegator)
                {
                    $total_delegators++;
                    $total_incentive+=hexdec($delegator['incentive'])/WAN_DIGIT;
                }
            $total_validator_incentive += hexdec($reward['incentive'])/WAN_DIGIT;
        }

        $all_validator = array();
        foreach($pay_reward as $reward)
        {
            if (!$reward['address']) continue;
            if (!isset($all_validator[$reward['address']]))
            {
                $all_validator[$reward['address']]=array(
                    'incentive'=>0,
                    'delegators'=>0,
                    'delegator_incentive'=>0,
                    'reward_ratio'=>0,
                    'leader_count'=>0,
                    'feeRate'=>$all_validator_stake[$reward['address']]['feeRate']/100,
                    'delegated_fee_amount'=>0
                );
            }

            if (isset($reward['delegators']))
            foreach($reward['delegators'] as $delegator)
            {
                $all_validator[$reward['address']]['delegators']++;
                $all_validator[$reward['address']]['delegator_incentive']+=hexdec($delegator['incentive'])/WAN_DIGIT;

                $rate = 1+($all_validator_stake[$reward['address']]['feeRate']/100)/100;
                $before_fee = ((hexdec($delegator['incentive'])/WAN_DIGIT)*$rate);
                //echo ((hexdec($client['amount'])/WAN_DIGIT)*(100+(0/100))).'<br/>';
                $all_validator[$reward['address']]['delegated_fee_amount'] += $before_fee-(hexdec($delegator['incentive'])/WAN_DIGIT);

            }

            $all_validator[$reward['address']]['incentive'] += hexdec($reward['incentive'])/WAN_DIGIT;
            //$validator['delegator_incentive']/$all_validator_stake[$address]['delegated_amount']
            $tmp_total_stake = $all_validator_stake[$reward['address']]['delegated_amount'];
            $tmp_reward = $all_validator[$reward['address']]['delegator_incentive'];

            if ($tmp_total_stake != 0)
            {
            $all_validator[$reward['address']]['reward_ratio'] = $tmp_reward/$tmp_total_stake*1000000;
            }

            $all_validator[$reward['address']]['leader_count']++;
        }

        //echo '<pre>';
        //print_r($all_validator);

        uasort($all_validator, function($a, $b) {
            return $b['reward_ratio'] - $a['reward_ratio'];
        });



        $view['total_delegators'] = $total_delegators;
        $view['total_delegator_incentive'] = $total_incentive;
        $view['total_validator_incentive'] = $total_validator_incentive;
        $view['total_validators'] = count($all_validator);
        $view['validator_list'] = $all_validator;
        $view['all_validator_stake'] = $all_validator_stake;


        $view['validator_info_list'] = $this->config->item('validator_list');
        $view['web_title'] = 'REWARD';
        $this->load->view('reward',$view);

    }

    public function chart()
    {
        error_reporting(0);
        $this->output->cache(30);
        // Get Current Epoch//
        $epochinfo = $this->_getCurrentEpochInfo();
        if ($epochinfo=='')
        {
            return '';
        }

        $epochinfo = unserialize($epochinfo);
        $epoch_stop = $epochinfo['epochId']-1;
        $epoch_start = $epoch_stop-30;

        if ($epoch_start < 18143)
        {
            $epoch_start = 18143;
        }

        $delegated_amount = array();
        $delegator = array();
        $pending_stakeout_amount = array();
        $pending_stakeout_delegator = array();
        $validator_reward = array();
        $delegator_reward = array();

        for($epoch=$epoch_start;$epoch<=$epoch_stop;$epoch++) {

            $delegated_amount[$epoch] = 0;
            $pending_stakeout_amount[$epoch] = 0;
            $delegator[$epoch] = 0;
            $pending_stakeout_delegator[$epoch] = 0;
            $validator_reward[$epoch] = 0;
            $delegator_reward[$epoch] = 0;

            // Get Staker & Fee //
            $block_height = $this->_getBlockIncentive((int)$epoch);


            if ($block_height) {


                $StakerInfo = unserialize($this->_getStakerInfo($block_height));


                foreach ($StakerInfo as $staker) {
                    foreach ($staker['clients'] as $client) {
                        $delegator[$epoch]++;
                        $delegated_amount[$epoch] += hexdec($client['amount']) / WAN_DIGIT;
                        if ($client['quitEpoch'] != 0) {
                            $pending_stakeout_amount[$epoch] += hexdec($client['amount']) / WAN_DIGIT;
                            $pending_stakeout_delegator[$epoch]++;
                        }
                    }

                }

            }

            // About Reward //
            $client = new Client($this->config->item('iwan_client'));
            $secret = $this->config->item('iwan_secret');
            $timestamp = round(microtime(true) * 1000);
            $this->load->driver('cache', array('adapter' => 'file'));

            $method = 'getEpochIncentivePayDetail';

            $result = $this->cache->get($method . '_' . $epoch);
            if (!$result) {

                $params_array = array(
                    'epochID' => $epoch,
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
                try
				{
					$client->send($query_string);
				}
				catch (exception $e)
				{
					$client->send($query_string);
					//echo $this->_api_error();
				}
                $result = json_decode($client->receive(), true);

                // Save into the cache for 1 days
                if (isset($result['result']) && $result['result']) {
                    $result['result']['epochId'] = $epoch;
                    $result = serialize($result['result']);
                    $this->cache->save($method . '_' . $epoch, $result, 2592000);
                } else {
                    $result = '';
                }
            }

            // Do select //
            $pay_reward = unserialize($result);

            if ($pay_reward) {
                $epoch_selected = array();
                foreach ($pay_reward as $reward) {
                    if (!isset($reward['address']) || !$reward['address']) continue;
                    $validator_reward[$epoch] += hexdec($reward['incentive']) / WAN_DIGIT;

                    if (isset($reward['delegators'])) {
                        foreach($reward['delegators'] as $de_reward) {
                            $delegator_reward[$epoch] += hexdec($de_reward['incentive']) / WAN_DIGIT;
                        }

                    }

                }
                $delegator_reward[$epoch] = round($delegator_reward[$epoch],2);
                $validator_reward[$epoch] = round($validator_reward[$epoch],2);
            }
        }
        if (end($delegator) == 0)
        {
            $epoch_stop--;
            array_pop($delegator);
            array_pop($delegated_amount);
            array_pop($pending_stakeout_amount);
            array_pop($pending_stakeout_delegator);
            array_pop($validator_reward);
            array_pop($delegator_reward);
        }

        $view['web_title'] = 'CHART';
        $view['epoch_start'] = $epoch_start;
        $view['epoch_stop'] = $epoch_stop;
        $view['delegated_amount'] = $delegated_amount;
        $view['delegators'] = $delegator;
        $view['pending_stakeout_amount'] = $pending_stakeout_amount;
        $view['pending_stakeout_delegator'] = $pending_stakeout_delegator;
        $view['validator_reward'] = $validator_reward;
        $view['delegator_reward'] = $delegator_reward;

        // Find Est Reward Ratio //
        $est_reward = array();
        foreach ($delegated_amount as $epoch=>$amount)
        {
            $est_reward[$epoch] = round($delegator_reward[$epoch] / $amount * 1000,2);
        }
        $view['est_reward'] = $est_reward;


        $this->load->view('chart',$view);

       // echo '<pre>';
       // print_r($delegated_amount);print_r($delegator);print_r($pending_stakeout_amount);

    }

    public function epoch_summary()
    {
        error_reporting(0);

        // Get Price //
        //$price = json_decode($this->_getGekcoPrice(),true);
        $selected_result = unserialize($this->_getLeaderGroup());
        echo '🔍 Epoch '.$selected_result['epochId'].' insight 🔎<br/><br/>';

        //echo 'PRICE (Binance):<br/>';
        //echo number_format($price['tickers'][0]['last'],8).' '.$price['tickers'][0]['target'].' | '.$price['tickers'][1]['last'].' '.$price['tickers'][1]['target'].' | '.$price['tickers'][4]['last'].' '.$price['tickers'][4]['target'].'<br/><br/>';


        // Stake Info //
        $stakers = unserialize($this->_getCurrentStakerInfo());
        $delegating_validator_count = 0;
        $non_delegate_validator_count = 0;
        $delegate_capacity = 0;
        $total_delegated = 0;
        $total_stakeout = 0;
        foreach($stakers as $row)
        {

            $selfstake = round(hexdec($row['amount'])/WAN_DIGIT,18);
            foreach($row['partners'] as $p_row)
            {
                $selfstake+=round(hexdec($p_row['amount'])/WAN_DIGIT,18);
            }

            // Filter Out Non-Validator //
            $selfstake = ''.$selfstake;
            if ($selfstake < 50000 || $row['feeRate']==10000)
            {
                $non_delegate_validator_count++;
                continue;
            }

            // Reset //
            $selfstake = round(hexdec($row['amount'])/WAN_DIGIT,18);
            $delegated = 0;

            foreach($row['partners'] as $p_row)
            {
                $selfstake+=round(hexdec($p_row['amount'])/WAN_DIGIT,18);;
            }
            foreach($row['clients'] as $c_row)
            {
                if ($c_row['quitEpoch'] != 0)
                {
                    $total_stakeout+= round(hexdec($c_row['amount'])/WAN_DIGIT,18);
                }
                $delegated+=round(hexdec($c_row['amount'])/WAN_DIGIT,18);
            }


            // Sum All //
            $total_delegated += $delegated;
            $delegate_capacity += $selfstake;
            $delegating_validator_count++;
        }
        $delegate_capacity*=10;

        // Show Result //
        echo 'Total: '.($delegating_validator_count+$non_delegate_validator_count).' validators<br/>';
        echo 'Delegating: '.$delegating_validator_count.' validators<br/>';
        echo 'Non-Delegation: '.$non_delegate_validator_count.' validators<br/><br/>';
        //echo '===================================<br/><br/>';


        echo 'Delegation Capacity: '.number_format($delegate_capacity,2).' WAN<br/>';
        echo 'Delegated: '.number_format($total_delegated,2).' WAN ('.number_format($total_delegated*100/$delegate_capacity,2).'%)<br/>';
        echo 'Pending Stake-out: '.number_format($total_stakeout,2).' WAN ('.number_format($total_stakeout*100/$total_delegated,2).'% of Delegated)<br/>';



        $view['EL_list'] = array();
        $view['RNP_list'] = array();
        $view['selected_validators'] = array();



        foreach($selected_result as $leader)
        {



            // Init //
            if (!isset($view['selected_validators'][$leader['secAddr']]))
            {
                $view['selected_validators'][$leader['secAddr']]=array(
                    'COUNT'=>0,
                    'EL'=>0,
                    'RNP'=>0
                );
            }

            if ($leader['secAddr'] == '') continue;

            $view['selected_validators'][$leader['secAddr']]['COUNT']++;

            if ($leader['type']==0) // EL Leader
            {

                if (!isset($view['EL_list'][$leader['secAddr']]))
                {
                    $view['EL_list'][$leader['secAddr']] = 0;
                }
                $view['EL_list'][$leader['secAddr']]++;
                $view['selected_validators'][$leader['secAddr']]['EL']++;
            }
            else{ // RNP
                if (!isset($view['RNP_list'][$leader['secAddr']]))
                {
                    $view['RNP_list'][$leader['secAddr']] = 0;
                }
                $view['RNP_list'][$leader['secAddr']]++;
                $view['selected_validators'][$leader['secAddr']]['RNP']++;
            }

        }




        uasort($view['selected_validators'], function($a, $b) {
            return $b['COUNT'] - $a['COUNT'];
        });
        $this->load->config('foundations');
        $view['foundation_list'] = $this->config->item('foundation_list');

        $view['selected_foundation'] = array();
        foreach($view['selected_validators'] as $key=>$row)
        {
            if (in_array($key,$view['foundation_list']))
            {
                $view['selected_foundation'][$key] = $view['selected_validators'][$key];
                unset($view['selected_validators'][$key]);
            }
        }
        $view['selected_validators']['foundation'] = array(
            'COUNT'=>0,
            'EL'=>0,
            'RNP'=>0,
            'FOUNDATION_COUNT'=>0
        );
        foreach($view['selected_foundation'] as $key=>$foundation)
        {
            $view['selected_validators']['foundation']['COUNT'] += $foundation['COUNT'];
            $view['selected_validators']['foundation']['EL'] += $foundation['EL'];
            $view['selected_validators']['foundation']['RNP'] += $foundation['RNP'];
            $view['selected_validators']['foundation']['FOUNDATION_COUNT']++;
        }


        function get_validator_info($address,$validator_info_list)
        {

            if (isset($validator_info_list[$address]))
            {
                return $validator_info_list[$address];
            }
            return false;
        }
        $view['validator_info_list'] = $this->config->item('validator_list');




        //echo '===================================<br/><br/>';
        //echo '-------------------------------------------<br/>';
        //echo 'Selected EL: '.array_sum($view['EL_list']).' validators<br/>';
        //echo 'Selected RNP: '.array_sum($view['RNP_list']).' validators<br/>';
        echo '-------------------------------------------<br/>';
        echo '👉 Selected Validators 👈<br/>';
        echo 'Total: '.(count($view['selected_validators'])-2).' Validators | '.array_sum($view['EL_list']).' EL | '.array_sum($view['RNP_list']).' RNP<br/>';
        echo '-------------------------------------------<br/>';
        foreach($view['selected_validators'] as $key=>$row)
        {
            if (!$key) continue;
            $validator = get_validator_info($key,$view['validator_info_list']);

            $tmp_array = array();
            // Create EL / RNP
            $tmp_array = array();
            if ($row['EL'])
            {
                $tmp_array[] = $row['EL'].' EL';
            }
            if ($row['RNP'])
            {
                $tmp_array[] = $row['RNP'].' RNP';
            }

            $string = '('.implode(', ',$tmp_array).')';

            if ($key !='foundation')
            {
                if ($validator)
                {
                    echo $row['COUNT'].' times - '. $validator['name'].' '.$string;
                }
                else{

                    echo $row['COUNT'].' times - '.substr($key,0,5).'...'.substr($key,-4).' '.$string;
                }
            }
            else
                {
                    echo $row['COUNT'].' times - FOUNDATION\'S NODE (Non-Reward)';
                }
            echo '<br/>';
        }
        echo '-------------------------------------------<br/>';
        $pay_reward = $this->_getEpochIncentivePayDetail();

        $pay_reward = (unserialize($pay_reward));
        $total_delegators = 0;
        $total_incentive = 0;
        $total_validator_incentive = 0;
        foreach($pay_reward as $reward)
        {
            if (isset($reward['delegators']))
            foreach($reward['delegators'] as $delegator)
            {
                //echo 'haha';
                $total_delegators++;
                $total_incentive+=round(hexdec($delegator['incentive'])/WAN_DIGIT,18);
            }
            $total_validator_incentive += round(hexdec($reward['incentive'])/WAN_DIGIT,18);
        }
        echo '🏆 Reward payout for previous epoch '.($selected_result['epochId']-1).' 🏆<br/>';
        //echo '-------------------------------------------<br/>';
        //echo 'Delegators #: '.number_format($total_delegators).' delegators<br/>';
        echo 'Delegators: '.number_format($total_incentive,2).' WAN ('.number_format($total_delegators).' delegators)<br/>';
        echo 'Validators: '.number_format($total_validator_incentive,2).' WAN<br/>';
        echo '-------------------------------------------<br/>';
        //echo '<pre>';
        $all_validator = array();

        foreach($pay_reward as $reward)
        {
            if (!$reward['address']) continue;
            if (!isset($all_validator[$reward['address']]))
            {
                $all_validator[$reward['address']]['validator']=0;
                $all_validator[$reward['address']]['delegator']=0;
            }
            $all_validator[$reward['address']]['validator'] += hexdec($reward['incentive'])/WAN_DIGIT;

            foreach($reward['delegators'] as $delegator)
            {
                $all_validator[$reward['address']]['delegator'] += hexdec($delegator['incentive'])/WAN_DIGIT;
            }
            //$validator = get_validator_info($reward['address'],$view['validator_info_list']);
            //echo $validator['name'].' - '.round(hexdec($reward['incentive'])/WAN_DIGIT,18).'<br/>';
        }

        // Get Leader in previous Epoch //
        $leader = unserialize($this->_getLeaderGroup(1));
        $leader_list = array();
        foreach ($leader as $row)
        {
            if (!isset($leader_list[$row['secAddr']]))
            {
                $leader_list[$row['secAddr']] = array(
                    'RNP'=>0,
                    'EL'=>0
                );
            }
            if ($row['type']==0)
            {
                $leader_list[$row['secAddr']]['EL']++;
            }
            else{
                $leader_list[$row['secAddr']]['RNP']++;
            }
        }

        $leader = unserialize($this->_getLeaderGroup(2));

        $leader_yesterday_list = array();


        foreach ($leader as $row)
        {
            if (!isset($leader_yesterday_list[$row['secAddr']]))
            {
                $leader_yesterday_list[$row['secAddr']] = array(
                    'RNP'=>0,
                    'EL'=>0
                );
            }
            if ($row['type']==0)
            {
                $leader_yesterday_list[$row['secAddr']]['EL']++;
            }
            else{
                $leader_yesterday_list[$row['secAddr']]['RNP']++;
            }
        }
        //print_r($leader_list);
        //die();

        // Sorting //
        foreach($all_validator as $address => $reward)
        {
            $all_validator[$address]['JOBS_COUNT'] = 0;
            if ($leader_list[$address]['EL'])
            {
                $all_validator[$address]['JOBS_COUNT'] += $leader_list[$address]['EL'];
            }
            if ($leader_list[$address]['RNP'])
            {
                $all_validator[$address]['JOBS_COUNT'] += $leader_list[$address]['RNP'];
            }
            if ($leader_yesterday_list[$address]['EL'])
            {
                $all_validator[$address]['JOBS_COUNT'] += $leader_yesterday_list[$address]['EL'];
            }
        }
        uasort($all_validator, function($a, $b) {
            return $b['JOBS_COUNT'] - $a['JOBS_COUNT'];
        });

        foreach($all_validator as $address => $reward)
        {

            $validator = get_validator_info($address,$view['validator_info_list']);
            if (!$validator['name'])
            {
                $validator['name'] = substr($address,0,5).'...'.substr($address,-4);
            }

            // Create EL / RNP
            $tmp_array = array();
            if ($leader_list[$address]['EL'])
            {
                $tmp_array[] = $leader_list[$address]['EL'].' EL';
            }
            if ($leader_list[$address]['RNP'])
            {
                $tmp_array[] = $leader_list[$address]['RNP'].' RNP';
            }
            if ($leader_yesterday_list[$address]['EL'])
            {
                $tmp_array[] = $leader_yesterday_list[$address]['EL'].' SL';
            }
            $string = '('.implode(', ',$tmp_array).')';

            //echo $reward['JOBS_COUNT'].'<br/>';
            echo $validator['name'].' - '.number_format(($reward['validator']+$reward['delegator']),2).' WAN '.$string.'<br/>';
        }
        echo '-------------------------------------------<br/>';
        echo '🔗 Full info at www.wanstakeinsight.com<br/>';
        //echo '❤ Support us by staking on validator "CryptoFennec" :3';
    }
	
	private function _api_error()
	{
		echo '<center style="font-size:25px;font-family:arial"><br/><br/><img src="./assets/logo.png"> <br/><br/>WAN STAKE INSIGHT</center><br/>';
		echo '<center style="font-size:20px;font-family:arial"><b>API Error!</b> <br/>Please try again</center>';
		die();
	}



	public function api($method,$value1='')
    {
        header('Content-Type: application/json; charset=utf-8');
        switch($method)
        {

            // The old one //

            case 'currentStakerInfo':
                $result = unserialize($this->_getCurrentStakerInfo());
                if ($value1)
                {
                   $is_found = false;
                    foreach($result as $validator)
                    {
                        if ($validator['address'] == trim(strtolower($value1)))
                        {
                            echo json_encode($validator);
                            $is_found = true;
                        }
                    }
                    if (!$is_found)
                    {
                        echo json_encode(array());
                        die();
                    }
                }
                else{
                    echo json_encode($result);
                }
                break;
            default:
                show_404();

        }
    }
	
	public function sticker()
	{
	    error_reporting(0);
        //$this->output->cache(60);
        // Validator Address //
        // Delegated amount //
        // Highest Delegated  //
        // Average Delegated
        // Sum of Partner In //
        // Fee Rate //
        // Max Fee Rate //

        // Pending Stake Out//
        $validator_list = array();
        $non_delegate_validator_list = array();

        $view['max_fee_rate_list'] = array();
        $view['fee_rate_list'] = array();
        $view['delegate_amount_list'] = array();
        $view['total_staked'] = 0;
        $view['pending_stake_out_list'] = array();
        $view['non_delegate_validator_amount_list'] = array();
        $view['delegate_validator_amount_list'] = array();
        $view['total_voting_power'] =0;


        $result = unserialize($this->_getCurrentStakerInfo());

        foreach($result as $row)
        {
            //echo '<pre>';
            //print_r($row);
            //echo '</pre>';
            //die();

            $selfstake = round(hexdec($row['amount'])/WAN_DIGIT,18);
            foreach($row['partners'] as $p_row)
            {
                $selfstake+=round(hexdec($p_row['amount'])/WAN_DIGIT,18);
            }

            //echo $selfstake.'<br/>';
            $selfstake = ''.$selfstake;
            if ($selfstake < 50000 || $row['feeRate']==10000)
            {

               // echo '>'.$selfstake.'<br/>';
                $view['non_delegate_validator_amount_list'][$row['address']] = $selfstake;
                $view['total_staked'] += $selfstake;


                // Calculate non d validator
                $non_delegate_validator_list[$row['address']]['address'] = $row['address'];
                $selfstake = round(hexdec($row['amount'])/WAN_DIGIT,18);
                $non_delegate_validator_list[$row['address']]['selfStake'] = $selfstake;
                $non_delegate_validator_list[$row['address']]['partnerAmount'] = array();
                $non_delegate_validator_list[$row['address']]['sumVotingPower'] = round(hexdec($row['votingPower'])/WAN_DIGIT,18);

                $non_delegate_validator_list[$row['address']]['stakingEpoch'] = $row['stakingEpoch'];
                $non_delegate_validator_list[$row['address']]['lockEpochs'] = $row['lockEpochs'];
                $non_delegate_validator_list[$row['address']]['nextLockEpochs'] = $row['nextLockEpochs'];


                // Sum Voting Power //
                foreach($row['partners'] as $p_row)
                {

                    $non_delegate_validator_list[$row['address']]['partnerAmount'][] = round(hexdec($p_row['amount'])/WAN_DIGIT,18);
                    $selfstake+=round(hexdec($p_row['amount'])/WAN_DIGIT,18);
                    $non_delegate_validator_list[$row['address']]['sumVotingPower'] += round(hexdec($p_row['votingPower'])/WAN_DIGIT,18);

                    $view['total_voting_power'] += hexdec($p_row['votingPower'])/WAN_DIGIT;
                }
                foreach($row['clients'] as $c_row)
                {
                    $view['total_voting_power'] += hexdec($c_row['votingPower'])/WAN_DIGIT;
                }
                $view['total_voting_power'] += hexdec($row['votingPower'])/WAN_DIGIT;


                continue;
            }

            $validator_list[$row['address']]['address'] = $row['address'];
            $view['fee_rate_list'][] = $validator_list[$row['address']]['feeRate'] = $row['feeRate'];
            $view['max_fee_rate_list'][] = $validator_list[$row['address']]['maxFeeRate'] = $row['maxFeeRate'];

            // Reset selfstake //
            $selfstake = round(hexdec($row['amount'])/WAN_DIGIT,18);
            $validator_list[$row['address']]['selfStake'] = $selfstake;

            $validator_list[$row['address']]['partnerAmount'] = array();

            $validator_list[$row['address']]['sumVotingPower'] = round(hexdec($row['votingPower'])/WAN_DIGIT,18);
           // die();

            // Epoch //
            $validator_list[$row['address']]['stakingEpoch'] = $row['stakingEpoch'];
            $validator_list[$row['address']]['lockEpochs'] = $row['lockEpochs'];
            $validator_list[$row['address']]['nextLockEpochs'] = $row['nextLockEpochs'];

            foreach($row['partners'] as $p_row)
            {
                $validator_list[$row['address']]['partnerAmount'][] = round(hexdec($p_row['amount'])/WAN_DIGIT,18);
                $selfstake+=round(hexdec($p_row['amount'])/WAN_DIGIT,18);
                $validator_list[$row['address']]['sumVotingPower'] += round(hexdec($p_row['votingPower'])/WAN_DIGIT,18);
                $view['total_voting_power'] += hexdec($p_row['votingPower'])/WAN_DIGIT;
            }

            $view['delegate_validator_amount_list'][] = $selfstake;
            $view['total_staked'] += $selfstake;

            foreach($row['clients'] as $c_row)
            {
                if ($c_row['quitEpoch'] != 0)
                {
                    $view['pending_stake_out_list'][] = round(hexdec($c_row['amount'])/WAN_DIGIT,18);
                    $validator_list[$row['address']]['stake_out'][] = round(hexdec($c_row['amount'])/WAN_DIGIT,18);
                }
                $view['total_staked'] += $view['delegate_amount_list'][] = $validator_list[$row['address']]['delegatorAmount'][] = round(hexdec($c_row['amount'])/WAN_DIGIT,18);
                $validator_list[$row['address']]['sumVotingPower'] += round(hexdec($c_row['votingPower'])/WAN_DIGIT,18);
                $view['total_voting_power'] += hexdec($c_row['votingPower'])/WAN_DIGIT;
            }

            $view['total_voting_power'] += hexdec($row['votingPower'])/WAN_DIGIT;

            if (isset($validator_list[$row['address']]['delegatorAmount']))
            {
                $validator_list[$row['address']]['sumDelegatorAmount'] = array_sum($validator_list[$row['address']]['delegatorAmount']);
            }
            else{
                $validator_list[$row['address']]['sumDelegatorAmount'] = 0;
            }
        }
        //die();

        // Sort by stake //
        usort($validator_list, function($a, $b) {
            return $b['sumDelegatorAmount'] - $a['sumDelegatorAmount'];
        });

        // Sort by stake //
        usort($non_delegate_validator_list, function($a, $b) {
            return $b['sumVotingPower'] - $a['sumVotingPower'];
        });

        $view['validator_count'] = count($validator_list);
		$view['pending_stake_out_count'] = count($view['pending_stake_out_list']);
		$view['pending_stake_out_sum'] = array_sum($view['pending_stake_out_list']);

        $view['non_delegate_validator_count'] = count($view['non_delegate_validator_amount_list']);
		$view['delegate_validator_count'] = count($view['delegate_validator_amount_list']);
        //$view['validator_info_list'] = $this->config->item('validator_list');
       
	   $view['delegated_amount'] = array_sum($view['delegate_amount_list']);
	   $view['delegated_count'] = count($view['delegate_amount_list']);
	   
	   unset($view['total_voting_power']);
	   unset($view['validator_count']);
	   unset($view['delegate_amount_list']);
	   unset($view['fee_rate_list']);
	   unset($view['max_fee_rate_list']);
	   unset($view['pending_stake_out_list']);
	   unset($view['non_delegate_validator_amount_list']);
	   unset($view['delegate_validator_amount_list']);

        $epochinfo = $this->_getCurrentEpochInfo();

        $epochinfo = unserialize($epochinfo);
        $view['current_epoch_id'] = $epochinfo['epochId'];

		echo json_encode($view);
	}
}
