<?php $this->load->view('header',array('web_title'=>$web_title))?>

        <div class="content">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-6">
                                    <div class="numbers text-center">
                                        <p class="card-category">AVG. MAX FEE</p>
                                        <p class="card-title"><?php echo round(array_sum(array_filter($max_fee_rate_list))/count($max_fee_rate_list)/100,2)?>%
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="numbers text-center">
                                        <p class="card-category">AVG. FEE</p>
                                        <p class="card-title"><?php echo round(array_sum(array_filter($fee_rate_list))/count($fee_rate_list)/100,2)?>%
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer  text-center">
                            <hr>
                            <div class="stats">
                                Lowest fee is <b><?php echo round(min($fee_rate_list)/100,2)?>%</b> ∷ Highest fee is <b><?php echo round(max($fee_rate_list)/100,2)?>%</b>
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
                                        <p class="card-category">AVG. Delegate amount</p>
                                        <p class="card-title"><?php echo number_format(round(array_sum(array_filter($delegate_amount_list))/count($delegate_amount_list),2))?> WAN
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <hr>
                            <div class="stats">
                                <b><?php echo number_format(count($delegate_amount_list))?></b> delegators ∷ Highest delegate is <b><?php echo number_format(max($delegate_amount_list))?></b> WAN
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-6">
                                    <div class="numbers text-center">
                                        <p class="card-category" style="font-size:12px;">AVG. VALIDATOR STAKE</p>
                                        <p class="card-title">
                                            <?php echo number_format(array_sum(array_filter($delegate_validator_amount_list))/count($delegate_validator_amount_list))?> WAN
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="numbers text-center">
                                        <p class="card-category" style="font-size:12px;">AVG. VALIDATOR STAKE (ND)</p>
                                        <p class="card-title"><?php echo number_format(array_sum(array_filter($non_delegate_validator_amount_list))/count($non_delegate_validator_amount_list))?> WAN
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <hr>
                            <div class="stats">
                                <b><?php echo number_format(count($delegate_validator_amount_list))?></b> Delegating Validators ∷ <b><?php echo number_format(count($non_delegate_validator_amount_list))?></b> Non-Delegate Validators
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
                                        <p class="card-category">All Pending Stake Out</p>

                                        </p class="card-title"><?php echo number_format(array_sum($pending_stake_out_list))?> WAN (<?php echo $pending_percent = round((array_sum($pending_stake_out_list)*100)/array_sum($delegate_amount_list),2)?>% <i style="cursor:pointer" class="fa fa-question-circle text-danger"  data-toggle="tooltip" data-html="true" title="Percentage from Delegated WAN not Total Capacity"></i>)</p>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <hr>
                            <div class="stats">
                                From <b><?php echo number_format(count($pending_stake_out_list))?></b> Delegator
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $totalCapacity = 0;
                //$totalCapacitywithND = 0;
                foreach($validator_list as $validator):
                    $total_delegate =  0;
                    $selfStake= 0;
                    $partnerStake= 0;
                    if (isset($validator['delegatorAmount']))
                    {
                        $total_delegate = (array_sum($validator['delegatorAmount']));
                    }
                    if (isset($validator['selfStake']))
                    {
                        $selfStake= ($validator['selfStake']);
                    }

                    if (isset($validator['partnerAmount'])) {
                        $partnerStake= (array_sum($validator['partnerAmount']));
                    }
                    $totalSelfStake = $selfStake+$partnerStake;
                    $totalCapacity += $totalSelfStake;
                    //$totalCapacitywithND += $totalSelfStake;
                endforeach;



                ?>

                <div class="col-lg-12">
                    <div class="card card-stats">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <div class="numbers text-center">
                                        <p class="card-category">Delegation Capacity</p>
                                        <p class="card-title"><?php echo custom_number_format($totalCapacity*10,2)?> WAN  <i style="cursor:pointer" class="fa fa-question-circle text-danger"  data-toggle="tooltip" data-html="true" title="10 times of (Self_Stake+Partner_Stake)"></i>


                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <div class="numbers text-center">
                                        <p class="card-category">Delegated</p>

                                        <p class="card-title">
                                            <?php echo custom_number_format(array_sum($delegate_amount_list),2)?> WAN <i style="cursor:pointer" class="fa fa-question-circle text-danger"  data-toggle="tooltip" data-html="true" title="Self-Stake and Partner-Stake not included"></i>
                                        </p>

                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <div class="numbers text-center">
                                        <p class="card-category">POS LIVE SINCE</p>

                                        <p class="card-title">
                                            <?php echo $current_epoch_id-18143?> EPOCH (DAYS)
                                        </p>

                                    </div>
                                </div>
                                <div class="col-12">
                                    <?php
                                    $realTotalCapacity = $totalCapacity*10; // Total Capacity;

                                    // Percentage of Deleated - stakeout //
                                    $percentageOfDelegatedwoPending = round((array_sum($delegate_amount_list)-array_sum($pending_stake_out_list))*100/$realTotalCapacity,2);

                                    // Percentage of Stake-outt //
                                    $percentageOfStakeOut = round(array_sum($pending_stake_out_list)*100/$realTotalCapacity,2);

                                    $sumDelegatedPercent = $percentageOfDelegatedwoPending+$percentageOfStakeOut;


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
                                        <div class="progress-bar  <?php echo $border_color?>" role="progressbar" style="width: <?php echo ceil($percentageOfDelegatedwoPending)?>%;" aria-valuenow="<?php echo ($percentageOfDelegatedwoPending)?>" aria-valuemin="0" aria-valuemax="100"><?php echo ($percentageOfDelegatedwoPending)?>%</div>
                                        <div class="progress-bar <?php echo $border_color?> progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo ceil($percentageOfStakeOut)?>%;" aria-valuenow="<?php echo $percentageOfStakeOut?>" aria-valuemin="0" aria-valuemax="100"><?php echo $percentageOfStakeOut?>%</div>
                                    </div>

                                    <div style="margin-top:10px;">
                                     <div style="border-radius:3px;width:20px;height:20px;display:inline-block;vertical-align: middle" class="progress-bar  <?php echo $border_color?>"></div> <a style="padding-top:12px;">Delegated without Pending Stakeout</a>
                                    </div>
                                    <div style="margin-top:10px;">
                                        <div style="border-radius:3px;width:20px;height:20px;display:inline-block;vertical-align: middle" class="progress-bar progress-bar-striped progress-bar-animated <?php echo $border_color?>"></div> <a style="padding-top:12px;">Pending Stakeout</a>
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
                            <h4 class="card-title">DELEGATING VALIDATORS</h4>
                            <p class="card-category">Total <b><?php echo count($validator_list)?></b> Validators</p>
                            <hr/>
                        </div>

                        <div class="card-body">
                        <table class="bg-white table table-hover table-striped tableFixHead"  style="white-space: nowrap;">
                            <thead class="text-danger">
                            <tr >

                                <th colspan="2">
                                    NAME / ADDRESS
                                </th>


                                <th class="text-center">
                                    CAPACITY
                                </th>
                                <th>
                                    FEE
                                </th>
                                <th>
                                    LOCK PERIOD
                                </th>
                                <th>
                                    STAKE / POWER WEIGHT
                                </th>
                                <th>
                                    DELEGATION
                                </th>
                                <th>
                                    PENDING STAKE OUT
                                </th>
                                <th>
                                    SELF-STAKE / PARTNER
                                </th>

                            </tr></thead>
                            <tbody style="overflow-y:scroll;height:100px;">
                            <?php

                            function get_validator_info($address,$validator_info_list)
                            {

                                if (isset($validator_info_list[$address]))
                                {
                                    return $validator_info_list[$address];
                                }
                                return null;
                            }
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

                            <?php foreach($validator_list as $address=>$validator):
                                $total_delegate =  0;
                                $selfStake= 0;
                                $partnerStake= 0;
                                if (isset($validator['delegatorAmount']))
                                {
                                    $total_delegate = (array_sum($validator['delegatorAmount']));
                                }

                                if (isset($validator['selfStake']))
                                {
                                    $selfStake= ($validator['selfStake']);
                                }

                                if (isset($validator['partnerAmount'])) {
                                    $partnerStake= (array_sum($validator['partnerAmount']));
                                }
                                $totalSelfStake = $selfStake+$partnerStake;

                                $capicityPercent = ceil($total_delegate*100/(($totalSelfStake)*10));
                                $capacityWanRemain = $totalSelfStake*10-$total_delegate;

                                $total_delegate = number_format($total_delegate);
                                $selfStake= number_format($selfStake);
                                $partnerStake = number_format($partnerStake);
                                ?>
                                <tr>
                                    <td style="text-align: center">
                                        <?php
                                        $info = get_validator_info($validator['address'],$validator_info_list);
                                        if (isset($info['logo']))
                                        {
                                            echo '<img src="'.$info['logo'].'" style="width:60px;height:auto"/><div style="width:60px;"></div>';
                                        }
                                        else
                                        {
                                            echo '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAMAAAC5zwKfAAAAVFBMVEUAAABxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGY5IJHbAAAAHHRSTlMAoZkIkytZfkOKeCIOBYc8NRKEakodFnFRYY9m2EI04QAAAdlJREFUWMPtl0mWhSAMRQnyRQXs+7//fdakBnqi0jwHNai7gHvSkBjFM3ObD1WppCyrIW9ngWHyms6U+Sdd12i6oh9dkq7VdIca43X2S0/UsYkbRR7yuOqRH52F+woKYZ+x+DiqC/N9KJQ9KOuMwtEhQk0R5GBDON7GWIqj9gnvBkRKusYzMjNx1LcxNrOmzUvi9LEBysIdXpSODNGxxBYrToxMuEW1ePCPkXoS9v6Hy2M0EW/Ghrz88WFJB83BxMpyy0ZnnLikohPVvVDRiSWsdf1DhFV/dDZh+60UT7ipLfJf7wQImbcz7erCUq4FSs0eK8ZKrHcQBd/aCHa4WDcAo2Q+6YBzrCbOlqybFrrCJGdLl1SpvoGumdJ0WY9cdRxXEuN2jpHzRHZpvvzOl1hAg12cnJsCLlnqfFynWwgGEuDG2gFVUCMbZsOy5Sg2vRbydfx+wmg9ByY8JR0qHKD157+Mire/6h/U594uYabkEZUJ2HhG/JPMq8XrxqWU+7LOggFO8zcTOPY4KmrCa7fTETm/vBtof3e94utGQxcwpyPOB8z43ZxX4qx/StgQZ0SEM9gUTs18EpvnxlNCPEQJ70L2I4YyKU9HkD/kZRKvYDatpOq/geH9AN3xFDqa+MLKAAAAAElFTkSuQmCC" style="width:50px;height:50px;"/><div style="width:50px;"></div>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php

                                        if (isset($info['name']))
                                        {
                                            echo '<b>'.$info['name'].'</b><br/>'.$validator['address'];
                                        }
                                        else{
                                            echo '<b>'.$validator['address'].'</b>';
                                        }

                                        ?>
                                        <a href="https://www.wanscan.org/vld/<?php echo $validator['address']?>" target="_blank"><i class="fa fa-search"></i></a><br/>
                                        <?php

                                        if (isset($info['language']))
                                            echo '<i class="fa fa-comments"></i> '.$info['language'].'<br/>';


                                        if (isset($info['website']))
                                            echo '<a href="'.$info['website'].'" target="_blank" rel="nofollow">'.$info['website'].' <i class="fa fa-external-link"></i></a><br/>';

                                        if (isset($info['telegram']))
                                        {
                                            $tmp = explode(',',$info['telegram']);
                                            foreach($tmp as $tg)
                                            echo '<a href="https://t.me/'.$tg.'" target="_blank" rel="nofollow"><i class="fa fa-telegram"></i> '.$tg.'</a> ';
                                            echo '<br/>';
                                        }
                                        if (isset($info['twitter']))
                                        echo '<a href="https://www.twitter.com/'.$info['twitter'].'" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i> '.$info['twitter'].'</a><br/>';




                                        ?>


                                        <?php
                                        //echo '<b># Server: </b>'.(isset($info['node'])?$info['node']:'N/A').' <i style="cursor:pointer" class="fa fa-question-circle text-danger"  data-toggle="tooltip-table" data-html="true" title="Data was added manually.  If you notice any errors, contact me via telegram <a href=\'https://t.me/cryptofennec\' target=\'_blank\'>@cryptofennec</a>"></i><br/>';
                                        ?>

                                    </td>

                                    <td>
                                        <?php
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
                                    <td>
                                        <b>Current: </b>
                                        <?php
                                        echo round($validator['feeRate']/100,2).'%';
                                        ?>
                                        <br/>
                                        <b>Max: </b>
                                        <?php
                                        echo round($validator['maxFeeRate']/100,2).'%';
                                        ?>

                                    </td>
                                    <td>
                                        <?php
                                        $lockperiodPecent = 100-ceil(100*($current_epoch_id-$validator['stakingEpoch'])/$validator['lockEpochs']);
                                        if ($lockperiodPecent > 100)
                                        {
                                            $lockperiodPecent =100;
                                        }
                                        ?>
                                        <div class="progress-circle m-auto" data-value='<?php echo $lockperiodPecent?>'>
                                            <?php

                                            $border_color = 'border-info';

                                            if ($lockperiodPecent <= 40)
                                            {
                                                $border_color = 'border-warning';
                                            }
                                            if ($lockperiodPecent <= 20)
                                            {
                                                $border_color = 'border-danger';
                                            }
                                            $lockperiodLeft = ($validator['lockEpochs']-($current_epoch_id-$validator['stakingEpoch']));
                                            if ($lockperiodLeft > $validator['lockEpochs'])
                                            {
                                                $lockperiodLeft = $validator['lockEpochs'];
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
                                                    <div><b><?php echo $validator['lockEpochs']?> days</b></div>

                                                    <a style="font-size:10px;line-height:0px;"><div><?php echo $lockperiodLeft?> days left</a>
                                                    <a style="font-size:13px;line-height:0px;"><br/><?php echo $validator['nextLockEpochs']>0?'<span class="badge badge-success" style="font-weight:normal">Renew</span>':'<span class="badge badge-danger" style="font-weight:normal">Not Renew</span>'?></a>

                                                </div>
                                            </div>
                                        </div>


                                        <?php
                                        /*
                                        echo 'Percent:'.ceil(100*($current_epoch_id-$validator['stakingEpoch'])/$validator['lockEpochs']).'%';
                                        echo 'LEFT: '.($validator['lockEpochs']-($current_epoch_id-$validator['stakingEpoch'])).'<br/>';
                                        echo '<b>Locked</b> '.$validator['lockEpochs'].' Days<br/>';
                                        echo '<b>Auto Renew?</b> '.$validator['nextLockEpochs'].' Days<br/>';
                                        echo '<b>Locked Since:</b> '.$validator['stakingEpoch'];
                                        */
                                        ?>
                                    </td>
                                    <td>
                                        <?php

                                        $tmp_total_stake = str_replace(',','',$total_delegate)+$totalSelfStake;
                                        //echo number_format($total_staked);
                                       echo '<b>Stake: </b>'.number_format($tmp_total_stake*100/$total_staked,2).'%';

                                       echo '<br/><b>Power: </b>'.number_format($validator['sumVotingPower']*100/$total_voting_power,2).'%';


                                        ?>
                                    </td>
                                    <td style="font-size:12px;">
                                        <b>Total:</b>
                                        <?php
                                        echo $total_delegate;
                                        ?>
                                        WAN<br/>

                                        <b>AVG:</b>
                                        <?php
                                        if (isset($validator['delegatorAmount']))
                                        {
                                            echo number_format(array_sum(array_filter($validator['delegatorAmount']))/count($validator['delegatorAmount']));
                                        }
                                        else{
                                            echo 0;
                                        }
                                        ?>
                                        WAN<br/>

                                        <b>MAX: </b>
                                        <?php
                                        if (isset($validator['delegatorAmount']))
                                        {
                                            echo number_format(max($validator['delegatorAmount']));
                                        }
                                        else{
                                            echo 0;
                                        }
                                        ?>
                                        WAN<br/>
                                        <b># Delegator: </b>
                                        <?php
                                        if (isset($validator['delegatorAmount']))
                                        {
                                            echo number_format(count($validator['delegatorAmount']));
                                        }
                                        else{
                                            echo 0;
                                        }
                                        ?>

                                    </td>
                                    <td>
                                        <?php
                                        if (isset($validator['stake_out']))
                                        {
                                            echo number_format(array_sum($validator['stake_out']));
                                        }
                                        else{
                                            echo 0;
                                        }
                                        ?>
                                        WAN
                                        <?php
                                        if (isset($validator['stake_out'])):

                                            echo '('.ceil(array_sum($validator['stake_out'])*100/str_replace(',','',$total_delegate)).'% ';

                                            ?>
                                            <i style="cursor:pointer" class="fa fa-question-circle text-danger"  data-toggle="tooltip" data-html="true" title="Percentage from Total Delegation"></i>)
                                            <?php
                                        endif;
                                        ?>



                                        <br/>
                                        <b># Delegator: </b>
                                        <?php
                                        if (isset($validator['stake_out']))
                                        {
                                            echo number_format(count($validator['stake_out']));
                                        }
                                        else{
                                            echo 0;
                                        }
                                        ?>
                                    </td>
                                    <td style="font-size:12px;">
                                        <b>Self-Stake: </b>
                                        <?php
                                        echo $selfStake;
                                        ?>
                                        WAN <br/>
                                        <b>Partner-Stake: </b>
                                        <?php
                                        echo $partnerStake;
                                        ?>
                                        WAN <br/>
                                        <b>Total Validator Stake:</b> <?php echo number_format($totalSelfStake)?> WAN<br/>

                                        <b># Partner: </b>
                                        <?php
                                        if (isset($validator['partnerAmount']))
                                        {
                                            echo number_format(count($validator['partnerAmount']));
                                        }
                                        else{
                                            echo 0;
                                        }
                                        ?>
                                    </td>

                                </tr>
                            <?php endforeach;?>
                            </tbody>

                        </table>
                    </div>
                </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">NON-DELEGATION VALIDATORS</h4>
                            <p class="card-category">Total <b><?php echo count($non_delegate_validator_list)?></b> Validators</p>
                            <hr/>
                        </div>

                        <div class="card-body">
                            <table class="bg-white table table-hover table-striped tableFixHead"  style="white-space: nowrap;">
                                <thead class="text-danger">
                                <tr >

                                    <th colspan="2">
                                        NAME / ADDRESS
                                    </th>

                                    <th>
                                        LOCKED PERIOD
                                    </th>

                                    <th>
                                        STAKE / POWER WEIGHT
                                    </th>


                                    <th>
                                        SELF-STAKE / PARTNER
                                    </th>

                                </tr></thead>
                                <tbody style="overflow-y:scroll;height:100px;">


                                <?php foreach($non_delegate_validator_list as $address=>$validator):
                                    $total_delegate =  0;
                                    $selfStake= 0;
                                    $partnerStake= 0;
                                    if (isset($validator['delegatorAmount']))
                                    {
                                        $total_delegate = (array_sum($validator['delegatorAmount']));
                                    }

                                    if (isset($validator['selfStake']))
                                    {
                                        $selfStake= ($validator['selfStake']);
                                    }

                                    if (isset($validator['partnerAmount'])) {
                                        $partnerStake= (array_sum($validator['partnerAmount']));
                                    }
                                    $totalSelfStake = $selfStake+$partnerStake;

                                    $capicityPercent = ceil($total_delegate*100/(($totalSelfStake)*10));
                                    $capacityWanRemain = $totalSelfStake*10-$total_delegate;

                                    $total_delegate = number_format($total_delegate);
                                    $selfStake= number_format($selfStake);
                                    $partnerStake = number_format($partnerStake);
                                    ?>
                                    <tr>
                                        <td style="text-align: center">
                                            <?php
                                            $info = get_validator_info($validator['address'],$validator_info_list);
                                            if (isset($info['logo']))
                                            {
                                                echo '<img src="'.$info['logo'].'" style="width:60px;height:auto"/><div style="width:60px;"></div>';
                                            }
                                            else
                                            {
                                                echo '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAMAAAC5zwKfAAAAVFBMVEUAAABxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGY5IJHbAAAAHHRSTlMAoZkIkytZfkOKeCIOBYc8NRKEakodFnFRYY9m2EI04QAAAdlJREFUWMPtl0mWhSAMRQnyRQXs+7//fdakBnqi0jwHNai7gHvSkBjFM3ObD1WppCyrIW9ngWHyms6U+Sdd12i6oh9dkq7VdIca43X2S0/UsYkbRR7yuOqRH52F+woKYZ+x+DiqC/N9KJQ9KOuMwtEhQk0R5GBDON7GWIqj9gnvBkRKusYzMjNx1LcxNrOmzUvi9LEBysIdXpSODNGxxBYrToxMuEW1ePCPkXoS9v6Hy2M0EW/Ghrz88WFJB83BxMpyy0ZnnLikohPVvVDRiSWsdf1DhFV/dDZh+60UT7ipLfJf7wQImbcz7erCUq4FSs0eK8ZKrHcQBd/aCHa4WDcAo2Q+6YBzrCbOlqybFrrCJGdLl1SpvoGumdJ0WY9cdRxXEuN2jpHzRHZpvvzOl1hAg12cnJsCLlnqfFynWwgGEuDG2gFVUCMbZsOy5Sg2vRbydfx+wmg9ByY8JR0qHKD157+Mire/6h/U594uYabkEZUJ2HhG/JPMq8XrxqWU+7LOggFO8zcTOPY4KmrCa7fTETm/vBtof3e94utGQxcwpyPOB8z43ZxX4qx/StgQZ0SEM9gUTs18EpvnxlNCPEQJ70L2I4YyKU9HkD/kZRKvYDatpOq/geH9AN3xFDqa+MLKAAAAAElFTkSuQmCC" style="width:50px;height:50px;"/><div style="width:50px;"></div>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php

                                            if (isset($info['name']))
                                            {
                                                echo '<b>'.$info['name'].'</b><br/>'.$validator['address'];
                                            }
                                            else{
                                                echo '<b>'.$validator['address'].'</b>';
                                            }

                                            ?>
                                            <a href="https://www.wanscan.org/vld/<?php echo $validator['address']?>" target="_blank"><i class="fa fa-search"></i></a><br/>
                                            <?php

                                            if (isset($info['language']))
                                                echo '<i class="fa fa-comments"></i> '.$info['language'].'<br/>';


                                            if (isset($info['website']))
                                                echo '<a href="'.$info['website'].'" target="_blank" rel="nofollow">'.$info['website'].' <i class="fa fa-external-link"></i></a><br/>';

                                            if (isset($info['telegram']))
                                            {
                                                $tmp = explode(',',$info['telegram']);
                                                foreach($tmp as $tg)
                                                    echo '<a href="https://t.me/'.$tg.'" target="_blank" rel="nofollow"><i class="fa fa-telegram"></i> '.$tg.'</a> ';
                                                echo '<br/>';
                                            }
                                            if (isset($info['twitter']))
                                                echo '<a href="https://www.twitter.com/'.$info['twitter'].'" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i> '.$info['twitter'].'</a><br/>';




                                            ?>


                                            <?php
                                            //echo '<b># Server: </b>'.(isset($info['node'])?$info['node']:'N/A').' <i style="cursor:pointer" class="fa fa-question-circle text-danger"  data-toggle="tooltip-table" data-html="true" title="Data was added manually.  If you notice any errors, contact me via telegram <a href=\'https://t.me/cryptofennec\' target=\'_blank\'>@cryptofennec</a>"></i><br/>';
                                            ?>

                                        </td>




                                        <td  style="width:100px">
                                            <?php
                                            $lockperiodPecent = 100-ceil(100*($current_epoch_id-$validator['stakingEpoch'])/$validator['lockEpochs']);
                                            if ($lockperiodPecent > 100)
                                            {
                                                $lockperiodPecent =100;
                                            }
                                            ?>
                                            <div class="progress-circle m-auto" data-value='<?php echo $lockperiodPecent?>'>
                                                <?php

                                                $border_color = 'border-info';

                                                if ($lockperiodPecent <= 40)
                                                {
                                                    $border_color = 'border-warning';
                                                }
                                                if ($lockperiodPecent <= 20)
                                                {
                                                    $border_color = 'border-danger';
                                                }
                                                $lockperiodLeft = ($validator['lockEpochs']-($current_epoch_id-$validator['stakingEpoch']));
                                                if ($lockperiodLeft > $validator['lockEpochs'])
                                                {
                                                    $lockperiodLeft = $validator['lockEpochs'];
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
                                                        <div><b><?php echo $validator['lockEpochs']?> days</b></div>

                                                        <a style="font-size:10px;line-height:0px;"><div><?php echo $lockperiodLeft?> days left</a>
                                                        <a style="font-size:13px;line-height:0px;"><br/><?php echo $validator['nextLockEpochs']>0?'<span class="badge badge-success" style="font-weight:normal">Renew</span>':'<span class="badge badge-danger" style="font-weight:normal">Not Renew</span>'?></a>

                                                    </div>
                                                </div>
                                            </div>


                                            <?php
                                            /*
                                            echo 'Percent:'.ceil(100*($current_epoch_id-$validator['stakingEpoch'])/$validator['lockEpochs']).'%';
                                            echo 'LEFT: '.($validator['lockEpochs']-($current_epoch_id-$validator['stakingEpoch'])).'<br/>';
                                            echo '<b>Locked</b> '.$validator['lockEpochs'].' Days<br/>';
                                            echo '<b>Auto Renew?</b> '.$validator['nextLockEpochs'].' Days<br/>';
                                            echo '<b>Locked Since:</b> '.$validator['stakingEpoch'];
                                            */
                                            ?>
                                        </td>
                                        <td>
                                            <?php

                                            $tmp_total_stake = str_replace(',','',$total_delegate)+$totalSelfStake;
                                            //echo number_format($total_staked);
                                            echo '<b>Stake: </b>'.number_format($tmp_total_stake*100/$total_staked,2).'%';

                                            echo '<br/><b>Power: </b>'.number_format($validator['sumVotingPower']*100/$total_voting_power,2).'%';


                                            ?>
                                        </td>


                                        <td style="font-size:12px;">
                                            <b>Self-Stake: </b>
                                            <?php
                                            echo $selfStake;
                                            ?>
                                            WAN <br/>
                                            <b>Partner-Stake: </b>
                                            <?php
                                            echo $partnerStake;
                                            ?>
                                            WAN <br/>
                                            <b>Total Validator Stake:</b> <?php echo number_format($totalSelfStake)?> WAN<br/>

                                            <b># Partner: </b>
                                            <?php
                                            if (isset($validator['partnerAmount']))
                                            {
                                                echo number_format(count($validator['partnerAmount']));
                                            }
                                            else{
                                                echo 0;
                                            }
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


<?php $this->load->view('footer',array('js'=>'welcome_message_js'))?>
