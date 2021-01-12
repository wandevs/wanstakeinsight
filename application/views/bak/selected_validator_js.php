<!-- Chart JS -->
<script src="./assets/js/plugins/chartjs.min.js"></script>

<script>
    ctx = document.getElementById('selected_validators_chart').getContext("2d");

    myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [1, 2, 3],
            datasets: [{
                label: "Emails",
                pointRadius: 0,
                pointHoverRadius: 0,
                backgroundColor: [
                    '#e3e3e3',
                    '#4acccd',
                    '#fcc468',
                    '#ef8157'
                ],
                borderWidth: 0,
                data: [342, 480, 530, 120]
            }]
        },

        options: {

            legend: {
                display: false
            },

            pieceLabel: {
                render: 'percentage',
                fontColor: ['white'],
                precision: 2
            },

            tooltips: {
                enabled: true
            },

            scales: {
                yAxes: [{

                    ticks: {
                        display: false
                    },
                    gridLines: {
                        drawBorder: false,
                        zeroLineColor: "transparent",
                        color: 'rgba(255,255,255,0.05)'
                    }

                }],

                xAxes: [{
                    barPercentage: 1.6,
                    gridLines: {
                        drawBorder: false,
                        color: 'rgba(255,255,255,0.1)',
                        zeroLineColor: "transparent"
                    },
                    ticks: {
                        display: false,
                    }
                }]
            },
        }
    });
</script>