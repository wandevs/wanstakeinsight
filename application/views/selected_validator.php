<?php $this->load->view('header',array('web_title'=>$web_title))?>

<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-stats">
                <div class="card-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="numbers text-center">
                                <p class="card-category">CURRENT EPOCH</p>
                                <p class="card-title" style="font-size:50px;"><?php echo $current_epoch?>


                            </div>
                        </div>



                    </div>
                </div>
                <div class="card-footer text-center">

                </div>
            </div>

            <div class="card card-stats">
                <div class="card-body ">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="numbers text-center">
                                <p class="card-category">Non-Foundation Validator</p>

                                <p class="card-title">
                                    <?php echo (count($selected_validators)-1)?>
                                </p>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="numbers text-center">
                                <p class="card-category">Foundation Validator</p>

                                <p class="card-title">
                                    <?php echo $selected_validators['foundation']['FOUNDATION_COUNT']?>
                                </p>

                            </div>
                        </div>


                    </div>
                </div>
                <div class="card-footer text-center">

                </div>
            </div>

            <div class="card card-stats">
                <div class="card-body ">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="numbers text-center">
                                <p class="card-category">Selected EL</p>

                                <p class="card-title">
                                    <?php echo array_sum($EL_list)?>
                                </p>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="numbers text-center">
                                <p class="card-category">Selected RNP</p>

                                <p class="card-title">
                                    <?php echo array_sum($RNP_list)?>
                                </p>

                            </div>
                        </div>


                    </div>
                </div>
                <div class="card-footer text-center">
                    <hr>
                    <div class="stats">
                        * EL = Epoch Leader | RNP = Random Number Proposer
                    </div>
                </div>
            </div>
        </div>

       
    </div>

    <div class="row">
        <div class="col-md-12">

            <style>
                .tableFixHead          { overflow-y: auto; height: 100px; }
                .tableFixHead thead th { position: sticky; top: 0;background:white;box-shadow:0px 5px 5px 0px white;z-index:5}

            </style>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">SELECTED VALIDATORS</h4>
                    <p class="card-category">Total <b><?php echo (count($selected_validators)-1)?></b> Validators</p>
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
                            #EL
                        </th>
                        <th class="text-center">
                            #RNP
                        </th>

                        <th class="text-center">
                            SELECTED AS EL/RNP PERCENTAGE
                        </th>

                        <th class="text-center">
                            #SL (EL in Epoch <?php echo $current_epoch-1?>)
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

                    ?>

                    <?php
                    $sum_el = 0;
                    $sum_rnp = 0;
                    $sum_sl = 0;
                    $sum_sl_foundation = 0;
                    $numItems = count($selected_validators); // Check last //
                    $i = 0;
                    foreach($selected_validators as $address=>$validator):?>

                    <?php if(++$i === $numItems): // Last Item Loop for SL /?>

                        <?php foreach($SL_list as $SL_address => $SL_validator):
                                if (!$SL_address) continue;
                                if (in_array($SL_address,$foundation_list))
                                {
                                    $sum_sl_foundation += $SL_list[$SL_address];
                                    continue;
                                }
                                ?>
                                <tr>
                                    <td style="text-align: center;width:80px;">
                                        <?php
                                        $info = get_validator_info($SL_address,$validator_info_list);
                                        if (isset($info['logo']))
                                        {
                                            echo '<img src="'.$info['logo'].'" style="width:60px;height:auto;"/><div style="width:60px;"></div>';
                                        }
                                        else{

                                            echo '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAMAAAC5zwKfAAAAVFBMVEUAAABxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGY5IJHbAAAAHHRSTlMAoZkIkytZfkOKeCIOBYc8NRKEakodFnFRYY9m2EI04QAAAdlJREFUWMPtl0mWhSAMRQnyRQXs+7//fdakBnqi0jwHNai7gHvSkBjFM3ObD1WppCyrIW9ngWHyms6U+Sdd12i6oh9dkq7VdIca43X2S0/UsYkbRR7yuOqRH52F+woKYZ+x+DiqC/N9KJQ9KOuMwtEhQk0R5GBDON7GWIqj9gnvBkRKusYzMjNx1LcxNrOmzUvi9LEBysIdXpSODNGxxBYrToxMuEW1ePCPkXoS9v6Hy2M0EW/Ghrz88WFJB83BxMpyy0ZnnLikohPVvVDRiSWsdf1DhFV/dDZh+60UT7ipLfJf7wQImbcz7erCUq4FSs0eK8ZKrHcQBd/aCHa4WDcAo2Q+6YBzrCbOlqybFrrCJGdLl1SpvoGumdJ0WY9cdRxXEuN2jpHzRHZpvvzOl1hAg12cnJsCLlnqfFynWwgGEuDG2gFVUCMbZsOy5Sg2vRbydfx+wmg9ByY8JR0qHKD157+Mire/6h/U594uYabkEZUJ2HhG/JPMq8XrxqWU+7LOggFO8zcTOPY4KmrCa7fTETm/vBtof3e94utGQxcwpyPOB8z43ZxX4qx/StgQZ0SEM9gUTs18EpvnxlNCPEQJ70L2I4YyKU9HkD/kZRKvYDatpOq/geH9AN3xFDqa+MLKAAAAAElFTkSuQmCC" style="width:50px;height:50px;"/><div style="width:50px;"></div>';

                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php

                                        if (isset($info['name']))
                                        {
                                            echo '<b>'.$info['name'].'</b><br/>'.$SL_address;
                                        }
                                        else{
                                            if ($SL_address=='foundation')
                                            {
                                                echo '<b>WANCHAIN FOUNDATION\'S NODE</b><br/>Total '.$validator['FOUNDATION_COUNT'].' Validators<br/>';
                                            }
                                            else{
                                                echo '<b>'.$SL_address.'</b>';
                                            }

                                        }

                                        ?>
                                        <?php if ($SL_address!='foundation'):?>
                                            <a href="https://www.wanscan.org/vld/<?php echo $SL_address?>" target="_blank"><i class="fa fa-search"></i></a><br/>
                                        <?php endif;?>
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
                                    </td>

                                    <td class="text-center " style="font-size:2em;">-</td>
                                    <td class="text-center " style="font-size:2em;">-</td>
                                    <td class="text-center " style="font-size:2em;">
                                        -
                                    </td>
                                    <td class="text-center" style="font-size:2em;">
                                        <?php

                                        if (isset($SL_list[$SL_address]))
                                        {
                                            echo $SL_list[$SL_address];
                                            $sum_sl += $SL_list[$SL_address];
                                        }
                                        else{
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                </tr>
                        <?php endforeach?>
                    <?php endif;?>

					<?php
					if ($address=='foundation')
					{
						continue;
					}
					?>
                        <tr>
                            <td style="text-align: center;width:80px;">
                                <?php
                                $info = get_validator_info($address,$validator_info_list);
                                if (isset($info['logo']))
                                {
                                    echo '<img src="'.$info['logo'].'" style="width:60px;height:auto;"/><div style="width:60px;"></div>';
                                }
                                else{
                                    if ($address=='foundation')
                                    {
                                        echo '<img src="data:image/png;base64,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" style="width:50px;height:50px;"/><div style="width:50px;"></div>';

                                    }
                                    else
                                    {
                                        echo '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAMAAAC5zwKfAAAAVFBMVEUAAABxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGZxbGY5IJHbAAAAHHRSTlMAoZkIkytZfkOKeCIOBYc8NRKEakodFnFRYY9m2EI04QAAAdlJREFUWMPtl0mWhSAMRQnyRQXs+7//fdakBnqi0jwHNai7gHvSkBjFM3ObD1WppCyrIW9ngWHyms6U+Sdd12i6oh9dkq7VdIca43X2S0/UsYkbRR7yuOqRH52F+woKYZ+x+DiqC/N9KJQ9KOuMwtEhQk0R5GBDON7GWIqj9gnvBkRKusYzMjNx1LcxNrOmzUvi9LEBysIdXpSODNGxxBYrToxMuEW1ePCPkXoS9v6Hy2M0EW/Ghrz88WFJB83BxMpyy0ZnnLikohPVvVDRiSWsdf1DhFV/dDZh+60UT7ipLfJf7wQImbcz7erCUq4FSs0eK8ZKrHcQBd/aCHa4WDcAo2Q+6YBzrCbOlqybFrrCJGdLl1SpvoGumdJ0WY9cdRxXEuN2jpHzRHZpvvzOl1hAg12cnJsCLlnqfFynWwgGEuDG2gFVUCMbZsOy5Sg2vRbydfx+wmg9ByY8JR0qHKD157+Mire/6h/U594uYabkEZUJ2HhG/JPMq8XrxqWU+7LOggFO8zcTOPY4KmrCa7fTETm/vBtof3e94utGQxcwpyPOB8z43ZxX4qx/StgQZ0SEM9gUTs18EpvnxlNCPEQJ70L2I4YyKU9HkD/kZRKvYDatpOq/geH9AN3xFDqa+MLKAAAAAElFTkSuQmCC" style="width:50px;height:50px;"/><div style="width:50px;"></div>';
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <?php

                                if (isset($info['name']))
                                {
                                    echo '<b>'.$info['name'].'</b><br/>'.$address;
                                }
                                else{
                                    if ($address=='foundation')
                                    {
                                        echo '<b>WANCHAIN FOUNDATION\'S NODE</b><br/>Total '.$validator['FOUNDATION_COUNT'].' Validators<br/>';
                                    }
                                    else{
                                        echo '<b>'.$address.'</b>';
                                    }

                                }

                                ?>
                                <?php if ($address!='foundation'):?>
                                    <a href="https://www.wanscan.org/vld/<?php echo $address?>" target="_blank"><i class="fa fa-search"></i></a><br/>
                                <?php endif;?>
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
                            </td>

                            <td class="text-center " style="font-size:2em;"><?php echo $validator['EL'];$sum_el+=$validator['EL']?></td>
                            <td class="text-center " style="font-size:2em;"><?php echo $validator['RNP'];$sum_rnp+=$validator['RNP']?></td>
                            <td class="text-center " style="font-size:2em;">
                                <?php echo round(($validator['EL']+$validator['RNP'])*100/$total_selected,1)?>%
                            </td>
                            <td class="text-center" style="font-size:2em;">
                                <?php

                                if (isset($SL_list[$address]))
                                {
                                    echo $SL_list[$address];
                                    $sum_sl += $SL_list[$address];
                                    unset($SL_list[$address]);
                                }
                                else{
                                    if ($address!='foundation')
                                    {
                                        echo '-';
                                    }
                                    else{
                                        echo $sum_sl_foundation;
                                    }

                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                    <tfoot>
                        <tr class="table-dark">
                            <td class="text-center" style="font-size:2em;" colspan="2">Total</td>
                            <td class="text-center "  style="font-size:2em;">
                                <?php echo $sum_el?>
                            </td>
                            <td class="text-center "  style="font-size:2em;">
                                <?php echo $sum_rnp?>
                            </td>
                            <td></td>
                            <td class="text-center"  style="font-size:2em;"><?php echo $sum_sl+$sum_sl_foundation?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>


<?php $this->load->view('footer')?>
