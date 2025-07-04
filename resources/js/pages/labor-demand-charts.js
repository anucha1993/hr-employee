/**
 * Labor Demand Report Charts
 * Custom Chart.js initialization for labor demand reports
 */

// Check Chart.js dependency
document.addEventListener('DOMContentLoaded', function() {
    // Make sure Chart.js is available
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded. Please make sure it is properly included.');
    } else {
        console.log('Chart.js successfully loaded');
    }
});

// Helper function for rgba color generation
function hexToRGB(hex, alpha) {
    var r = parseInt(hex.slice(1, 3), 16),
        g = parseInt(hex.slice(3, 5), 16),
        b = parseInt(hex.slice(5, 7), 16);

    if (alpha) {
        return "rgba(" + r + ", " + g + ", " + b + ", " + alpha + ")";
    } else {
        return "rgb(" + r + ", " + g + ", " + b + ")";
    }
}

// Define default colors
const defaultColors = ["#3bc0c3", "#4489e4", "#d03f3f", "#716cb0", "#f24f7c", "#75b432", "#f0ad4e", "#ff5b5b"];

// LaborDemandCharts class
var LaborDemandCharts = function() {
    this.charts = [];
    this.defaultColors = defaultColors;
};

// Bar chart initialization
LaborDemandCharts.prototype.initBarCharts = function(chartData) {
    console.log('Initializing bar charts with data:', chartData);

    if (!chartData || !chartData.labels || chartData.labels.length === 0) {
        console.warn('No chart data available for bar charts');
        return;
    }

    // Destroy existing charts to prevent duplicates
    if (window.demandBarChart) window.demandBarChart.destroy();
    if (window.ratesBarChart) window.ratesBarChart.destroy();

    // Demand vs Started vs Resigned Bar Chart
    const demandCtx = document.getElementById('myChart');
    if (demandCtx) {
        window.demandBarChart = new Chart(demandCtx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'ความต้องการแรงงาน',
                        data: chartData.demandData,
                        backgroundColor: hexToRGB(this.defaultColors[0], 0.6),
                        borderColor: this.defaultColors[0],
                        borderWidth: 1
                    },
                    {
                        label: 'พนักงานเข้างาน',
                        data: chartData.startedData,
                        backgroundColor: hexToRGB(this.defaultColors[1], 0.6),
                        borderColor: this.defaultColors[1],
                        borderWidth: 1
                    },
                    {
                        label: 'พนักงานลาออก',
                        data: chartData.resignedData,
                        backgroundColor: hexToRGB(this.defaultColors[2], 0.6),
                        borderColor: this.defaultColors[2],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'จำนวนความต้องการแรงงาน vs พนักงานเข้างาน vs พนักงานลาออก',
                        font: { size: 16 }
                    },
                    legend: { position: 'top' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'จำนวน (คน)' }
                    }
                }
            }
        });

        this.charts.push(window.demandBarChart);
    }

    // Success Rate vs Retention Rate Bar Chart
    const ratesCtx = document.getElementById('myChart2');
    if (ratesCtx) {
        window.ratesBarChart = new Chart(ratesCtx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'อัตราความสำเร็จ (%)',
                        data: chartData.successRateData,
                        backgroundColor: hexToRGB(this.defaultColors[3], 0.6),
                        borderColor: this.defaultColors[3],
                        borderWidth: 1
                    },
                    {
                        label: 'อัตราการคงอยู่ (%)',
                        data: chartData.retentionRateData,
                        backgroundColor: hexToRGB(this.defaultColors[4], 0.6),
                        borderColor: this.defaultColors[4],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'อัตราความสำเร็จ vs อัตราการคงอยู่',
                        font: { size: 16 }
                    },
                    legend: { position: 'top' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'ร้อยละ (%)' },
                        max: 100
                    }
                }
            }
        });

        this.charts.push(window.ratesBarChart);
    }

    console.log('Bar charts initialized successfully');
};

// Pie chart initialization
LaborDemandCharts.prototype.initPieCharts = function(chartData) {
    console.log('Initializing pie charts with data:', chartData);

    if (!chartData || !chartData.labels || chartData.labels.length === 0) {
        console.warn('No chart data available for pie charts');
        return;
    }

    // Destroy existing charts to prevent duplicates
    if (window.demandPieChart) window.demandPieChart.destroy();
    if (window.startedPieChart) window.startedPieChart.destroy();
    if (window.successPieChart) window.successPieChart.destroy();
    if (window.retentionPieChart) window.retentionPieChart.destroy();

    // Generate colors array based on the number of data points
    const backgroundColors = this.defaultColors.slice(0, chartData.labels.length);
    const borderColors = backgroundColors.map(color => color);

    // Demand Pie Chart
    const demandPieCtx = document.getElementById('myChart3');
    if (demandPieCtx) {
        window.demandPieChart = new Chart(demandPieCtx, {
            type: 'pie',
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.demandData,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} คน (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        this.charts.push(window.demandPieChart);
    }

    // Started Employees Pie Chart
    const startedPieCtx = document.getElementById('myChart4');
    if (startedPieCtx) {
        window.startedPieChart = new Chart(startedPieCtx, {
            type: 'pie',
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.startedData,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} คน (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        this.charts.push(window.startedPieChart);
    }

    // Success Rate Pie Chart
    const successPieCtx = document.getElementById('myChart5');
    if (successPieCtx) {
        window.successPieChart = new Chart(successPieCtx, {
            type: 'pie',
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.successRateData,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                return `${label}: ${value}%`;
                            }
                        }
                    }
                }
            }
        });

        this.charts.push(window.successPieChart);
    }

    // Retention Rate Pie Chart
    const retentionPieCtx = document.getElementById('myChart6');
    if (retentionPieCtx) {
        window.retentionPieChart = new Chart(retentionPieCtx, {
            type: 'pie',
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.retentionRateData,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                return `${label}: ${value}%`;
                            }
                        }
                    }
                }
            }
        });

        this.charts.push(window.retentionPieChart);
    }

    console.log('Pie charts initialized successfully');
};

// Initialize all charts
LaborDemandCharts.prototype.init = function(chartData, chartType) {
    console.log('Initializing LaborDemandCharts:', chartType);
    
    if (chartType === 'bar') {
        this.initBarCharts(chartData);
    } else if (chartType === 'pie') {
        this.initPieCharts(chartData);
    } else {
        console.warn('Unknown chart type:', chartType);
    }
};

// Create the main instance and export to window
console.log('Creating LaborDemandCharts instance');
window.LaborDemandCharts = new LaborDemandCharts();
console.log('LaborDemandCharts initialized and attached to window');
