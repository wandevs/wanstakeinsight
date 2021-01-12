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
			   
			   <div class="row">
				<div class="col-md-12">
					<div class="card card-stats">
							<div class="card-body " style="padding-bottom:15px;">
							<div class="numbers text-center">
							<div id="chart-tools" style="display:flex;flex-direction:row;justify-content:space-between;align-items:center;width:100%">
								<?php
								$chart_title = '24 hrs';
								if ($this->uri->segment(3) == 'week')
								{
									$chart_title = '7 days';
								}
								elseif($this->uri->segment(3) == 'month')
								{
									$chart_title = '30 days';
								}
								?>
								
								<div><b><?php echo $chart_title?></b> Chart & Info</div>
									
									<div style="display:flex;flex-direction:row;flex-wrap:wrap;justify-content: center;">
										<div class="btn-group" style="font-size:0.5em;color:white;border-radius:20px;" role="group">
										  <a href="./token/wasp" class="btn <?php echo $this->uri->segment(3) == ''?'active':''?>">24 hrs</a>
										  <a href="./token/wasp/week" class="btn <?php echo $this->uri->segment(3) == 'week'?'active':''?>">7 days</a>
										  <a href="./token/wasp/month" class="btn <?php echo $this->uri->segment(3) == 'month'?'active':''?>">30 days</a>
										</div>
										
										<a style="font-size:0.5em;color:white;border-radius:20px;display:flex;    flex-direction: column;
    justify-content: center;
    height: 38px;
    padding-top: 0;
    padding-bottom: 0;
    align-items: center;
    font-size: 12px;    line-height: 12px;" href="https://www.stelareum.io/en/exchange/wanswap.html" class="btn" target="_blank">ADVANCED STATS<div style="font-size:9px">by Stelareum</div></a>
									
									</div>
								
								</div>
								
								<div style="flex-wrap:wrap;display:flex;align-items:center;justify-content:space-around;font-size:15px;padding-top:15px;padding-bottom:15px;">
								<div style="background:#f4f3ef;border-radius:15px;padding:10px 20px;flex:1;margin:5px;">Highest<br/><b><?php echo round($day_summary['max_price'],4)?>$</b></div>
								<div style="background:#f4f3ef;border-radius:15px;padding:10px 20px;flex:1;margin:5px;">Lowest<br/><b><?php echo round($day_summary['min_price'],4)?>$</b></div>
								<div style="background:#f4f3ef;border-radius:15px;padding:10px 20px;flex:1;margin:5px;">Average<br/><b><?php echo round($day_summary['avg_price'],4)?>$</b> </div>
								<div style="background:#f4f3ef;border-radius:15px;padding:10px 20px;flex:1;margin:5px;">Volume<br/><b><?php echo number_format($day_summary['sum_volume'],2)?>WASP</b></div>
								<div style="background:#f4f3ef;border-radius:15px;padding:10px 20px;flex:1;margin:5px;">M.Cap<br/><b><?php echo number_format($wasp_price*(str_replace(',','',$wasp_supply)),2)?>$</b></div>
								</div>
							</div>
						<canvas id="wasp_stat_chart"></canvas>
						</div>
					</div>
				</div>
			   
			   </div>
			   
			   <div class="pool">
			   <div style="position:absolute;right:0;top:0;padding:5px 20px;font-size:12px;background:#00000015;border-top-right-radius:15px;border-bottom-left-radius:15px;"><i class="fa fa-clock-o"></i> <?php echo $timestamp?></div>
			   <h3 style="text-align:center;position:relative;opacity:0.6"><b>WASP/WAN</b> POOL</h3>
			   <h5 style="margin-top: -30px;margin-bottom:30px;text-align: center;opacity:0.6">Deposited ~<?php echo $pool_size?> USD</h5>
			   <div class="row">
			   
                <div class="col-md-6">
                    <div class="card card-stats" style="background:rgba(255,255,255,0.7)">
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
                    <div class="card card-stats" style="background:rgba(255,255,255,0.7)">
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
			   
			   <div class="pool">
			   <div style="position:absolute;right:0;top:0;padding:5px 20px;font-size:12px;background:#00000015;border-top-right-radius:15px;border-bottom-left-radius:15px;"><i class="fa fa-clock-o"></i> <?php echo $timestamp?></div>
			   <h3 style="text-align:center;position:relative;opacity:0.6">THE <b>HIVE</b> <img src="./assets/img/hive.svg" style="
    width: 25px;
    position: relative;
    top: -10px;
    left: -6px;
"></h3>
			   <h5 style="margin-top: -30px;margin-bottom:30px;text-align: center;opacity:0.6">Deposited ~<?php echo $wasp_hive_size?> USD</h5>
			   <div class="row">
			   
                <div class="col-md-12">
                    <div class="card card-stats" style="background:rgba(255,255,255,0.7)">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-12">
                                    <div class="numbers text-center">
                                        <p class="card-category">WASP AMOUNT</b></p>
                                        <p class="card-title" style="font-size:25px;">
											<?php echo $wasp_hive?> WASP
                                        </p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
						<div class="card-footer text-center"><hr/><div class="stats"><?php echo $wasp_hive_percent?>% of WASP supply</div></div>
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
.pool
{
	padding:30px;border-radius:15px;background:#00000008;margin-top:40px;background-image: url(./assets/img/wasp.svg);
    background-repeat: no-repeat;
    background-position-y: 30%;
    background-size: 450px;
    background-position-x: 50%;box-shadow:0 6px 10px -4px rgba(0, 0, 0, 0.15);
	position:relative;
}
@media only screen and (min-width: 767px) {
	.pool
	{
		background-size: 300px !important;
	}
	
}
#chart-tools .btn-group a:first-child
	{
		border-radius:15px 0 0 15px;
	}
	#chart-tools .btn-group a:last-child
	{
		border-radius:0 15px 15px 0;
	}
@media only screen and (max-width: 767px) {
	
	#chart-tools
	{
		flex-direction:column !important;

	}
}
</style>

<?php $this->load->view('footer',array(
        'js'=>'wasp_js',
		'wasp_stat'=>$chart_data,
))?>


