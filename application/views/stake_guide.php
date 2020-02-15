<?php $this->load->view('header',array('web_title'=>$web_title))?>
<style>
    .tableFixHead          { overflow-y: auto; height: 100px; }
    .tableFixHead thead th { position: sticky; top: 0;background:white;box-shadow:0px 5px 5px 0px white;z-index:5}
</style>
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Enter amount of WAN</h4>
                        </div>
                        <div class="card-body">
                            <form method="get">
                                <div class="form-group">
                                    <input name="amount" style="padding:10px;font-size:18px;text-align:center" class="form-control" type="text" placeholder="Min is 100 WAN" value="<?php echo $this->input->get('amount')?$this->input->get('amount'):'100'?>">
                                </div>
                                <button type="submit" class="btn btn-primary btn-block btn-lg">Calculate</button>
                            </form>
                            * This is just a prediction tool calculated from previous 30 Epochs data (<?php echo $epoch_start.' - '.$epoch_stop?>)
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($this->input->get('amount')):?>

            <!-- Daily Reward -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Strategy #1</h4>
                            <p class="card-category">I need a reward everyday</p>
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
                                            SELECTED (IN 30 EPOCHS)
                                        </th>
                                        <th>
                                            ESTIMATED REWARD (30 EPOCH)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody style="overflow-y:scroll;height:100px;">
                                <?php
                                $stategy3_tmp = array();
								// Sort by epoch //
								uasort($stategy1, function($a, $b) {
									return $b['epoch'] - $a['epoch'];
								});
                                foreach($stategy1 as $address=>$row):


                                    if ($row['epoch'] < 25)
                                        {
                                            if ($row['epoch'] < 20)
                                            {
                                                continue;
                                            }
                                            $stategy3_tmp[$address] = $row;
                                            continue;
                                        }
                                    $stategy3_tmp[$address] = $row;

                                    ?>
                                    <tr>

                                        <td style="text-align: center;width:80px">

                                            <?php
                                            $info = get_validator_info($address,$validator_info_list);
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
                                                echo '<b>'.$info['name'].'</b><br/>'.$address;
                                            }
                                            else{
                                                echo '<b>'.$address.'</b>';
                                            }

                                            ?>

                                            <a href="https://www.wanscan.org/vld/<?php echo $address?>" target="_blank"><i class="fa fa-search"></i></a><br/>

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
                                        <td style="text-align:center">
                                            <div style="font-size:30px;font-weight:bold;">
                                            <?php echo $row['epoch']?>
                                            </div>
                                            <?php echo $row['el']?> EL | <?php echo $row['rnp'] ?> RNP
                                        </td>
                                        <td style="text-align:left;font-size:18px;">
                                            ~<?php echo number_format($row['est_reward']/1000000*$invest,3)?> WAN
                                        </td>

                                    </tr>

                                <?php endforeach?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Performance -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Strategy #2</h4>
                            <p class="card-category">I need the highest reward amount (Top 10)</p>
                        </div>

                        <div class="card-body">
                            <table class="bg-white table table-hover table-striped tableFixHead"  style="white-space: nowrap;">
                                <thead class="text-danger">
                                <tr >

                                    <th colspan="2">
                                        NAME / ADDRESS
                                    </th>


                                    <th class="text-center">
                                        SELECTED (IN 30 EPOCHS)
                                    </th>
                                    <th>
                                        ESTIMATED REWARD (30 EPOCH)
                                    </th>
                                </tr>
                                </thead>
                                <tbody style="overflow-y:scroll;height:100px;">
                                <?php
                                $s2_count = 0;
                                foreach($stategy2 as $address=>$row):
                                    $s2_count++;
                                    if ($s2_count > 10) break;
                                    ?>
                                    <tr>

                                        <td style="text-align: center;width:80px">

                                            <?php
                                            $info = get_validator_info($address,$validator_info_list);
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
                                                echo '<b>'.$info['name'].'</b><br/>'.$address;
                                            }
                                            else{
                                                echo '<b>'.$address.'</b>';
                                            }

                                            ?>

                                            <a href="https://www.wanscan.org/vld/<?php echo $address?>" target="_blank"><i class="fa fa-search"></i></a><br/>

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
                                        <td style="text-align:center">
                                            <div style="font-size:30px;font-weight:bold;">
                                                <?php echo $row['epoch']?>
                                            </div>
                                            <?php echo $row['el']?> EL | <?php echo $row['rnp'] ?> RNP
                                        </td>
                                        <td style="text-align:left;font-size:18px;">
                                            ~<?php echo number_format($row['est_reward']/1000000*$invest,3)?> WAN
                                        </td>

                                    </tr>

                                <?php endforeach?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


                <!-- High reward + Frequency -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Strategy #3</h4>
                                <p class="card-category">I need to Balance High Reward + Often daily reward</p>
                            </div>

                            <div class="card-body">
                                <table class="bg-white table table-hover table-striped tableFixHead"  style="white-space: nowrap;">
                                    <thead class="text-danger">
                                    <tr >

                                        <th colspan="2">
                                            NAME / ADDRESS
                                        </th>


                                        <th class="text-center">
                                            SELECTED (IN 30 EPOCHS)
                                        </th>
                                        <th>
                                            ESTIMATED REWARD (30 EPOCH)
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody style="overflow-y:scroll;height:100px;">
                                    <?php
                                    $s2_count = 0;
                                    foreach($stategy2 as $address=>$row):
                                        $s2_count++;
                                        if ($s2_count > 10) break;

                                        if (!isset($stategy3_tmp[$address]))
                                        {
                                            continue;
                                        }

                                        ?>
                                        <tr>

                                            <td style="text-align: center;width:80px">

                                                <?php
                                                $info = get_validator_info($address,$validator_info_list);
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
                                                    echo '<b>'.$info['name'].'</b><br/>'.$address;
                                                }
                                                else{
                                                    echo '<b>'.$address.'</b>';
                                                }

                                                ?>

                                                <a href="https://www.wanscan.org/vld/<?php echo $address?>" target="_blank"><i class="fa fa-search"></i></a><br/>

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
                                            <td style="text-align:center">
                                                <div style="font-size:30px;font-weight:bold;">
                                                    <?php echo $row['epoch']?>
                                                </div>
                                                <?php echo $row['el']?> EL | <?php echo $row['rnp'] ?> RNP
                                            </td>
                                            <td style="text-align:left;font-size:18px;">
                                                ~<?php echo number_format($row['est_reward']/1000000*$invest,3)?> WAN
                                            </td>

                                        </tr>

                                    <?php endforeach?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endif;?>

        </div>


<?php $this->load->view('footer')?>
