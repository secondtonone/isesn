$(function () {
    $('#container').highcharts({
        title: {
            text: 'Квартиры',
            x: -20 //center
        },
        subtitle: {
            text: 'Единая служба недвижимости',
			
            x: -20
        },
        xAxis: {
            categories: ['Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь',
                'Июль', 'Авг', 'Сен', 'Окт', 'Нояб', 'Дек']
        },
        yAxis: {
            title: {
                text: 'Количество'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: 'шт'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'Студия',
            data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
        }, {
            name: 'Дача',
            data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
        }, {
            name: '1-комнатная',
            data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
        }, {
            name: '2-комнатная',
            data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
        }, {
            name: '3-комнатная',
            data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
        }]
    });
	
	$('#container-2').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Объекты проданные в 2014'
        },
        subtitle: {
            text: 'источник ИС ЕСН'
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Объекты (ед.)'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Проданные объекты: <b>{point.y:.1f} единиц</b>'
        },
        series: [{
			color: 'rgba(165,170,217,1)',
            name: 'Объекты',
            data: [
                ['Студия', 23.7],
                ['Дача', 16.1],
                ['1-комнатная', 14.2],
                ['2-комнатная', 14.0],
                ['3-комнатная', 12.5],
                ['4-комнатная', 12.1],
            ],
            dataLabels: {
                enabled: true,
                rotation: -0,
                color: '#FFFFFF',
                align: 'center',
                x: 0,
                y: 30,
                style: {
                    fontSize: '14px',
                    fontFamily: 'Verdana, sans-serif',
                    textShadow: '0 0 3px black'
                }
            }
        }]
    });



});