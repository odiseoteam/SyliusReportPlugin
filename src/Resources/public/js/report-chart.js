function generateLineChart(labels, values, ctx) {
    data = {
        labels : labels,
        datasets : [
            {
                fillColor : "rgba(26,187,156,0.5)",
                strokeColor : "rgba(55,163,126,1)",
                pointColor : "rgba(55,163,126,1)",
                pointStrokeColor : "#fff",
                pointHighlightFill : "#fff",
                pointHighlightStroke : "rgba(55,163,126,1)",
                data : values
            }
        ]
    }
    window.myLine = new Chart(ctx).Line(data, {
        responsive: true,
        maintainAspectRatio: false
    });
}
function generateBarChart(labels, values, ctx) {
    data = {
        labels : labels,
        datasets : [
            {
                fillColor : "rgba(220,220,220,0.5)",
                strokeColor : "rgba(220,220,220,0.8)",
                highlightFill: "rgba(220,220,220,0.75)",
                highlightStroke: "rgba(220,220,220,1)",
                data : values
            }
        ]
    }
    window.myBar = new Chart(ctx).Bar(data, {
        responsive: true,
        maintainAspectRatio: false
    });
}
function generateRadarChart(labels, values, ctx) {
    data = {
        labels: labels,
        datasets: [
            {
                fillColor: "rgba(26,187,156,0.5)",
                strokeColor: "#37a37e",
                pointColor: "rgba(220,220,220,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: values
            }
        ]
    }
    $("#canvas").width(600).height("auto");
    window.myBar = new Chart(ctx).Radar(data, {
        responsive: true,
        maintainAspectRatio: false
    });
}
function generatePolarChart(labels, values, ctx) {
    window.myBar = new Chart(ctx).PolarArea(generateRadialData(labels, values), {
        responsive: true,
        maintainAspectRatio: false
    });
}
function generatePieChart(labels, values, ctx) {
    window.myBar = new Chart(ctx).Pie(generateRadialData(labels, values), {
        responsive: true,
        maintainAspectRatio: false
    });
}
function generateDoughnutChart(labels, values, ctx) {
    window.myBar = new Chart(ctx).Doughnut(generateRadialData(labels, values), {
        responsive: true,
        maintainAspectRatio: false
    });
}
function generateRadialData(labels, values) {
    var colors = ["#1abb9c", "#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360", "#bdbdbd", "#AA66CC", "33B5E5"];
    var highlights = ["#37a37e", "#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774", "#acacac", "#9933CC", "0099CC"];
    var data = [];
    $.each(values, function(i){
        var fragment = {
            value: parseInt(this),
            color: colors[i%9],
            highlight: highlights[i%9],
            label: labels[i]
        };
        data.push(fragment);
    });
    return data;
}

function initChart(self){
    var chartType = self.attr("data-type");
    var reportCode = self.attr("id");
    var labels = self.attr("data-labels").split(";");
    var values = self.attr("data-values").split(";");

    var ctx = document.getElementById(reportCode).getContext("2d");
    switch(chartType) {
        case "line": generateLineChart(labels, values, ctx); break;
        case "bar": generateBarChart(labels, values, ctx); break;
        case "radar": generateRadarChart(labels, values, ctx); break;
        case "polar": generatePolarChart(labels, values, ctx); break;
        case "pie": generatePieChart(labels, values, ctx); break;
        case "doughnut": generateDoughnutChart(labels, values, ctx); break;
    }
}

window.addEventListener("load",function(){
    $.each($("canvas"), function(){
        initChart($(this));
    });
});
