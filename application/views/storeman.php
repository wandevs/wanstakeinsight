<?php $this->load->view('header',array('web_title'=>$web_title))?>

        <div class="content">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-12">
                                    <div class="numbers text-center">
                                        <p class="card-category">Total Storeman Staked</p>
                                        <p class="card-title">
										<?php echo number_format($total_selfstaked)?> WAN
                                        </p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="card-footer  text-center">
                            <hr>
                            <div class="stats">
                                Storemen staked: <?php echo number_format($total_deposit)?> WAN (<?php echo round($total_deposit*100/$total_selfstaked,1)?>%)
								 ∷ 
								Partners staked: <?php echo number_format($total_partnerDeposit)?> WAN (<?php echo round($total_partnerDeposit*100/$total_selfstaked,1)?>%)
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">

                                <div class="col-12">
                                    <div class="numbers text-center">
                                        <p class="card-category">AVG. Delegated amount</p>
                                        <p class="card-title"><?php echo number_format($avg_delegated)?> WAN
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <hr>
                            <div class="stats">
                                <b><?php echo number_format($delegated_count)?></b> delegators
                            </div>
                        </div>
                    </div>
                </div>
                </div>
				
				<div class="row">

                
                <div class="col-lg-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <div class="numbers text-center">
                                        <p class="card-category">Delegation Capacity (1:5 Ratio)</p>
                                        <p class="card-title"><?php echo number_format($total_selfstaked*5)?> WAN  <i style="cursor:pointer" class="fa fa-question-circle text-danger"  data-toggle="tooltip" data-html="true" title="Equal to Storemen Staked+Partners Staked"></i>


                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <div class="numbers text-center">
                                        <p class="card-category">Delegated</p>

                                        <p class="card-title">
                                            <?php echo number_format($total_delegated,2)?> WAN <i style="cursor:pointer" class="fa fa-question-circle text-danger"  data-toggle="tooltip" data-html="true" title="Self-Stake and Partner-Stake not included"></i>
                                        </p>

                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <?php
                                    $realTotalCapacity = $total_selfstaked*5; // Total Capacity;

                                    $sumDelegatedPercent = $total_delegated*100/$realTotalCapacity;


                                    if ($sumDelegatedPercent < 80) {
                                        $border_color = 'bg-success';
                                    }
                                    if ($sumDelegatedPercent >= 70) {
                                        $border_color = 'bg-warning';
                                    }
                                    if ($sumDelegatedPercent >= 90) {
                                        $border_color = 'bg-danger';
                                    }


                                    ?>

                                    <div class="progress" style="margin-top:10px;height:30px;">
                                        <div class="progress-bar  <?php echo $border_color?>" role="progressbar" style="width: <?php echo ceil($sumDelegatedPercent)?>%;" aria-valuenow="<?php echo ($sumDelegatedPercent)?>" aria-valuemin="0" aria-valuemax="100"><?php echo number_format($sumDelegatedPercent,2)?>%</div>
                                        
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="card-footer text-center">

                        </div>
                    </div>
                </div>
				
				
				
				<div class="col-lg-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="numbers text-center">
                                        <p class="card-category">Remaining Operation time</p>
                                        <p class="card-title" id="demo" style="font-size:35px;height:82px;padding:15px">...</p>
										
										
										
                                    </div>
                                </div>
                                
                                
                                

                            </div>
                        </div>
                        <div class="card-footer text-center">

                        </div>
                    </div>
                </div>
				
            </div>
            <div class="row">
                <div class="col-md-12">

                    <style>
                        .tableFixHead          { overflow-y: auto; height: 100px; }
                        .tableFixHead thead th { position: sticky; top: 0;background:white;box-shadow:0px 5px 5px 0px white;z-index:5}


                        .progress-circle {
                            width: 100px;
                            height: 100px;
                            background: none;
                            position: relative;
                        }

                        .progress-circle::after {
                            content: "";
                            width: 100%;
                            height: 100%;
                            border-radius: 50%;
                            border: 6px solid #eee;
                            position: absolute;
                            top: 0;
                            left: 0;
                        }

                        .progress-circle>span {
                            width: 50%;
                            height: 100%;
                            overflow: hidden;
                            position: absolute;
                            top: 0;
                            z-index: 1;
                        }

                        .progress-circle .progress-left {
                            left: 0;
                        }

                        .progress-circle .progress-bar {
                            width: 100%;
                            height: 100%;
                            background: none;
                            border-width: 6px;
                            border-style: solid;
                            position: absolute;
                            top: 0;
                            z-index:-10;
                        }

                        .progress-circle .progress-left .progress-bar {
                            left: 100%;
                            border-top-right-radius: 80px;
                            border-bottom-right-radius: 80px;
                            border-left: 0;
                            -webkit-transform-origin: center left;
                            transform-origin: center left;
                        }

                        .progress-circle .progress-right {
                            right: 0;
                        }

                        .progress-circle .progress-right .progress-bar {
                            left: -100%;
                            border-top-left-radius: 80px;
                            border-bottom-left-radius: 80px;
                            border-right: 0;
                            -webkit-transform-origin: center right;
                            transform-origin: center right;
                        }

                        .progress-circle .progress-value {
                            position: absolute;
                            top: 0;
                            left: 0;
                            z-index:0;
                        }
                    </style>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">STOREMEN OF GROUP "<?php echo $groupName?>"</h4>
							<p class="card-category">Wanchain <> Ethereum</p>
                            <hr/>
                        </div>

                        <div class="card-body">
                        <table class="bg-white table table-hover table-striped tableFixHead"  style="white-space: nowrap;">
                            <thead class="text-danger">
                            <tr >
								<th class="text-center">
									RANK
								</th>
                                <th colspan="2">
                                    NAME / ADDRESS
                                </th>
								
								<th class="text-center">
                                    Unclaimed Reward
                                </th>
								
								
								<th class="text-center">
                                    Non-Slashed?
                                </th>
								
								<th class="text-center">
                                    STAKED POWER
                                </th>

                                <th class="text-center">
                                    CAPACITY
                                </th>

                                <th>
                                    DELEGATION
                                </th>
                                
                                <th>
                                    SELF-STAKE / PARTNER
                                </th>

                            </tr></thead>
                            <tbody style="overflow-y:scroll;height:100px;">
                            <?php

                            
                            function custom_number_format($n, $precision = 2) {
                                if ($n < 1000) {
                                    // Anything less than thousand
                                    $n_format = number_format($n);
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

                            <?php
							$current_rank = 0;
							$total_power = 0;
							// Move foundation to last //
							if ($storemen[0]['isWhite'] == 1)
							{
								$foundation = $storemen[0];
								unset($storemen[0]);
								array_push($storemen, $foundation); 
							}
							
							// Power //
							foreach($storemen as $storeman)
							{
								$self_power = round(($storeman['deposit']+$storeman['partnerDeposit'])/WAN_DIGIT*1.5);
								$delegate_power = round($storeman['delegateDeposit']/WAN_DIGIT);
								$total_power += $self_power+$delegate_power;
							}
							foreach($storemen as $storeman):
							
                                $current_rank++;
                                ?>
                                <tr>
									<td style="text-align: center;font-size:20px;">
										<b><?php echo $current_rank?></b>
									</td>
                                    <td style="text-align: center">
                                        <?php
                                        
                                        if (isset($storeman['iconData']))
                                        {
                                            echo '<img src="data:image/'.$storeman['iconType'].';base64,'.$storeman['iconData'].'" style="width:60px;height:auto"/><div style="width:60px;"></div>';
                                        }
                                        else
                                        {
											if ($storeman['isWhite']==1)
											{
												echo '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAMAAABHPGVmAAABBVBMVEUAAAATaq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq0Taq3///8QaKxBh73x9vr9/v73+vzl7/YZbq8yfrhOkML6/P1Gi78dcLEWbK4ue7cldrQ3gbrp8fiQudkhc7IqebXa6PLV5fG71OiwzeSgw95/rtP0+Pvg7PXR4u9to8xkncns8/nC2OqGs9Vzp89SksPN4O7I3eyLttddmceqyuJ6q9FWlcW00OWmx+CWvdtooMs7hLvc6fObwN1jfq07AAAAI3RSTlMABuriNA7D+/j02p+WazgnFsruS97RurGkkYeAdWZBPixYWR9Cq/4AAAUjSURBVGje1ZppWxoxEMeX3S03ClgvRK2dDbfIJYe24gHiCWrV7/9R6mrpLGSTCSBP6f+Vz6PyI5PJXImmJk/U3Nva8Ab1gA/AF9CD3o2t72bUo32avpiRUEz3w5j8eiwUMb98CmEnEYuDUPFYYmdGjmc56Q0AoYA3ueyZHrEUNkBJRnjJMyUiFAdlxUPTYJY3dZhI+ubypNsdCcLECkYmcgFz3QdTyLduqu/GtgFTythW3Jlo2A9Tyx+OqjC+rcFMWvtGM3ZXYUat7lKMla8ws76uEAwDPkHGCsWYN2VXwVYMgClYbFfoV6sKjMrtbUWBsirwsegajSidH2UyR+cNGrMWdT3nYRJR3j8uWm8qHl+VSUzY7exv+ylG7bVj/VH6Z42i+Ld5hmkQiEb/xXLo9KRKYAyTi+3rckR+0C1aI8rc3OXlmPXxyB/xyRDw/Ni2OGV/HIAM44uM5cGgjNG8zOECjo4y1lCHdak7B5dHPGtTZqmLa/zY3G2leYtIwp03nR62pIstdfCQtYZq36eAMUih8eTurC85FhISMgr1zuhWoxugOz8LKSEPuRDW6LVwn1/6Vcb9Bt2ZWkpYcMCvjh3f97XGxtZ4iGu8vhC4c/ivaxnuB/xn2mH5/TLjdutHlnRnY+hgSTdE9eTU6UMl5uZ3dzdOdy64UZJ/DrsXOJXvrh3/jsFd9lWsVq8EnLwfx34n4AI5v8k63VYlahaPTlzOTGDnHZIATo0alAaPOXRbafx/P0jph4sq1BrAKSGwFku17gclqF2eodvKMtmv65eng3J+/7GV4v86ZtvLjLtA0la722uyagVUxKpvf/rruG2lXSBx046/4AaxLdWqp8qgJHvR9pFyg4Adi0MCiK3cAVNaSfPMepMAEtK0aEwCye6rQQo5CSQW1Ux93hDd1L775w3x72lbMG8IbGkb84dsaN75Q7xakICQBTcNCWo6AeErl8smmwyiawECwlcumbOLEpsEEtB86hAGqY9cmH04AKYO8WmgDGHN20OuqlOBAA1BS51lLC4jq0Foc/FVA9YWauYiNx4txanzVGBqG0+7sJ360FIjavUajIbo5GFES7mp2L0qM/IwkmGFVeqHo1sxLCuxsaPCChUg80NLoVPZHfBIY9dvVOQBkgj1J2gpPB7ji8t0+4fyUL8nS1qZLNe+uTp0NiNNWsL0i+Iqd2y/UPL0G42pQXIYfLGRRMkLCS1EQEQFMXZ1BCQkLO6Ilh1bBxoSEZap1PABWwcKEjex4OYh9BgF+zGECBqUhCsE4wbIWwe46hZlkAQ2QeMQbJ7KVylgYkRqkC/1WmJIYAfbOQ4yjOWp+3bOpZ1DP7aPaOGpgxDeWraSrhA7K4EdQASNKaaAzmsN7HGbKySJLTYPsT8432s5hxvigUjusmGHTYTwLbYW5iF2KGSNekfoZMw20VDZx8p7TuAhYeHYgz3//AiF+GXxuODYY/Ss2kvjpiz6kniAk88zh9m5EMnKOMBxwlk+zw9w6FEUJg8M9ow9Ow5gm/v23EKooRomD6xQRgYi3UGZGqrR40G+rms5fj7tV4nxID3oRIxzJChwOHLQSY9sIXWf5ashamSrOnwWJdyXfoMcPhNjdGpym35VHqPzFwKkzdqzXAjQVxuYcFu96a426EsaTLj1gtIlzSzXTaB43TTDxdkCXc8ZK//+MvNzrmUX44J59qvyxbn0n+n5wqI9xJjyScliPo6Z+JnPIj9YUn569X88IhM/h9tTfg73GylzZl1PryF1AAAAAElFTkSuQmCC" style="width:60px;height:60px;"/>';
											}
											else{
												
                                            echo '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAMAAAC5zwKfAAAAVFBMVEUAAABxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGY5IJHbAAAAHHRSTlMAoZkIkytZfkOKeCIOBYc8NRKEakodFnFRYY9m2EI04QAAAdlJREFUWMPtl0mWhSAMRQnyRQXs+7//fdakBnqi0jwHNai7gHvSkBjFM3ObD1WppCyrIW9ngWHyms6U+Sdd12i6oh9dkq7VdIca43X2S0/UsYkbRR7yuOqRH52F+woKYZ+x+DiqC/N9KJQ9KOuMwtEhQk0R5GBDON7GWIqj9gnvBkRKusYzMjNx1LcxNrOmzUvi9LEBysIdXpSODNGxxBYrToxMuEW1ePCPkXoS9v6Hy2M0EW/Ghrz88WFJB83BxMpyy0ZnnLikohPVvVDRiSWsdf1DhFV/dDZh+60UT7ipLfJf7wQImbcz7erCUq4FSs0eK8ZKrHcQBd/aCHa4WDcAo2Q+6YBzrCbOlqybFrrCJGdLl1SpvoGumdJ0WY9cdRxXEuN2jpHzRHZpvvzOl1hAg12cnJsCLlnqfFynWwgGEuDG2gFVUCMbZsOy5Sg2vRbydfx+wmg9ByY8JR0qHKD157+Mire/6h/U594uYabkEZUJ2HhG/JPMq8XrxqWU+7LOggFO8zcTOPY4KmrCa7fTETm/vBtof3e94utGQxcwpyPOB8z43ZxX4qx/StgQZ0SEM9gUTs18EpvnxlNCPEQJ70L2I4YyKU9HkD/kZRKvYDatpOq/geH9AN3xFDqa+MLKAAAAAElFTkSuQmCC" style="width:50px;height:50px;"/><div style="width:50px;"></div>';
											}
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php

                                        if (isset($storeman['name']))
                                        {
                                            echo '<b>'.$storeman['name'].'</b><br/>'.$storeman['wkAddr'];
                                        }
                                        else{
                                            echo '<b>'.$storeman['wkAddr'].'</b>';
                                        }

                                        ?>
                                        <a href="https://www.wanscan.org/storemaninfo/<?php echo $storeman['wkAddr']?>?groupid=<?php echo $groupId?>" target="_blank"><i class="fa fa-search"></i></a><br/>
                                        
										<?php
										if ($storeman['isWhite']==1)
										{
											echo '<span style="font-size:13px;font-weight:normal" class="badge badge-info">Foundation</span>';
										}
										
										?>
										
										<?php echo $storeman['quited']==''?'<span class="badge badge-success" style="font-size:13px;font-weight:normal"><i class="fa fa-check"></i> Renew</span>':'<span class="badge badge-danger" style="font-size:13px;font-weight:normal"><i class="fa fa-remove"></i> Quitted</span>'?> 
										
                                    </td>
									
									<td class="text-center">
										<?php
										echo number_format($storeman['incentive']/WAN_DIGIT,2).' WAN';
										
										?>
									</td>
									
									
									<td class="text-center">
										<?php
										if ($storeman['slashedCount']==0)
										{
											echo '<i class="fa fa-check text-success fa-2x"></i>';
										}
										else{
											echo '<i class="fa fa-remove text-danger fa-2x"></i><br/>';
											echo $storeman['slashedCount'].' time'.($storeman['slashedCount']>1?'s':'');
										}
										
										?>
									</td>
									
									<td class="text-center">
										<?php
										$self_power = round(($storeman['deposit']+$storeman['partnerDeposit'])/WAN_DIGIT*1.5);
										$delegate_power = round($storeman['delegateDeposit']/WAN_DIGIT);
										echo '<b>'.number_format($self_power+$delegate_power).'</b><br/>';
										echo number_format(($self_power+$delegate_power)*100/$total_power,2).'%'
										?>
									</td>

                                    <td>
                                        <?php
										
										$staked = ($storeman['deposit']+$storeman['partnerDeposit'])/WAN_DIGIT;
										$capacity = $staked*5;
										//echo $staked;
										$capacityWanRemain = round($capacity-($storeman['delegateDeposit']/WAN_DIGIT));
										$capicityPercent = ceil($storeman['delegateDeposit']/WAN_DIGIT*100/$capacity);
										
										
                                        if ($capacityWanRemain <= 100)
                                        {
                                            $capacityWanRemain = 0;

                                        }
                                        else{
                                            if ($capicityPercent == 100)
                                            $capicityPercent = 99;
                                        }
                                        ?>
                                        <div class="progress-circle m-auto" data-value='<?php echo $capicityPercent?>'>
                                            <?php
                                            if ($capicityPercent < 70)
                                            {
                                                $border_color = 'border-success';
                                            }
                                            if ($capicityPercent >= 70)
                                            {
                                                $border_color = 'border-warning';
                                            }
                                            if ($capicityPercent >= 90)
                                            {
                                                $border_color = 'border-danger';
                                            }

                                            ?>
                                              <span class="progress-left">
                                                  <span class="progress-bar <?php echo $border_color?>"></span>
                                              </span>
                                            <span class="progress-right">
                                             <span class="progress-bar <?php echo $border_color?>"></span>
                                            </span>
                                            <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center text-center">
                                                <div class="h2" style="font-size: 14px;margin-top: 8px;margin-bottom:8px;line-height:18px;">
                                                    <div><b><?php echo $capicityPercent?>%</b></div>

                                                    <?php if ($capacityWanRemain !=0):?>
                                                        <a style="font-size:10px;line-height:0px;"><div>REMAIN<br style="height:0;"/><?php echo custom_number_format($capacityWanRemain,1)?> WAN</a>
                                                    <?php else:?>
                                                        <a style="font-size:10px;line-height:0px;"><div>QUOTA<br style="height:0;"/>REACHED</a>
                                                    <?php endif;?>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    
                                    
                                    <td style="font-size:12px;">
                                        <b>Total:</b>
                                        <?php
                                        echo number_format($storeman['delegateDeposit']/WAN_DIGIT);
                                        ?>
                                        WAN<br/>

                                       
                                        
                                        <b># Delegator: </b>
                                        <?php
                                        echo number_format($storeman['delegatorCount']);
                                        ?>

                                    </td>
                                    
                                    <td style="font-size:12px;">
                                        <b>Self-Stake: </b>
                                        <?php
                                        echo number_format($storeman['deposit']/WAN_DIGIT);
                                        ?>
                                        WAN <br/>
                                        <b>Partner-Stake: </b>
                                        <?php
                                        echo number_format($storeman['partnerDeposit']/WAN_DIGIT);
                                        ?>
                                        WAN <br/>
                                        <b>Total Validator Stake:</b> <?php echo number_format(($storeman['deposit']+$storeman['partnerDeposit'])/WAN_DIGIT)?> WAN<br/>

                                        <b># Partner: </b>
                                        <?php
                                        echo $storeman['partnerCount'];
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


<?php $this->load->view('footer',array('js'=>'storeman_js'))?>


