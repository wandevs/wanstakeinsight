<?php $this->load->view('header',array('web_title'=>$web_title))?>

        <div class="content" style="max-width:1200px;margin:0 auto;margin-bottom:50px;">
            <div class="row">
                <div class="col-sm-6 col-lg-4">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-12">
                                    <div class="numbers text-center">
                                        <p class="card-category">CURRENT WASP SUPPLY</b></p>
                                        <p class="card-title" style="font-size:25px;">
										
											<?php echo $wasp_supply?> WASP
                                        </p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
						<div class="card-footer text-center"><hr/><div class="stats">Unclaimed <?php echo $wasp_unclaimed?> WASP</div></div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-12">
                                    <div class="numbers text-center">
                                        <p class="card-category">EXCHANGE RATE</b></p>
                                        <p class="card-title" style="font-size:25px;">
										1 WAN = <?php echo $exchange_rate?> WASP
                                        </p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
						<div class="card-footer text-center"><hr/><div class="stats">Price ~<?php echo $wasp_price?> USD</div></div>
                    </div>
                </div>
				<div class="col-md-12 col-lg-4">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-12">
                                    <div class="numbers text-center">
                                        <p class="card-category">BURNED WASP</b></p>
                                        <p class="card-title" style="font-size:25px;">
										<?php echo $wasp_burned?> WASP
                                        </p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
						<div class="card-footer text-center"><hr/><div class="stats">Burned <?php echo $wasp_burned_percent?>% of supply</div></div>
                    </div>
                </div>
               </div>
			   
			   <div style="padding:30px;border-radius:15px;background:#00000008;margin-top:40px;position:relative">
			   <div style="position:absolute;right:0;top:0;padding:5px 20px;font-size:12px;background:#00000015;border-top-right-radius:15px;border-bottom-left-radius:15px;"><i class="fa fa-clock-o"></i> <?php echo $timestamp?></div>
			   <h3 style="text-align:center;"><b>WASP/WAN</b> POOL</h3>
			   <h5 style="margin-top: -30px;margin-bottom:30px;text-align: center;">Deposited ~<?php echo $pool_size?> USD</h5>
			   <div class="row">
			   
                <div class="col-md-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-12">
                                    <div class="numbers text-center">
                                        <p class="card-category">WASP AMOUNT</b></p>
                                        <p class="card-title" style="font-size:25px;">
											<?php echo $pool_wasp?> WASP
                                        </p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
						<div class="card-footer text-center"><hr/><div class="stats"><?php echo $pool_wasp_percentage?>% of WASP supply</div></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-12">
                                    <div class="numbers text-center">
                                        <p class="card-category">WAN AMOUNT</b></p>
                                        <p class="card-title" style="font-size:25px;">
											<?php echo $pool_wan?> WAN
                                        </p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
						<div class="card-footer text-center"><hr/><div class="stats"><?php echo $pool_wan_percentage?>% of WWAN supply</div></div>
                    </div>
                </div>
               </div>
			   </div>
        </div>
<style>
.main-panel>.content
{
	min-height:auto;
}
.wrapper
{
	background:#f4f3ef;
}
</style>

<?php $this->load->view('footer',array('js'=>'storeman_js'))?>


