<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use WebSocket\Client;
class Address extends CI_Controller {
	
	private function _getToplist($page)
	{
		// connect via SSL, but don't check cert
		$handle=curl_init('https://wan.tokenview.com/api/address/richrange/wan/'.$page.'/10');
		curl_setopt($handle, CURLOPT_VERBOSE, true);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		$content = curl_exec($handle);

		return json_decode($content,true); // show target page
	}
	
	
	public function index()
	{
		//$this->output->cache(720); // 12
		$view['lists'] = array();
		for($i=1;$i<=5;$i++)
		{
			$data = $this->_getToplist($i);
			$view['lists'] = array_merge($view['lists'],$data['data']);
			sleep(1);
		}
		//print_r($view['lists']);
		//die();
		$view['alias'] = $this->_alias();
		
		$view['web_title'] = 'TOP 50 HOLDERS';
        $this->load->view('address',$view);
	}
	
	function _alias()
	{
		$data = array(
			'0x8d175879c8c527cad640a22e3c6832fe5de190e3'=>'Binace: Cold Wallet #1',
			'0x00000000000000000000000000000000000000da' => 'Wanchain: POS Contract',
			'0x0000000000000000000000000000000000000000' => 'Black Hole / Burn',
			'0x4a2a82864c2db7091142c2078543fab0286251a9' => 'Wanchain Fund #1',
			'0xae8d9b975ec8df8359ea79e50e89b18601816ac3' => 'Wanchain Fund #2',
			'0x0da4512bb81fa50891533d9a956238dcc219abcf' => 'Wanchain Fund #3',
			'0x53d81a644a0d1081d6c6e8b25f807c2cfb6ede35' => 'Wanchain Fund #4',
			'0x0d99dc888eca001383eee55f681472d48f568a32' => 'Binance: Cold Wallet #2',
			'0x1e7450d5d17338a348c5438546f0b4d0a5fbeab6' => 'Wanchain: Storeman Contract',
			'0xdabd997ae5e4799be47d6e69d9431615cba28f48' => 'WWAN Token',
			'0x0a98Fb70939162725Ae66e626fE4B52Cff62C2E5' => 'Huobi: Hot Wallet',
			'0x731Bd7289b4191706b00f6f1877662B5E8697E82' => 'Binance: Hot Wallet',
			'0xe8548014f731194764af27c8edc9bbaa7d2f4c46' => 'WanLend: wWAN',
		);
		return array_change_key_case($data, CASE_LOWER);
	}
	
}