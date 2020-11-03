<div
    class="w-full h-full"
    x-data="{ ...lineChart() }"
    x-init="drawChart(@this)"
>
    <div wire:ignore x-ref="container"></div>

    <script>
        function lineChart() {
            return {
                chart: null,

                drawChart(component) {
                    if (this.chart) {
                        this.chart.destroy()
                    }

                    const title = component.get('lineChartModel.title');
                    const animated = component.get('lineChartModel.animated') || false;
                    const dataLabels = component.get('lineChartModel.dataLabels') || {};
                    const data = component.get('lineChartModel.data');
                    const onPointClickEventName = component.get('lineChartModel.onPointClickEventName');

                    const series = [{
                        name: title,
                        data: data.map(item => item.value),
                    }]

                    const categories = data.map(item => item.title)

                    const options = {
                        series: series,

                        chart: {
                            type: 'line',
                            height: '100%',
                            zoom: {
                                enabled: false
                            },
                            toolbar: {
                                show: false
                            },
                            animations: {
                                enabled: animated,
                            },
                            events: {
                                markerClick: function(event, chartContext, { dataPointIndex }) {
                                    if (!onPointClickEventName) {
                                        return
                                    }

                                    const point = data[dataPointIndex]
                                    component.call('onPointClick', point)
                                }
                            }
                        },

                        dataLabels: dataLabels,

                        stroke: component.get('lineChartModel.stroke') || {},

                        title: {
                            text: title,
                            align: 'center'
                        },

                        xaxis: {
                            ...component.get('lineChartModel.xAxis') || {},
                            categories: categories,
                        },

                        yaxis: component.get('lineChartModel.yAxis') || {},

                        annotations: {
                            points: component.get('lineChartModel.markers').map(item => {
                                    return {
                                        x: item.title,
                                        y: item.value,
                                        marker: {
                                            size: 6,
                                            fillColor: '#fff',
                                            strokeColor: item.strokeColor,
                                            radius: 2,
                                        },
                                        label: {
                                            offsetY: 0,
                                            style: {
                                                color: item.textColor,
                                                background: item.textBackgroundColor,
                                            },
                                            text: item.text || '',
                                        }
                                    }
                                }
                            )
                        },
                    };

                    this.chart = new ApexCharts(this.$refs.container, options);
                    this.chart.render();
                }
            }
        }
    </script>
</div>

