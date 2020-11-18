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
                                <b><?php echo number_format($delegated_count)?></b> delegators ∷ Highest delegate is <b><?php echo number_format($highest_delegated)?></b> WAN
                            </div>
                        </div>
                    </div>
                </div>
                

                
                <div class="col-lg-12">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <div class="numbers text-center">
                                        <p class="card-category">Delegation Capacity</p>
                                        <p class="card-title"><?php echo number_format($total_selfstaked)?> WAN  <i style="cursor:pointer" class="fa fa-question-circle text-danger"  data-toggle="tooltip" data-html="true" title="Equal to Storemen Staked+Partners Staked"></i>


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
                                    $realTotalCapacity = $total_selfstaked; // Total Capacity;

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
                                    Non-Slashed?
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
                                            echo '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAMAAAC5zwKfAAAAVFBMVEUAAABxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGY5IJHbAAAAHHRSTlMAoZkIkytZfkOKeCIOBYc8NRKEakodFnFRYY9m2EI04QAAAdlJREFUWMPtl0mWhSAMRQnyRQXs+7//fdakBnqi0jwHNai7gHvSkBjFM3ObD1WppCyrIW9ngWHyms6U+Sdd12i6oh9dkq7VdIca43X2S0/UsYkbRR7yuOqRH52F+woKYZ+x+DiqC/N9KJQ9KOuMwtEhQk0R5GBDON7GWIqj9gnvBkRKusYzMjNx1LcxNrOmzUvi9LEBysIdXpSODNGxxBYrToxMuEW1ePCPkXoS9v6Hy2M0EW/Ghrz88WFJB83BxMpyy0ZnnLikohPVvVDRiSWsdf1DhFV/dDZh+60UT7ipLfJf7wQImbcz7erCUq4FSs0eK8ZKrHcQBd/aCHa4WDcAo2Q+6YBzrCbOlqybFrrCJGdLl1SpvoGumdJ0WY9cdRxXEuN2jpHzRHZpvvzOl1hAg12cnJsCLlnqfFynWwgGEuDG2gFVUCMbZsOy5Sg2vRbydfx+wmg9ByY8JR0qHKD157+Mire/6h/U594uYabkEZUJ2HhG/JPMq8XrxqWU+7LOggFO8zcTOPY4KmrCa7fTETm/vBtof3e94utGQxcwpyPOB8z43ZxX4qx/StgQZ0SEM9gUTs18EpvnxlNCPEQJ70L2I4YyKU9HkD/kZRKvYDatpOq/geH9AN3xFDqa+MLKAAAAAElFTkSuQmCC" style="width:50px;height:50px;"/><div style="width:50px;"></div>';
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
											echo '<span style="font-size:13px;" class="badge badge-info">Foundation</span>';
										}
										
										?>
										
										<?php echo $storeman['quited']==''?'<span class="badge badge-success" style="font-size:13px">Renew</span>':'<span class="badge badge-danger" style="font-size:13px">Quitted</span>'?> 
										
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

                                    <td>
                                        <?php
										
										$staked = ($storeman['deposit']+$storeman['partnerDeposit'])/WAN_DIGIT;
										$capacity = $staked;
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


<?php $this->load->view('footer',array('js'=>'welcome_message_js'))?>
