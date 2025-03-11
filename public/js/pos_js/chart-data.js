'use strict';

$(document).ready(function() {

    function calculatePercentages(incomeData, expenseData) {
        let totalData = [];
        for (let i = 0; i < incomeData.length; i++) {
            let total = incomeData[i] + Math.abs(expenseData[i]);  // Total sales + purchases
            let salesPercentage = (incomeData[i] / total) * 100;
            let purchasePercentage = (Math.abs(expenseData[i]) / total) * 100;
            totalData.push({
                salesPercentage: salesPercentage.toFixed(3), // Ensure 3 decimal places
                purchasePercentage: purchasePercentage.toFixed(3) // Ensure 3 decimal places
            });
        }
        return totalData;
    }

    // Get the dynamic data passed from the Blade template
    var incomeData = JSON.parse(document.getElementById('incomeData').textContent);
    var expenseData = JSON.parse(document.getElementById('expenseData').textContent);

    // Calculate the percentage values
    var percentages = calculatePercentages(incomeData, expenseData);

    var options = {
        series: [{
            name: 'Income',
            data: incomeData,
        }, {
            name: 'Expense',
            data: expenseData,
        }],
        colors: ['#28C76F', '#EA5455'],
        chart: {
            type: 'bar',
            height: 300,
            stacked: true,
            zoom: {
                enabled: true
            }
        },
        responsive: [{
            breakpoint: 280,
            options: {
                legend: {
                    position: 'bottom',
                    offsetY: 0
                }
            }
        }],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '40%',  // Adjust the column width for better alignment
                endingShape: 'rounded'
            },
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],  // Add all 12 months
            labels: {
                // Make sure the x-axis labels are centered
                style: {
                    colors: ['#000'],
                    fontSize: '12px',
                    fontWeight: 600,
                    fontFamily: 'Arial, sans-serif',
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function(value) {
                    return Math.floor(value);  // Show full data on Y-axis without decimals
                }
            }
        },
        legend: {
            position: 'right',
            offsetY: 40
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function (val, opts) {
                    var seriesIndex = opts.seriesIndex;
                    var percentage = seriesIndex === 0 ? percentages[opts.dataPointIndex].salesPercentage : percentages[opts.dataPointIndex].purchasePercentage;

                    // Round the value in the tooltip to 3 decimal places
                    var formattedVal = parseFloat(val).toFixed(3);

                    return "OMR " + formattedVal + "  (" + parseFloat(percentage).toFixed(3) + "%)";  // Format both values with 3 decimals
                }
            }
        },
        dataLabels: {
            enabled: false,  // Disable data labels on the columns
        }
    };

    var chart = new ApexCharts(document.querySelector("#sales_charts"), options);
    chart.render();

});
