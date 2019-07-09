function barChart(config){
    var ctx = document.getElementById(config.canvasId);

    var myChart = new Chart(ctx, {
        type: config.type,
        data: {
            labels: config.labels,
            datasets: [
            {
                label: 'Biomedische Wetenschappen',
                data: [36, 38, 39, 38, 23],
                backgroundColor: '#921f1f',
            },
            {
                label: 'Toegepaste Wetenschappen',
                data: [6, 7, 7, 2, 5],
                backgroundColor: '#e90000',
            },
            {
                label: 'Humane Wetenschappen',
                data: [20, 25, 17, 13, 20],
                backgroundColor: '#ef7d00',
            },
            {
                label: 'Sociale Wetenschappen',
                data: [16, 20, 24, 22, 29],
                backgroundColor: '#dbf100',
            },
            {
                label: 'Exacte Wetenschappen',
                data: [22, 10, 13, 25, 23],
                backgroundColor: '#92e400',
            }
            ]
        },
        options: {
            scales: {
            xAxes: [{ 
                stacked: config.stacked,
                scaleLabel: {
                    display: true,
                    labelString: config.xAxisLabel
                },
                ticks: {
                    callback: function (value) {
                        return value + config.xAxisTickValue;
                    }
                }
            }],
            yAxes: [{ 
                stacked: config.stacked,
                scaleLabel: {
                    display: true,
                    labelString: config.yAxisLabel
                },
                ticks: {
                    callback: function (value) {
                        return value + config.yAxisTickValue;
                    }
                }
            }]
            }   
        }
    });

    //console.log(document.getElementById(config.titleId));
    document.getElementById(config.titleId).textContent = config.titleText;
}