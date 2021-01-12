<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use WebSocket\Client;
class Test extends CI_Controller {
    private function _getLeaderGroup($epochId)
    {
        $client = new Client($this->config->item('iwan_client'));
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);

        $method = 'getLeaderGroupByEpochID';

        $params_array = array(
            //'chainType'=>'WAN',
            'epochID'=>(int)$epochId,
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
        $client->send($query_string);



        $result = json_decode($client->receive(),true);
        //print_r($query_string);
        //die();
        $result['result']['epochId'] = $epochId;
        // Save into the cache for 5 minutes
        if (isset($result['result']))
        {
            $result = serialize($result['result']);
        }
        else{
            $result = '';
        }

        return $result;
    }
    public function getLeaderByEpoch($epoch_id)
    {
        error_reporting(0);

        // Get Price //
        //$price = json_decode($this->_getGekcoPrice(),true);
        $selected_result = unserialize($this->_getLeaderGroup($epoch_id));
        echo '🔍 Epoch '.$epoch_id.' insight 🔎<br/><br/>';

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

        echo '-------------------------------------------<br/>';
        echo '👉 Selected Validators 👈<br/>';
        echo 'Total: '.array_sum($view['EL_list']).' EL and '.array_sum($view['RNP_list']).' RNP<br/>';
        echo '-------------------------------------------<br/>';
        foreach($view['selected_validators'] as $key=>$row)
        {
            if (!$key) continue;
            $validator = get_validator_info($key,$view['validator_info_list']);
            if ($key !='foundation')
            {
                if ($validator)
                {
                    echo $row['COUNT'].' times - '. $validator['name'];
                }
                else{
                    echo $row['COUNT'].' times - UNKNOW / NDV';
                }
            }
            else
            {
                echo $row['COUNT'].' times - FOUNDATION\'S NODE (Non-Reward)';
            }
            echo '<br/>';
        }
        echo '-------------------------------------------<br/>';
        echo '🔗 See more: www.wanstakeinsight.com';
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
            $client->send($query_string);
            $result = json_decode($client->receive(),true);

            // Save into the cache for 5 minutes
            if (isset($result['result']) && $result['result'])
            {
                $result = serialize($result['result']);

                $this->cache->save($method.'_'.$block_height, $result, 259200000);
            }
            else{
                $result = '';
                $this->output->delete_cache();
            }
        }
        return $result;
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



            $client->send($query_string);
            $result = json_decode($client->receive(),true);


            // Save into the cache for 1 days
            if (isset($result['result']) && $result['result'])
            {
                $result['result']['epochId'] = $epoch;
                $result = serialize($result['result']);
                $this->cache->save($method.'_'.$epoch, $result, 259200000);
                sleep(1);
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
        $this->load->driver('cache', array('adapter' => 'file'));
        $method = 'getEpochIncentiveBlockNumber';
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

            $client->send($query_string);
            $result = json_decode($client->receive(), true);

            if (isset($result['result']) && $result['result']) {

                $result = $result['result'];

                $this->cache->save($method . '_' . $epochId, $result, 259200000);
            } else {
                $result = '';
                $this->output->delete_cache();
            }
        }
        return $result;
    }

    public function testBlock($epoch)
    {
        echo $this->_getLeaderGroupByEpoch((int)$epoch);
        //echo $block_height;
    }



    public function sum_reward($epoch_start=0, $epoch_stop=0, $sort='ratio',$invested=1000)
    {

        if ($epoch_stop==0)
        {
            $epochinfo = $this->_getCurrentEpochInfo();
            $epochinfo = unserialize($epochinfo);
            $epoch_stop = $epochinfo['epochId'];

            if ($epoch_start == 0)
            {
                $epoch_start = $epoch_stop-30;
            }
        }

        error_reporting(0);
        set_time_limit(0);

        // Sort by - ratio , epoch , job //
        // First Reward 18143 //
        $epoch_count = $epoch_stop-$epoch_start+1;
        echo '<b>Epoch Amount: </b>'.$epoch_count.' Epoch ('.$epoch_start.' to '.$epoch_stop.')<br/>';

        // Start at 18141 //
        $validators = array();
        $delegating = array();
        $delegating_reward = array();
        $delegate_ratio = array();

        //echo '<pre>';
        function get_validator_info($address,$validator_info_list)
        {

            if (isset($validator_info_list[$address]))
            {
                return $validator_info_list[$address];
            }
            return $address;
        }
		
		
		// Gather!! //
		if (false)
		for($epoch=$epoch_start;$epoch<=$epoch_stop;$epoch++) {
			//echo (int)$epoch.'<br/>';
			//ob_flush(); flush();
			//echo $this->_getLeaderGroupByEpoch((int)$epoch);
			
			
			
			
			$block_height = $this->_getBlockIncentive((int)$epoch);
			
			echo '<pre>';
			print_r(unserialize($this->_getStakerInfo($block_height)));
			die();
			
			$client = new Client($this->config->item('iwan_client'));
            $secret = $this->config->item('iwan_secret');
            $timestamp = round(microtime(true) * 1000);
            $this->load->driver('cache', array('adapter' => 'file'));

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
                $client->send($query_string);
                $result = json_decode($client->receive(), true);

                // Save into the cache for 1 days
                if (isset($result['result']) && $result['result']) {
                    $result['result']['epochId'] = $epoch;
                    $result = serialize($result['result']);

                    $this->cache->save($method . '_' . $epoch, $result, 259200000);
                } else {
                    $result = '';

                }
            }
			
		}
		
		
		//die('haha');
		// Endof Gather!! //
		
        for($epoch=$epoch_start;$epoch<=$epoch_stop;$epoch++) {

            // Get Stake & Fee //
            $block_height = $this->_getBlockIncentive((int)$epoch);
            if ($block_height)
            {

                $StakerInfo = unserialize($this->_getStakerInfo($block_height));
                foreach ($StakerInfo as $staker)
                {
                    foreach($staker['clients'] as $client)
                    {
                        if (!isset($delegating[$staker['address']][$epoch]))
                        {
                            $delegating[$staker['address']][$epoch] = 0;
                            $delegate_ratio[$staker['address']][$epoch] = 0;
                            $delegating_reward[$staker['address']][$epoch] = 0;
                        }
                        $delegating[$staker['address']][$epoch] += hexdec($client['amount'])/WAN_DIGIT;
						
						
                    }
					
					$partner_staked = 0;
					foreach($staker['partners'] as $partner)
					{
						$partner_staked+=hexdec($partner['amount'])/WAN_DIGIT;
					}
					
					$validators[$staker['address']]['selfStake'] = (hexdec($staker['amount'])/WAN_DIGIT)+$partner_staked;
					
					
                }

            }



            $client = new Client($this->config->item('iwan_client'));
            $secret = $this->config->item('iwan_secret');
            $timestamp = round(microtime(true) * 1000);
            $this->load->driver('cache', array('adapter' => 'file'));

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
                $client->send($query_string);
                $result = json_decode($client->receive(), true);

                // Save into the cache for 1 days
                if (isset($result['result']) && $result['result']) {
                    $result['result']['epochId'] = $epoch;
                    $result = serialize($result['result']);

                    $this->cache->save($method . '_' . $epoch, $result, 259200000);
                } else {
                    $result = '';

                }
            }

            // Do select //
            $pay_reward = unserialize($result);

            if ($pay_reward)
            {
                $epoch_selected = array();
                foreach($pay_reward as $reward)
                {
                    if (!isset($reward['address']) || !$reward['address']) continue;

                    if (!isset($validators[$reward['address']]))
                    {
                        $validators[$reward['address']]['validator_reward'] = 0;
                        $validators[$reward['address']]['delegator_reward'] = 0;
                        $validators[$reward['address']]['epoch_selected'] = 0;
                        $validators[$reward['address']]['working_selected'] = 0;
                        $validators[$reward['address']]['EL'] = 0;
                        $validators[$reward['address']]['RNP'] = 0;
                        $validators[$reward['address']]['SL'] = 0;


                    }
                    if (!isset($epoch_selected[$reward['address']]))
                    {
                        $epoch_selected[$reward['address']] = 0;
                    }

                    $validators[$reward['address']]['validator_reward'] += hexdec($reward['incentive'])/WAN_DIGIT;
                    $epoch_selected[$reward['address']]++;


                    if (isset($reward['delegators'])) {
                        foreach($reward['delegators'] as $de_reward) {

                            $validators[$reward['address']]['delegator_reward'] += hexdec($de_reward['incentive']) / WAN_DIGIT;
                            if (!isset($delegating_reward[$reward['address']][$epoch]))
                            {
                                $delegating_reward[$reward['address']][$epoch] = 0;
                            }
                            $delegating_reward[$reward['address']][$epoch] += hexdec($de_reward['incentive']) / WAN_DIGIT;
                        }
                    }


                }

                foreach($epoch_selected as $address=>$selected)
                {
                    if ($selected > 0)
                    {
                        $validators[$address]['epoch_selected']++;
                        $validators[$address]['working_selected'] += $selected;
                    }
                }

                // Get EL RNP SL //

                //$leaders = unserialize($this->_getLeaderGroupByEpoch((int)$epoch));
                foreach($leaders as $leader)
                {
                    if (!isset($validators[$leader['secAddr']])) continue; // case of foundation
                    if ($leader['type']==0)
                    {
                        $validators[$leader['secAddr']]['EL']++;
                    }
                    else{
                        $validators[$leader['secAddr']]['RNP']++;
                    }
                }


            }

        }

        // Calculate to total incentive //
        $total_validator_reward = 0;
        $total_delegator_reward = 0;
        foreach($validators as $validator)
        {
            $total_validator_reward+= $validator['validator_reward'];
            $total_delegator_reward+= $validator['delegator_reward'];

        }


        //echo '<pre>';
        //print_r($delegating_reward);


        foreach($delegating as $address=>$delegate_epoch)
        {
            foreach ($delegate_epoch as $epoch=>$delegated)
            {
                // Calculate Ratio //

                $delegate_ratio[$address][$epoch] = $delegating_reward[$address][$epoch]/$delegated*1000000;

            }
        }

        // Recal Ratio & SL//
        $predict_ratio = 0;
        $count_predict_ratio = 0;
        $total_el = 0;
        $total_rnp = 0;
        $total_sl = 0;
        foreach($validators as $address=>$validator)
        {
            if (!isset($delegate_ratio[$address]))
            {
                $delegate_ratio[$address] = array();
            }
            $validators[$address]['delegate_ratio'] = (array_sum($delegate_ratio[$address]));

            // Predict //
            $predict_ratio +=array_sum($delegate_ratio[$address]);
            foreach($delegate_ratio[$address] as $row)
            {
                 $count_predict_ratio++;
            }


           // $validators[$address]['SL'] = $validators[$address]['EL'];
           // $total_sl +=$validators[$address]['SL'];
            $total_el +=$validators[$address]['EL'];
            $total_rnp += $validators[$address]['RNP'];
            //echo $address.' - '.$validators[$address]['SL'];
            //die();
        }





        //print_r($delegate_ratio);
        //die();

        // Sort by stake //
        switch(strtolower($sort))
        {

            case 'el':
                uasort($validators, function($a, $b) {
                    if ($b['EL']!=$a['EL'])
                    {
                        return $b['EL'] - $a['EL'];
                    }
                    else{
                        return $b['working_selected'] - $a['working_selected'];
                    }
                });

                break;

            case 'rnp':
                uasort($validators, function($a, $b) {
                    if ($b['RNP']!=$a['RNP'])
                    {
                        return $b['RNP'] - $a['RNP'];
                    }
                    else{
                        return $b['working_selected'] - $a['working_selected'];
                    }
                });

                break;
                /*
            case 'sl':
                uasort($validators, function($a, $b) {
                    if ($b['SL']!=$a['SL'])
                    {
                        return $b['SL'] - $a['SL'];
                    }
                    else{
                        return $b['working_selected'] - $a['working_selected'];
                    }
                });

                break;
                */

            case 'epoch':
                uasort($validators, function($a, $b) {
                    if ($b['epoch_selected']!=$a['epoch_selected'])
                    {
                        return $b['epoch_selected'] - $a['epoch_selected'];
                    }
                    else{
                        return $b['working_selected'] - $a['working_selected'];
                    }
                });

                break;
            case 'job':

                uasort($validators, function($a, $b) {
                    if ($b['working_selected']!=$a['working_selected']) {
                        return $b['working_selected'] - $a['working_selected'];
                    }
                    else{
                        return $b['epoch_selected'] - $a['epoch_selected'];
                    }
                });

                break;
            default:
                uasort($validators, function($a, $b) {
                    return $b['delegate_ratio'] - $a['delegate_ratio'];
                });
            break;
        }
        $validator_info_list = $this->config->item('validator_list');
        ?>
        <style>

            td,th
            {
                padding:5px 10px;
                text-align:left;
                font-size:14px;
                font-family:arial;
                border:1px solid #eee;



            }
            table{
                border-spacing: 0px;
            }
        </style>
        <?php

        echo '<b>Annual Reward:</b> '. number_format($predict_ratio/$count_predict_ratio*100/1000000*365,2).'% <br/>';
        echo '<b>Selected:</b> '.count($validators).' Validators<br/>';
        echo '<b>Total EL/RNP:</b> '.number_format($total_el).' EL | '.number_format($total_rnp).' RNP<br/>';
        echo '<b>Total Rewards:</b> '.number_format($total_validator_reward+$total_delegator_reward,2).' WAN (Validator: '.number_format($total_validator_reward,2).' WAN | Delegator: '.number_format($total_delegator_reward,2).' WAN)<br/>';

        echo '=============================';
        echo '<table><thead><tr><th>V.Name</th><th>Selected Epochs</th><th>Selected Jobs</th><th>EL / RNP</th><th>Validator Reward</th><th>V.Reward %</th><th>Delegator Reward</th><th>Est. Reward of Invested 100 WAN</th><th>Est. Reward of Invested '.number_format($invested).' WAN</th></tr></thead><tbody>';
        foreach($validators as $address=>$validator)
        {
			if ($validator['validator_reward'] == 0) continue;
            $info = get_validator_info($address,$validator_info_list);
            echo '<tr>';
            echo '<td>'.(isset($info['name'])?$info['name']:$info).'</td>';
            echo '<td>'.$validator['epoch_selected'].'</td>';
			echo '<td>'.number_format($validator['working_selected']).'</td>';
            echo '<td>'.number_format($validator['EL']).' EL | '.number_format($validator['RNP']).' RNP </td>';
            echo '<td>'.number_format($validator['validator_reward'],2).' WAN</td>';
			echo '<td>'.number_format($validator['validator_reward']*100/$validator['selfStake'],2).'</td>';
            echo '<td>'.number_format($validator['delegator_reward'],2).' WAN</td>';
            echo '<td>'.number_format($validator['delegate_ratio']*100/1000000,3).' WAN</td>';
            echo '<td>'.number_format($validator['delegate_ratio']*$invested/1000000,3).' WAN</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
/*
    public function surprise($epoch_start, $epoch_stop)
    {
        // Start at 18141 //
        $last_result = array();
        echo '<pre>';
        for($epoch=$epoch_start;$epoch<=$epoch_stop;$epoch++) {


            $client = new Client($this->config->item('iwan_client'));
            $secret = $this->config->item('iwan_secret');
            $timestamp = round(microtime(true) * 1000);
            $this->load->driver('cache', array('adapter' => 'file'));

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
                $client->send($query_string);
                $result = json_decode($client->receive(), true);

                // Save into the cache for 1 days
                if (isset($result['result']) && $result['result']) {
                    $result['result']['epochId'] = $epoch;
                    $result = serialize($result['result']);
                    $this->cache->save($method . '_' . $epoch, $result, 259200000);
                } else {
                    $result = '';

                }
            }

            // Do select //
            $pay_reward = unserialize($result);
            $delegators = array();
            foreach($pay_reward as $reward)
            {
                if ($reward['address'] != '0x2a2fee5d3aefdcddd8247e3ea094a591323f3879') continue;
                if (isset($reward['delegators']))
                foreach($reward['delegators'] as $delegator)
                {
                    $delegators[] = $delegator['address'];
                }
            }

            //print_r($delegators);


            if (!$last_result)
            {

                $last_result = array_unique($delegators);
            }

            // Prune //
            $tmp_last_result = array();
            foreach($last_result as $key => $row)
            {
                if (in_array($row,$delegators))
                {
                    $tmp_last_result[] = $row;
                }
            }
            $last_result = $tmp_last_result;

            print_r($last_result);

        }
    }
*/
	public function test_heavy()
	{
		error_reporting(-1);
		ini_set('display_errors', 1);
		set_time_limit(300);
		$client = new Client($this->config->item('iwan_client'), ['timeout' => 60]);
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);
		
		$method = 'getRegisteredValidator';
		$params_array = array(
			'chainType'=>'WAN',
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
		
		
		
		$client->send($query_string);
		$result = json_decode($client->receive(),true);
		
		echo '<pre>';
		print_r($result);
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
            $client->send($query_string);
            $result = json_decode($client->receive(),true);

            // Save into the cache for 5 minutes
            if (isset($result['result']) && $result['result'])
            {
                $result = serialize($result['result']);
                $this->cache->save($method, $result, 1800);
            }
            else{
                $result = '';
                $this->output->delete_cache();
            }
        }
        return $result;
    }

    private function _getBalance($address)
    {
        $client = new Client($this->config->item('iwan_client'));
        $secret = $this->config->item('iwan_secret');
        $timestamp = round(microtime(true) * 1000);
        $this->load->driver('cache', array('adapter' => 'file'));

        $method = 'getMultiBalances';
        if ( ! $result = $this->cache->get($method))
        {
            $params_array = array(
                'chainType'=>'WAN',
                'address'=>$address,
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
            $client->send($query_string);
            $result = json_decode($client->receive(),true);

            //print_r($result);

            // Save into the cache for 5 minutes
            if (isset($result['result']) && $result['result'])
            {
                $result = serialize($result['result']);
                //$this->cache->save($method, $result, 1800);
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
            $client->send($query_string);
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

    private function _getActivity($epoch_dec=0)
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
        $method = 'getActivity';

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
            $client->send($query_string);
            $result = json_decode($client->receive(),true);

            print_r($result);

            // Save into the cache for 1 days
            if (isset($result['result']) && $result['result'])
            {
                $result['result']['epochId'] = $epoch;
                $result = serialize($result['result']);
                $this->cache->save($method.'_'.$epoch, $result, 86400);
            }
            else{
                $result = '';
                $this->output->delete_cache();
            }
        }
        return $result;
    }

    public function validator_alert()
    {
        $result = unserialize($this->_getCurrentStakerInfo());
        $validators = array();
        foreach($result as $row)
        {
            $validators[] = $row['address'];
        }

        $result = unserialize($this->_getBalance($validators));

        echo 'OUT OF WAN VALIDATORS:<br/>';
        echo '-------------------------------------<br/>';
        foreach($result as $address=>$wan)
        {
            if ($wan<=0.2)
            echo $address.' => '.($wan/WAN_DIGIT).' WAN<br/>';
        }

        $result = unserialize($this->_getActivity());
        echo 'EPOCH '.$result[epochId].' - ACTIVITY PROBLEM:<br/>';
        echo '-------------------------------------<br/>';
        echo '<pre>';
        print_r($result);

    }

    public function stakeout_list()
    {
        function get_validator_info($address,$validator_info_list)
        {

            if (isset($validator_info_list[$address]))
            {
                return $validator_info_list[$address];
            }
            return $address;
        }
        $result = unserialize($this->_getCurrentStakerInfo());
        $parse_array = array();
        foreach($result as $row)
        {

            foreach($row['clients'] as $c_row)
            {
                if ($c_row['quitEpoch'] != 0)
                {
                    $parse_array[$row['address']][$c_row['address']] = number_format(round(hexdec($c_row['amount'])/WAN_DIGIT,2));
                }

            }



        }
       // echo '<pre>';
       // print_r($parse_array);die();

        $view['validator_info_list'] = $this->config->item('validator_list');



        foreach($parse_array as $vaddress=>$row)
        {
            $validator = get_validator_info($vaddress,$view['validator_info_list']);
            echo '--------------------------------<br/>';
            echo 'Validator: '.$validator['name'].'<br/>';
            echo '--------------------------------<br/>';
            foreach ($row as $c_address=>$c_row)
            {
                echo $c_address.' - '.$c_row.'<br/>';
            }
            echo '<br/><br/>';
        }
    }
}