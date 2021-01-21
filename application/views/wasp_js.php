<!-- Chart JS -->
<script src="./assets/js/plugins/chartjs.min.js"></script>
<?php
$wasp_price = array();
$wasp_wan = array();
$timestamp = array();
$volume = array();
foreach ($wasp_stat as $row) {
    $tmp = strtotime($row['timestamp']);
    $timestamp[] = "'" . date('M j H:i', $tmp) . "'";
    $wasp_price[] = round($row['wasp_price'], 4);
    $wasp_wan[] = round(1/$row['exchange_rate'], 4);
    $volume[] = round($row['volume_changed'], 2);
}


?>

<script>
    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }

    function abbreviate(number, maxPlaces, forcePlaces, forceLetter) {
        number = Number(number);
        forceLetter = forceLetter || false;
        if (forceLetter !== false) {
            return annotate(number, maxPlaces, forcePlaces, forceLetter);
        }
        var abbr;
        if (number >= 1e12) {
            abbr = 'T';
        } else if (number >= 1e9) {
            abbr = 'B';
        } else if (number >= 1e6) {
            abbr = 'M';
        } else if (number >= 1e3) {
            abbr = 'K';
        } else {
            abbr = '';
        }
        return annotate(number, maxPlaces, forcePlaces, abbr);
    }

    function annotate(number, maxPlaces, forcePlaces, abbr) {
        var rounded = 0;
        switch (abbr) {
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
        if (maxPlaces !== false) {
            var test = new RegExp('\\.\\d{' + (maxPlaces + 1) + ',}$');
            if (test.test(('' + rounded))) {
                rounded = rounded.toFixed(maxPlaces);
            }
        }
        if (forcePlaces !== false) {
            rounded = Number(rounded).toFixed(forcePlaces);
        }
        return rounded + abbr;
    }
    var lineChartData = {
        labels: [<?php echo implode(',', $timestamp) ?>],
        datasets: [{
                type: 'line',
                label: 'WASP/USD',
                borderColor: "#ef8157",
                backgroundColor: "#ef8157",
                fill: false,

                pointRadius: 4,
                pointHoverRadius: 6,
                data: [<?php echo implode(',', $wasp_price) ?>],
                yAxisID: 'y-axis-1',
            },

            {
                type: 'line',
                label: 'WASP/WAN',
                borderColor: "#66c4de",
                backgroundColor: "#66c4de",
                fill: false,

                pointRadius: 4,
                pointHoverRadius: 6,
                data: [<?php echo implode(',', $wasp_wan) ?>],
                yAxisID: 'y-axis-3',
            }


            , {
                type: 'bar',
                label: 'Liquidity (WASP)',
                borderColor: "#f4f3ef",
                backgroundColor: "#f4f3ef",
                borderWidth: 2,
                fill: false,
                data: [<?php echo implode(',', $volume) ?>],
                yAxisID: 'y-axis-2'
            }
        ]
    };



    /* Delegation */
    window.onload = function() {
        var ctx = document.getElementById('wasp_stat_chart').getContext('2d');
        window.myLine = new Chart(ctx, {
            type: 'bar',
            data: lineChartData,
            options: {
                tooltips: {
                    mode: 'index',
                    intersect: true,
                    callbacks: {

                        label: function(tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
                            return label + ': ' + tooltipItem.yLabel;
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
                            
                            scaleLabel: {
                                display: true,
                                labelString: 'WASP/USD'
                            },
                           
                          

                        },

                        {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            id: 'y-axis-3',
                            
                            scaleLabel: {
                                display: true,
                                labelString: 'WASP/WAN'
                            },

                            gridLines: {
                                drawOnChartArea: false,
                            },
                            
                        },

                        {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            id: 'y-axis-2',
                            ticks: {
                                callback: function(value, index, values) {
                                    return abbreviate(value, 2, 1) + ' WASP';
                                },
                               
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Liquidity (WASP)'
                            },
                            gridLines: {
                                drawOnChartArea: false,
                            },
                        }
                    ],
                }
            }
        });

    };
</script>