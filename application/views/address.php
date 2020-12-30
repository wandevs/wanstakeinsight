<?php $this->load->view('header',array('web_title'=>$web_title))?>

        <div class="content">
           
            <div class="row">
                <div class="col-lg-12">
					<style>
                        .tableFixHead          { overflow-y: auto; height: 100px; }
                        .tableFixHead thead th { position: sticky; top: 0;background:white;box-shadow:0px 5px 5px 0px white;z-index:5}
						td{
							font-size:16px;
						}
                    </style>
				

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">TOP 50 Addresses</b></h4>
							<p class="card-category"></b></p>
                            <hr/>
                        </div>

                        <div class="card-body">
                        <table class="bg-white table table-hover table-striped tableFixHead"  style="white-space: nowrap;">
                            <thead class="text-danger">
                            <tr >
								
                                <th class="text-center">
                                    #
                                </th>
								
								<th>
                                    Address
                                </th>
								
								<th class="text-center">
                                    #Txs
                                </th>
								
													
								<th class="text-center">
                                    WAN Amount
                                </th>
								
								<th class="text-center">
                                    %of Supply (210m)
                                </th>


                            </tr></thead>
                            <tbody style="overflow-y:scroll;height:100px;">
                            <?php

                           
							$rank = 0;
							function change_address($address , $alias)
							{
								$address = strtolower($address);
								
								if (isset($alias[$address]))
								{
									return $alias[$address];
								}
								else
								{
									return $address;
								}
							}
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
							foreach($lists as $address):
								$rank++;
                                ?>
                                <tr>
								
                                    <td style="text-align: center; width:60px;">
                                        <?php
											echo $rank;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
											echo '<a class="text-danger" target="_blank" href="https://www.wanscan.org/address/'.$address['addr'].'">'.change_address($address['addr'], $alias).'</a>';
                                        ?>
                                    </td>
									
									<td class="text-center">
										<?php
											echo number_format($address['txCnt']);
                                        ?>
									</td>
									
									<td class="text-center">
										<?php
											echo custom_number_format($address['balance'],2);
                                        ?>
									</td>
										
										<td class="text-center">
										<?php
											echo number_format($address['balance']*100/210000000,2).'%';
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
<style>
.main-panel>.content
{
	min-height:auto;
}
</style>

<?php $this->load->view('footer',array('js'=>'storeman_js'))?>


