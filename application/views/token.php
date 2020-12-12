<?php $this->load->view('header',array('web_title'=>$web_title))?>

        <div class="content">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-12">
                                    <div class="numbers text-center">
                                        <p class="card-category">Total Converted Value › <b>Wanchain</b></p>
                                        <p class="card-title">
										<?php echo custom_number_format($wanchain_tvl)?> USD
                                        </p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
						<div class="card-footer text-center"><?php echo $wanchain_asset_count?> Assets</div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-12">
                                    <div class="numbers text-center">
                                        <p class="card-category">Total Converted Value › <b>Ethereum</b></p>
                                        <p class="card-title">
										<?php echo custom_number_format($ethereum_tvl)?> USD
                                        </p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
						<div class="card-footer text-center"><?php echo $ethereum_asset_count?> Assets</div>
                    </div>
                </div>
               </div>
				
				<div class="row">

				
            </div>
			
			
			<?php
			
                            
                            function custom_number_format($n, $precision = 2) {
                                if ($n < 1000) {
                                    // Anything less than thousand
                                    $n_format = number_format($n,4);
                                }
                                else if ($n < 1000000) {
                                    // Anything less than a million
                                    $n_format = number_format($n / 1000, $precision).'K';
                                } else if ($n < 1000000000) {
                                    // Anything less than a billion
                                    $n_format = number_format($n / 1000000, $precision) . 'M';
                                } else {
                                    // At least a billion
                                    $n_format = number_format($n / 1000000000, $precision) . 'B';
                                }

                                return $n_format;
                            }

			?>
			
            <div class="row">
                <div class="col-lg-12">
				 <style>
                        .tableFixHead          { overflow-y: auto; height: 100px; }
                        .tableFixHead thead th { position: sticky; top: 0;background:white;box-shadow:0px 5px 5px 0px white;z-index:5}
						td{
							font-size:16px;
						}
                    </style>
				

                    <div class="card"> <!--- FOR WANCHAIN --->
                        <div class="card-header">
                            <h4 class="card-title">Assets on <b>Wanchain</b></h4>
							<p class="card-category">Token Type: <b>WRC-20</b></p>
                            <hr/>
                        </div>

                        <div class="card-body">
                        <table class="bg-white table table-hover table-striped tableFixHead"  style="white-space: nowrap;">
                            <thead class="text-danger">
                            <tr >
								
                                <th colspan="2">
                                    Name
                                </th>
								
								<th class="text-center">
                                    Converted Amount
                                </th>
								
								<th class="text-center" colspan="3">
                                    Converted Amount Changed
                                </th>
								
								
								
								<th class="text-center">
                                    Price (USD)
                                </th>
								
								<th class="text-center">
                                    Total Converted Value (USD)
                                </th>


                            </tr></thead>
                            <tbody style="overflow-y:scroll;height:100px;">
                            <?php

                           
							
							foreach($wanchain_stats as $stat):

                                ?>
                                <tr>
								
                                    <td style="text-align: center; width:60px;">
                                        <?php

                                            echo '<img src="'.$asset_icons[$stat['asset_name']].'" style="width:60px;height:auto"/><div style="width:60px;"></div>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php  
                                        echo '<b style="font-size:18px;">'.$stat['asset_name'].'</b>';
										echo '<div style="font-size:11px;">@'.$stat['timestamp'].'</div>';
                                        ?>

                                    </td>
									
									<td class="text-center">
										<?php  
                                        echo custom_number_format($stat['asset_amount'],2);
                                        ?>
									</td>
									
									<td class="text-center" style="font-size:14px;">
										<?php  
										if ($stat['asset_amount'] >= $stat['last_24hrs_amount'])
										{
											echo '<b>24 Hrs</b> <div class="text-success">+'.custom_number_format($stat['asset_amount']-$stat['last_24hrs_amount'],2);
											echo ' ('.round((($stat['asset_amount']-$stat['last_24hrs_amount'])/$stat['last_24hrs_amount'])*100,2).'%)</div>';
										}
										else{
											echo '<b>24 Hrs</b> <div class="text-danger">-'.custom_number_format($stat['last_24hrs_amount']-$stat['asset_amount'],2);
											echo ' ('.round((($stat['last_24hrs_amount']-$stat['asset_amount'])/$stat['asset_amount'])*100,2).'%)</div>';
										}
                                        ?>
										</td>
										
										<td class="text-center" style="font-size:14px;">
										<?php  
										if ($stat['asset_amount'] >= $stat['last_7days_amount'])
										{
											echo '<b>7 Days</b> <div class="text-success">+'.custom_number_format($stat['asset_amount']-$stat['last_7days_amount'],2);
											echo ' ('.round((($stat['asset_amount']-$stat['last_7days_amount'])/$stat['last_7days_amount'])*100,2).'%)</div>';
										}
										else{
											echo '<b>7 Days</b> <div class="text-danger">-'.custom_number_format($stat['last_7days_amount']-$stat['asset_amount'],2);
											echo ' ('.round((($stat['last_7days_amount']-$stat['asset_amount'])/$stat['asset_amount'])*100,2).'%)</div>';
										}
                                        ?>
									</td>
										
										<td class="text-center" style="font-size:14px;">
										<?php  
										if ($stat['asset_amount'] >= $stat['last_30days_amount'])
										{
											echo '<b>30 Days</b> <div class="text-success">+'.custom_number_format($stat['asset_amount']-$stat['last_30days_amount'],2);
											echo ' ('.round((($stat['asset_amount']-$stat['last_30days_amount'])/$stat['last_30days_amount'])*100,2).'%)</div>';
										}
										else{
											echo '<b>30 Days</b> <div class="text-danger">-'.custom_number_format($stat['last_30days_amount']-$stat['asset_amount'],2);
											echo ' ('.round((($stat['last_30days_amount']-$stat['asset_amount'])/$stat['asset_amount'])*100,2).'%)</div>';
										}
                                        ?>
									</td>
									
									
									<td class="text-center">
										
										<?php  
                                        echo custom_number_format($stat['asset_price'],2);
                                        ?>
										
									</td>
									
									<td class="text-center">
										<?php  
                                        echo custom_number_format($stat['asset_tvl'],2);
                                        ?>
									</td>

                                    

                                </tr>
                            <?php endforeach;?>
                            </tbody>

                        </table>
                    </div>
                </div>
				


				<div class="card"> <!--- FOR ETHEREUM --->
                        <div class="card-header">
                            <h4 class="card-title">Assets on <b>Ethereum</b></h4>
							<p class="card-category">Token Type: <b>ERC-20</b></p>
                            <hr/>
                        </div>

                        <div class="card-body">
                        <table class="bg-white table table-hover table-striped tableFixHead"  style="white-space: nowrap;">
                            <thead class="text-danger">
                            <tr >
								
                                <th colspan="2">
                                    Name
                                </th>
								
								<th class="text-center">
                                    Converted Amount
                                </th>
								
								<th class="text-center" colspan="3">
                                    Converted Amount Changed
                                </th>
								
								
								<th class="text-center">
                                    Price (USD)
                                </th>
								
								<th class="text-center">
                                    Total Converted Value (USD)
                                </th>


                            </tr></thead>
                            <tbody style="overflow-y:scroll;height:100px;">
                            <?php

                           
							
							foreach($ethereum_stats as $stat):

                                ?>
                                <tr>
								
                                    <td style="text-align: center; width:60px;">
                                        <?php

                                            echo '<img src="'.$asset_icons[$stat['asset_name']].'" style="width:60px;height:auto"/><div style="width:60px;"></div>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php  
                                        echo '<b style="font-size:18px;">'.$stat['asset_name'].'</b>';
										echo '<div style="font-size:11px;">@'.$stat['timestamp'].'</div>';
                                        ?>

                                    </td>
									
									<td class="text-center">
										<?php  
                                        echo custom_number_format($stat['asset_amount'],2);
                                        ?>
									</td>
									
									<td class="text-center" style="font-size:14px;">
										<?php  
										if ($stat['asset_amount'] >= $stat['last_24hrs_amount'])
										{
											echo '<b>24 Hrs</b> <div class="text-success">+'.custom_number_format($stat['asset_amount']-$stat['last_24hrs_amount'],2);
											echo ' ('.round((($stat['asset_amount']-$stat['last_24hrs_amount'])/$stat['last_24hrs_amount'])*100,2).'%)</div>';
										}
										else{
											echo '<b>24 Hrs</b> <div class="text-danger">-'.custom_number_format($stat['last_24hrs_amount']-$stat['asset_amount'],2);
											echo ' ('.round((($stat['last_24hrs_amount']-$stat['asset_amount'])/$stat['asset_amount'])*100,2).'%)</div>';
										}
                                        ?>
										</td>
										
										<td class="text-center" style="font-size:14px;">
										<?php  
										if ($stat['asset_amount'] >= $stat['last_7days_amount'])
										{
											echo '<b>7 Days</b> <div class="text-success">+'.custom_number_format($stat['asset_amount']-$stat['last_7days_amount'],2);
											echo ' ('.round((($stat['asset_amount']-$stat['last_7days_amount'])/$stat['last_7days_amount'])*100,2).'%)</div>';
										}
										else{
											echo '<b>7 Days</b> <div class="text-danger">-'.custom_number_format($stat['last_7days_amount']-$stat['asset_amount'],2);
											echo ' ('.round((($stat['last_7days_amount']-$stat['asset_amount'])/$stat['asset_amount'])*100,2).'%)</div>';
										}
                                        ?>
									</td>
										
										<td class="text-center" style="font-size:14px;">
										<?php  
										if ($stat['asset_amount'] >= $stat['last_30days_amount'])
										{
											echo '<b>30 Days</b> <div class="text-success">+'.custom_number_format($stat['asset_amount']-$stat['last_30days_amount'],2);
											echo ' ('.round((($stat['asset_amount']-$stat['last_30days_amount'])/$stat['last_30days_amount'])*100,2).'%)</div>';
										}
										else{
											echo '<b>30 Days</b> <div class="text-danger">-'.custom_number_format($stat['last_30days_amount']-$stat['asset_amount'],2);
											echo ' ('.round((($stat['last_30days_amount']-$stat['asset_amount'])/$stat['asset_amount'])*100,2).'%)</div>';
										}
                                        ?>
									</td>
									
									
									<td class="text-center">
										
										<?php  
                                        echo custom_number_format($stat['asset_price'],2);
                                        ?>
										
									</td>
									
									<td class="text-center">
										<?php  
                                        echo custom_number_format($stat['asset_tvl'],2);
                                        ?>
									</td>

                                    

                                </tr>
                            <?php endforeach;?>
                            </tbody>

                        </table>
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
</style>

<?php $this->load->view('footer',array('js'=>'storeman_js'))?>


