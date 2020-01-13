<!-- Chart JS -->
<script src="./assets/js/plugins/chartjs.min.js"></script>
<?php
    $epochs = array();
    $delegator = array();
    $amount = array();
    $stakeout = array();
    $stakeout_delegator = array();

    foreach($delegators as $epoch=>$row)
    {
        $epochs[] = "'".$epoch."'";
        $delegator[] = $row;
    }
    foreach($delegated_amount as $row)
    {
        $amount[] = round($row,2);
    }
    foreach($pending_stakeout_amount as $row)
    {
        $stakeout[] = round($row,2);
    }
    foreach($pending_stakeout_delegator as $row)
    {
        $stakeout_delegator[] = $row;
    }

?>

<script>
    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }
    function abbreviate(number, maxPlaces, forcePlaces, forceLetter) {
        number = Number(number);
        forceLetter = forceLetter || false;
        if(forceLetter !== false) {
            return annotate(number, maxPlaces, forcePlaces, forceLetter);
        }
        var abbr;
        if(number >= 1e12) {
            abbr = 'T';
        }
        else if(number >= 1e9) {
            abbr = 'B';
        }
        else if(number >= 1e6) {
            abbr = 'M';
        }
        else if(number >= 1e3) {
            abbr = 'K';
        }
        else {
            abbr = '';
        }
        return annotate(number, maxPlaces, forcePlaces, abbr);
    }
    function annotate(number, maxPlaces, forcePlaces, abbr) {
        var rounded = 0;
        switch(abbr) {
            case 'T':
                rounded = number / 1e12;
                break;
            case 'B':
                rounded = number / 1e9;
                break;
            case 'M':
                rounded = number / 1e6;
                break;
            case 'K':
                rounded = number / 1e3;
                break;
            case '':
                rounded = number;
                break;
        }
        if(maxPlaces !== false) {
            var test = new RegExp('\\.\\d{' + (maxPlaces + 1) + ',}$');
            if(test.test(('' + rounded))) {
                rounded = rounded.toFixed(maxPlaces);
            }
        }
        if(forcePlaces !== false) {
            rounded = Number(rounded).toFixed(forcePlaces);
        }
        return rounded + abbr;
    }


    var lineChartData = {
        labels: [<?php echo implode(',',$epochs)?>],
        datasets: [{
            label: 'Delegator #',
            borderColor: "#fcc468",
            backgroundColor: "#fcc468",
            fill: false,
            borderDash: [2, 2],
            pointRadius: 4,
            pointHoverRadius: 8,
            data: [<?php echo implode(',',$delegator)?>],
            yAxisID: 'y-axis-1',
        }, {
            label: 'Delegated Amount',
            borderColor: "#f17e5d",
            backgroundColor: "#f17e5d",
            pointRadius: 4,
            pointHoverRadius: 8,
            fill: false,
            data: [<?php echo implode(',',$amount)?>],
            yAxisID: 'y-axis-2'
        }]
    };

    var lineChartData2 = {
        labels: [<?php echo implode(',',$epochs)?>],
        datasets: [{
            label: 'Stakeout Delegator #',
            borderColor: "#fcc468",
            backgroundColor: "#fcc468",
            fill: false,
            borderDash: [2, 2],
            pointRadius: 4,
            pointHoverRadius: 8,
            data: [<?php echo implode(',',$stakeout_delegator)?>],
            yAxisID: 'y-axis-3',
        }, {
            label: 'Stakeout Amount',
            borderColor: "#f17e5d",
            backgroundColor: "#f17e5d",
            pointRadius: 4,
            pointHoverRadius: 8,
            fill: false,
            data: [<?php echo implode(',',$stakeout)?>],
            yAxisID: 'y-axis-4'
        }]
    };

    var lineChartData3 = {
        labels: [<?php echo implode(',',$epochs)?>],
        datasets: [{
            label: 'Delegator Reward',
            borderColor: "#fcc468",
            backgroundColor: "#fcc468",
            fill: false,

            pointRadius: 4,
            pointHoverRadius: 8,
            data: [<?php echo implode(',',$delegator_reward)?>],
            yAxisID: 'y-axis-5',
        }, {
            label: 'Validator Reward',
            borderColor: "#f17e5d",
            backgroundColor: "#f17e5d",
            pointRadius: 4,
            pointHoverRadius: 8,
            fill: false,
            data: [<?php echo implode(',',$validator_reward)?>],
            yAxisID: 'y-axis-6'
        }]
    };

    var lineChartData4 = {
        labels: [<?php echo implode(',',$epochs)?>],
        datasets: [{
            label: 'Reward per 1000 WAN',
            borderColor: "#6bd098",
            backgroundColor: "#6bd098",
            pointRadius: 4,
            pointHoverRadius: 8,
            fill: false,
            data: [<?php echo implode(',',$est_reward)?>],
        }]
    };

    /* Delegation */
    window.onload = function() {
        var ctx = document.getElementById('delegation_chart').getContext('2d');
        window.myLine = Chart.Line(ctx, {
            data: lineChartData,
            options: {

                tooltips: {
                    callbacks: {
                        title:function(titleItem, data) {
                            var title = data.labels[titleItem[0].index] || '';

                            return 'EPOCH: '+title;
                        },
                        label: function(tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';

                            if (label) {
                                label += ': ';
                            }
                            label += formatNumber(tooltipItem.yLabel);
                            if (label.search("#") == -1)
                            {
                                return label+' WAN';
                            }
                            return label;
                        }
                    }
                },
                responsive: true,
                scales: {
                    yAxes: [{
                        type: 'linear',
                        display: true,
                        position: 'left',
                        id: 'y-axis-1',
                        ticks: {
                            callback: function(value, index, values) {
                                return formatNumber(value);
                            }
                        }
                    }, {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        id: 'y-axis-2',
                        ticks: {
                            callback: function(value, index, values) {

                                return abbreviate(value,2,1)+' WAN';
                            }
                        },
                        gridLines: {
                            drawOnChartArea: false,

                        },
                    }],
                }
            }
        });

        /*Stake Out*/
        var ctx = document.getElementById('stakeout_chart').getContext('2d');
        window.myLine = Chart.Line(ctx, {
            data: lineChartData2,
            options: {

                tooltips: {
                    callbacks: {
                        title:function(titleItem, data) {
                            var title = data.labels[titleItem[0].index] || '';

                            return 'EPOCH: '+title;
                        },
                        label: function(tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';

                            if (label) {
                                label += ': ';
                            }
                            label += formatNumber(tooltipItem.yLabel);
                            if (label.search("#") == -1)
                            {
                                return label+' WAN';
                            }
                            return label;
                        }
                    }
                },
                responsive: true,
                scales: {
                    yAxes: [{
                        type: 'linear',
                        display: true,
                        position: 'left',
                        id: 'y-axis-3',
                        ticks: {
                            callback: function(value, index, values) {
                                return formatNumber(value);
                            }
                        }
                    }, {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        id: 'y-axis-4',
                        ticks: {
                            callback: function(value, index, values) {
                                return abbreviate(value,2,1)+' WAN';
                            }
                        },
                        gridLines: {
                            drawOnChartArea: false,

                        },
                    }],
                }
            }
        });

        var ctx = document.getElementById('reward_chart').getContext('2d');
        window.myLine = Chart.Line(ctx, {
            data: lineChartData3,
            options: {

                tooltips: {
                    callbacks: {
                        title:function(titleItem, data) {
                            var title = data.labels[titleItem[0].index] || '';

                            return 'EPOCH: '+title;
                        },
                        label: function(tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';

                            if (label) {
                                label += ': ';
                            }
                            label += formatNumber(tooltipItem.yLabel);
                            return label+' WAN';
                        }
                    }
                },
                responsive: true,
                scales: {
                    yAxes: [{
                        type: 'linear',
                        display: true,
                        position: 'left',
                        id: 'y-axis-5',
                        ticks: {
                            callback: function(value, index, values) {
                                return formatNumber(value)+' WAN';
                            }
                        }
                    }, {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        id: 'y-axis-6',
                        ticks: {
                            callback: function(value, index, values) {
                                return formatNumber(value)+' WAN';
                            }
                        },
                        gridLines: {
                            drawOnChartArea: false,

                        },
                    }],
                }
            }
        });

        var ctx = document.getElementById('est_reward_chart').getContext('2d');
        window.myLine = Chart.Line(ctx, {
            data: lineChartData4,
            options: {

                tooltips: {
                    callbacks: {
                        title:function(titleItem, data) {
                            var title = data.labels[titleItem[0].index] || '';

                            return 'EPOCH: '+title;
                        },
                        label: function(tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';

                            if (label) {
                                label += ': ';
                            }
                            label += (tooltipItem.yLabel);
                            return label+' WAN';
                        }
                    }
                },
                responsive: true,
                scales: {
                    yAxes: [{
                        type: 'linear',
                        display: true,
                        position: 'right',
                        ticks: {
                            callback: function(value, index, values) {
                                return Math.round(value * 100) / 100+' WAN';
                            }
                        }
                    }],
                }

            }
        });
    };

</script>