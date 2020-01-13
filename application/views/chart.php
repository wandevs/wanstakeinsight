<?php $this->load->view('header',array('web_title'=>$web_title))?>

<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-stats">
                <div class="card-body ">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="numbers text-center">
                                <p class="card-category">FROM EPOCH</p>
                                <p class="card-title"><?php echo $epoch_start?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="numbers text-center">
                                <p class="card-category">TO EPOCH</p>
                                <p class="card-title"><?php echo $epoch_stop?>
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
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header ">
                    <h5 class="card-title">DELEGATION</h5>
                    <p class="card-category">Chart of Delegated amount and number of Delegators</p>
                </div>
                <div class="card-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="numbers text-center">

                                <canvas id="delegation_chart"></canvas>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header ">
                    <h5 class="card-title">STAKEOUT</h5>
                    <p class="card-category">Chart of Stake out amount and number of Stake out Delegators</p>
                </div>
                <div class="card-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="numbers text-center">

                                <canvas id="stakeout_chart"></canvas>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header ">
                    <h5 class="card-title">REWARDS</h5>
                    <p class="card-category">Chart of Validator and Delegator Rewards</p>
                </div>
                <div class="card-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="numbers text-center">

                                <canvas id="reward_chart"></canvas>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header ">
                    <h5 class="card-title">EST. REWARD PER 1,000 WAN</h5>
                    <p class="card-category">Chart of estimated delegator reward in each epoch</p>
                </div>
                <div class="card-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="numbers text-center">

                                <canvas id="est_reward_chart"></canvas>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>



<?php $this->load->view('footer',array(
        'js'=>'chart_js',
    'delegated_amount'=>$delegated_amount,
    'delegators'=>$delegators,
    'delegated_amount'=>$delegated_amount,
    'pending_stakeout_amount'=>$pending_stakeout_amount,
    'pending_stakeout_delegator'=>$pending_stakeout_delegator,
    'validator_reward' => $validator_reward,
    'delegator_reward' => $delegator_reward,
    'est_reward' => $est_reward,
))?>

