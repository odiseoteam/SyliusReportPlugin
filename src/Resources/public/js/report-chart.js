var reportChart = null;

function formatMoney(number, decimals, decPoint, thousandsSep, currencySymbol)
{
// *     example: formatMoney(1234.56, 2, ',', ' ');
// *     return: '1 234,56'
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep,
        dec = (typeof decPoint === 'undefined') ? '.' : decPoint,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return currencySymbol + s.join(dec);
}

function generateChart(ctx, type, labels, values, options)
{
    var datasetOptions = $.extend({
        data: values
    }, options.dataset);

    var chartOptions = {
        type: type,
        data: {
            labels: labels,
            datasets: [datasetOptions]
        },
        options: {
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            tooltips: {
                callbacks: {
                    label: function (tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || (chart.labels[tooltipItem.index] || '');
                        var datasetValue = chart.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];

                        if (options.isMoneyValue !== undefined && options.isMoneyValue === true) {
                            return datasetLabel + ': ' + formatMoney(datasetValue, 2, '.', ',', '$');
                        }

                        return datasetLabel + datasetValue;
                    }
                }
            }
        }
    };

    if (type !== 'pie' && type !== 'radar' && type !== 'polarArea' && type !== 'doughnut') {
        chartOptions.options.scales = {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    userCallback: function (value, index, values) {
                        if (options.isMoneyValue !== undefined && options.isMoneyValue === true) {
                            return formatMoney(value, 2, '.', ',', '$');
                        }

                        return value;
                    }
                }
            }]
        };
    }

    reportChart = new Chart(ctx, chartOptions);
}

function generateLineChart(ctx, labels, values, options)
{
    options = $.extend(options, {
        dataset: {
            backgroundColor: 'rgba(60,122,167,0.6)',
            borderColor: 'rgba(44,79,110,0.95)',
            borderWidth: 1
        }
    });

    generateChart(ctx, 'line', labels, values, options);
}

function generateBarChart(ctx, labels, values, options)
{
    options = $.extend(options, {
        dataset: {
            backgroundColor: 'rgba(60,122,167,0.6)',
            borderColor: 'rgba(44,79,110,0.95)',
            borderWidth: 1
        }
    });

    generateChart(ctx, 'bar', labels, values, options);
}

function generateRadarChart(ctx, labels, values, options)
{
    options = $.extend(options, {
        dataset: {
            backgroundColor: 'rgba(60,122,167,0.6)',
            borderColor: 'rgba(44,79,110,0.95)',
            borderWidth: 1
        }
    });

    generateChart(ctx, 'radar', labels, values, options);
}

function generatePolarChart(ctx, labels, values, options)
{
    options = $.extend(options, {
        dataset: {
            backgroundColor: 'rgba(60,122,167,0.6)',
            borderColor: 'rgba(44,79,110,0.95)',
            borderWidth: 1
        }
    });

    generateChart(ctx, 'polarArea', labels, values, options);
}

function generatePieChart(ctx, labels, values, options)
{
    options = $.extend(options, {
        dataset: generateRadialData(values)
    });

    generateChart(ctx, 'pie', labels, values, options);
}

function generateDoughnutChart(ctx, labels, values, options)
{
    options = $.extend(options, {
        dataset: generateRadialData(values)
    });

    generateChart(ctx, 'doughnut', labels, values, options);
}

function generateRadialData(values)
{
    var backgroundColors = ["#1abb9c", "#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360", "#bdbdbd", "#AA66CC", "#33B5E5"];
    var borderColors = ["#37a37e", "#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774", "#acacac", "#9933CC", "#0099CC"];
    var datasetConfiguration = {
        backgroundColor: [],
        borderColor: [],
        borderWidth: 1
    };

    $.each(values, function(i) {
        datasetConfiguration.backgroundColor.push(backgroundColors[i%9]);
        datasetConfiguration.borderColor.push(borderColors[i%9]);
    });

    return datasetConfiguration;
}

function initChart($reportCanvas)
{
    var reportCode = $reportCanvas.attr("id");
    var chartType = $reportCanvas.data("type");
    var labels = $reportCanvas.data("labels").toString().split(";");
    var values = $reportCanvas.data("values").toString().split(";");
    var options = $.extend($reportCanvas.data('options'), {
        dataset: {}
    });

    var ctx = document.getElementById(reportCode).getContext("2d");

    switch(chartType) {
        case "line": generateLineChart(ctx, labels, values, options); break;
        case "bar": generateBarChart(ctx, labels, values, options); break;
        case "radar": generateRadarChart(ctx, labels, values, options); break;
        case "polar": generatePolarChart(ctx, labels, values, options); break;
        case "pie": generatePieChart(ctx, labels, values, options); break;
        case "doughnut": generateDoughnutChart(ctx, labels, values, options); break;
    }
}

window.addEventListener("load",function()
{
    $.each($("canvas"), function() {
        initChart($(this));
    });
});
