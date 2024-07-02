<?php
    if (isset($pageDatas) === false)
    {
        $pageDatas = [];
    }

?>
<span id="12sd">여긴 메인이야! 메인은 처음이지?</span>
<style>
    #chart {
        max-width: 650px;
        margin: 35px auto;
    }

</style>
<script>
    window.Promise ||
    document.write(
        '<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"><\/script>'
    )
    window.Promise ||
    document.write(
        '<script src="https://cdn.jsdelivr.net/npm/eligrey-classlist-js-polyfill@1.2.20171210/classList.min.js"><\/script>'
    )
    window.Promise ||
    document.write(
        '<script src="https://cdn.jsdelivr.net/npm/findindex_polyfill_mdn"><\/script>'
    )
</script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    // Replace Math.random() with a pseudo-random number generator to get reproducible results in e2e tests
    // Based on https://gist.github.com/blixt/f17b47c62508be59987b
    var _seed = 42;
    Math.random = function() {
    _seed = _seed * 16807 % 2147483647;
    return (_seed - 1) / 2147483646;
    };
</script>
<script>
function generateData(count, yrange) {
    var i = 0;
    var series = [];
    while (i < count) {
        var x = 'w' + (i + 1).toString();
        var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;

        series.push({
        x: x,
        y: y
        });
        i++;
    }
    return series;
}
</script>

<div id="chart"></div>
<script>

var options = {
    series: [{
    data: [400, 430, 448, 470, 540, 580, 690, 1100, 1200, 1380]
}],
    chart: {
    type: 'bar',
    height: 380
},
plotOptions: {
    bar: {
    barHeight: '100%',
    distributed: true,
    horizontal: true,
    dataLabels: {
        position: 'bottom'
    },
    }
},
colors: ['#33b2df', '#546E7A', '#d4526e', '#13d8aa', '#A5978B', '#2b908f', '#f9a3a4', '#90ee7e',
    '#f48024', '#69d2e7'
],
dataLabels: {
    enabled: true,
    textAnchor: 'start',
    style: {
    colors: ['#fff']
    },
    formatter: function (val, opt) {
    return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val
    },
    offsetX: 0,
    dropShadow: {
    enabled: true
    }
},
stroke: {
    width: 1,
    colors: ['#fff']
},
xaxis: {
    categories: ['South Korea', 'Canada', 'United Kingdom', 'Netherlands', 'Italy', 'France', 'Japan',
    'United States', 'China', 'India'
    ],
},
yaxis: {
    labels: {
    show: false
    }
},
title: {
    text: 'Custom DataLabels',
    align: 'center',
    floating: true
},
subtitle: {
    text: 'Category Names as DataLabels inside bars',
    align: 'center',
},
tooltip: {
    theme: 'dark',
    x: {
    show: false
    },
    y: {
    title: {
        formatter: function () {
        return ''
        }
    }
    }
}
};

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();

</script>


<script>
    $(document).on('click', "#12sd", function(){
        box_alert('저장이 완료되었습니다.', 'e');

    });
</script>