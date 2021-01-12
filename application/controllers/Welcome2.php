<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use WebSocket\Client;
class Welcome extends CI_Controller {

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
            if (isset($result['result']))
            {
                $result = serialize($result['result']);
                $this->cache->save($method, $result, 600);
            }
            else{
                $result = '';
            }
        }

        return $result;
    }

    private function _getLeaderGroup()
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
        if ( ! $result = $this->cache->get($method))
        {
            $params_array = array(
                //'chainType'=>'WAN',
                'epochID'=>$epochinfo['epochId'],
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
            $result['result']['epochId'] = $epochinfo['epochId'];
            // Save into the cache for 5 minutes
            if (isset($result['result']))
            {
                $result = serialize($result['result']);
                $this->cache->save($method, $result, 600);
            }
            else{
                $result = '';
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
            $client->send($query_string);
            $result = json_decode($client->receive(),true);

            // Save into the cache for 5 minutes
            if (isset($result['result']))
            {
                $result = serialize($result['result']);
                $this->cache->save($method, $result, 1800);
            }
            else{
                $result = '';
            }
        }
        return $result;
    }
	public function index()
	{
	    error_reporting(0);
        $this->output->cache(5);
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



        $result = unserialize($this->_getCurrentStakerInfo());

        foreach($result as $row)
        {

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
                $view['non_delegate_validator_amount_list'][] = $selfstake;
                $view['total_staked'] += $selfstake;
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

            foreach($row['partners'] as $p_row)
            {
                $validator_list[$row['address']]['partnerAmount'][] = round(hexdec($p_row['amount'])/WAN_DIGIT,18);
                $selfstake+=round(hexdec($p_row['amount'])/WAN_DIGIT,18);
                $validator_list[$row['address']]['sumVotingPower'] += round(hexdec($p_row['votingPower'])/WAN_DIGIT,18);
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

            }

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

        $view['validator_list'] = $validator_list;
        $view['validator_info_list'] = $this->config->item('validator_list');
       // echo count($validator_list);
       // die();
        //print_r($delegator_list);
       // echo '<pre>';
       //  print_r($view['non_delegate_validator_amount_list']);

      // die();


		$this->load->view('welcome_message',$view);
	}

    public function selected_validators()
    {
        error_reporting(0);
        $this->output->cache(5);

        $result = unserialize($this->_getLeaderGroup());

       // echo '<pre>';
        // 0 is
        //print_r($result);
        //die();


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

        $view['validator_info_list'] = $this->config->item('validator_list');
        $this->load->view('selected_validator',$view);
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
}
