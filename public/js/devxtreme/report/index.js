var datas;
var pivotGrid;
var pivotGridPieChart;

// console.log(datas)
// console.log('date', new Date('2013-01-06'))

$(() => {
    pivotGridChart = $('#pivotgrid-chart').dxChart({
        dataSource: [],
        commonSeriesSettings: {
            argumentField: 'time',
            type: "stackedBar",
            label: {
                visible: true,
                format: {
                    type: "decimal"
                },
                connector: {
                    visible: true
                }
            }
        },
        series: [{
                valueField: 'total_need',
                name: 'Nhu cầu',
                stack: "apples"
            },
            {
                valueField: 'total_more',
                name: 'Phát sinh',
                stack: "apples"
            },
            // {
            //     valueField: 'total_sub',
            //     name: 'Giảm trừ',
            //     stack: "bananas"
            // },
            // {
            //     valueField: 'total_total',
            //     name: 'Tổng nhu cầu'
            // },
            {
                valueField: "recruited_total",
                name: "Đã tuyển",
            }
        ],
        legend: {
            verticalAlignment: 'bottom',
            horizontalAlignment: 'center',
            itemTextPosition: 'top',
        },
        label: {
            visible: true,
            position: 'outside',

            customizeText() {
                return `${this.valueText}`;
            },
        },
        valueAxis: {
            title: {
                text: 'Ứng viên',
            },
            position: 'left',
        },
        title: 'Biểu đồ kế hoạch tuyển dụng',
        export: {
            enabled: true,
        },
        // onPointCustomize: function (pointInfo) {
        //     if (pointInfo.seriesName === "total_total") { // Tên của series cuối cùng trong stack
        //         pointInfo.label.text = (pointInfo.value + pointInfo.stackValue).toString();
        //     }
        // },
        tooltip: {
            enabled: true,
            customizeTooltip: function (arg) {
                return {
                    text: arg.percentText + " - " + arg.valueText
                };
            }
        }
    }).dxChart('instance');

    pivotGrid = $('#pivotgrid').dxPivotGrid({
        allowSortingBySummary: true,
        allowFiltering: true,
        showBorders: true,
        showColumnGrandTotals: true,
        showRowGrandTotals: true,
        showRowTotals: false,
        showColumnTotals: false,
        height: 700,
        texts: {
            grandTotal: "Tổng"
        },
        export: {
            enabled: true
        },
        showTotalsPrior: {
            rows: true,
            columns: true
        },
        scrolling: {
            mode: 'virtual',
        },
        // fieldPanel: {
        //     showColumnFields: true,
        //     showDataFields: true,
        //     showFilterFields: true,
        //     showRowFields: true,
        //     allowFieldDragging: true,
        //     visible: true,
        // },
        dataSource: {
            fields: [{
                    caption: 'Vị trí',
                    dataField: 'position.name',
                    width: 150,
                    area: 'row',
                    sortBySummaryField: 'position_id',
                },
                {
                    dataField: 'time',
                    dataType: 'date',
                    area: 'column',
                    groupInterval: 'year',
                    headerFilter: {
                        allowSearch: true
                    }
                },
                {
                    dataField: 'time',
                    dataType: 'date',
                    area: 'column',
                    groupInterval: 'quarter',
                },
                {
                    dataField: 'time',
                    dataType: 'date',
                    area: 'column',
                    groupInterval: 'month',
                    headerFilter: {
                        allowSearch: true
                    }
                },
                {
                    caption: 'Nhu cầu đầu kỳ',
                    dataField: 'need',
                    dataType: 'number',
                    area: 'data',
                    summaryType: 'sum',
                    cssClass: 'centered-cell',
                  customizeText: function (data) {
                        return data.value == 0 ||  data.value == undefined ? '' : String(data.value);
                    }
                },
                {
                    caption: 'Nhu cầu phát sinh',
                    dataField: 'more',
                    dataType: 'number',
                    area: 'data',
                    summaryType: 'sum',
                    cssClass: 'centered-cell',
                  customizeText: function (data) {
                        return data.value == 0 ||  data.value == undefined ? '' : String(data.value);
                    }
                },

                {
                    caption: 'Giảm trừ',
                    dataField: 'sub',
                    dataType: 'number',
                    area: 'data',
                    summaryType: 'sum',
                    cssClass: 'centered-cell',
                  customizeText: function (data) {
                        return data.value == 0 ||  data.value == undefined ? '' : String(data.value);
                    }
                },
                {
                    caption: 'Tổng nhu cầu',
                    dataField: 'total',
                    dataType: 'number',
                    area: 'data',
                    summaryType: 'sum',
                    cssClass: 'centered-cell',
                  customizeText: function (data) {
                        return data.value == 0 ||  data.value == undefined ? '' : String(data.value);
                    }
                },
                {
                    caption: 'Đã tuyển',
                    dataField: 'recruitmented',
                    dataType: 'number',
                    area: 'data',
                    summaryType: 'sum',
                    cssClass: 'centered-cell',
                  customizeText: function (data) {
                        return data.value == 0 ||  data.value == undefined ? '' : String(data.value);
                    }
                },

                // {
                //     caption: 'Nhân viên',
                //     dataField: 'employee_count',
                //     dataType: 'number',
                //     area: 'data',
                //     summaryType: 'sum'
                // },
                //  {
                //     caption: 'Từ chối',
                //     dataField: 'reject_count',
                //     dataType: 'number',
                //     area: 'data',
                //     summaryType: 'sum'
                // },
                //  {
                //     caption: 'Tổng',
                //     dataField: 'total_count',
                //     dataType: 'number',
                //     area: 'data',
                //     summaryType: 'sum'
                // },
                // {
                //     caption: 'Tổng',
                //     dataField: 'status_count',
                //     dataType: 'number',
                //     summaryType: 'sum',
                //     format: '',
                //     area: 'data',
                // }
            ],
            store: datas,
        },
    }).dxPivotGrid('instance');

    //    pivotGrid.bindChart("#pivotgrid-chart", {
    //         dataFieldsDisplayMode: "splitPanes",
    //         alternateDataFields: false
    //     });

    GetData()
    GetDataChart()

    // function expand() {
    //     const dataSource = pivotGrid.getDataSource();
    //     dataSource.expandHeaderItem('row', ['North America']);
    //     dataSource.expandHeaderItem('column', [2013]);
    // }

    // setTimeout(expand, 0);
});

// Mở tất cả các dropdown khi tải dữ liệu
// pivotGrid.getDataSource().expandAll(0);
// pivotGrid.getDataSource().expandAll(1);


function GetData() {
    $.ajax({
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/api/report/recruitment',
        dataType: 'json',
        data: {
            _token: "{{ csrf_token() }}",
            date_from: $("input[name='date_from']").val(),
            date_to: $("input[name='date_to']").val(),
            position_id: $("select[name='position_id[]']").val(),
            display_type: $("select[name='display_type']").val(),
        },
        success: function (resp) {
            pivotGrid.option("dataSource.store", resp.data);
            pivotGrid.getDataSource().reload();
        }
    });
}

function GetDataChart() {
    $.ajax({
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/api/report/recruitment/chart',
        dataType: 'json',
        data: {
            _token: "{{ csrf_token() }}",
            date_from: $("input[name='date_from']").val(),
            date_to: $("input[name='date_to']").val(),
            position_id: $("select[name='position_id[]']").val(),
            display_type: $("select[name='display_type']").val(),
        },
        success: function (resp) {
            pivotGridChart.option("dataSource", resp.data);
            pivotGridChart.refresh();
            //datas = resp.data;
        }
    });
}

$('#clearPositionFilter').on('click', function (e) {
    e.preventDefault();
    $('#positionSelect').val(null).trigger('change');
});

$('#filterButton').click(function (e) {
    GetDataChart()
    GetData()
});
