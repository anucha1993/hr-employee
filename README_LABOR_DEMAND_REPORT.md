# Labor Demand Report Documentation

## Overview
This document outlines the implementation and functionality of the Labor Demand Report (สถิติจำนวนความต้องการแรงงาน) feature in the HR Employee system.

## Features
- Filter employee data by date range, project, and employee status
- Display data in table format showing labor demand, employee counts, and success/retention rates
- Visualize data in bar charts and pie charts
- Export data to Excel

## Technical Implementation

### Components
1. **Livewire Component**
   - `App\Livewire\Reports\LaborDemandReport`
   - Handles data querying, filtering, and visualization logic

2. **Blade Template**
   - `resources\views\livewire\reports\labor-demand-report.blade.php`
   - Displays the UI with filters, table, and charts

3. **Controller**
   - `App\Http\Controllers\Reports\LaborDemandReportController`
   - Handles the Excel export functionality

4. **Export Class**
   - `App\Exports\LaborDemandExport`
   - Formats data for Excel export

### Data Flow
1. User selects filters (date range, project, employee status)
2. The Livewire component queries the database based on these filters
3. Results are displayed in the selected format (table, bar chart, pie chart)
4. User can export the data to Excel

### Key Query Logic
The report uses specific query logic to ensure accurate employee counts:

1. **Starting Employees**: 
   - Employees who started working within the selected date range
   - Filtered by factory/project and optional employee status

2. **Resigned Employees**:
   - Two conditions are combined with OR:
     - Employees with status matching "ลาออก" or "พ้นสภาพ" or "เลิกจ้าง"
     - Employees with resignation date within the selected date range
   - Filtered by factory/project and optional employee status

3. **Success Rate**:
   - Calculated as (Starting Employees / Labor Demand) * 100%

4. **Retention Rate**:
   - Calculated as ((Starting Employees - Resigned Employees) / Starting Employees) * 100%

## Troubleshooting

### Common Issues
1. **Charts not showing**: 
   - Ensure Chart.js is properly loaded
   - Check browser console for errors
   - Make sure the dispatch('updateCharts') event is triggered when changing tabs

2. **Empty data or zeros**:
   - Check that the date range is valid
   - Verify employee data exists in the selected date range
   - Confirm the employee status filter is set correctly

3. **Export not working**:
   - Check route configuration in web.php
   - Verify LaborDemandExport class is properly formatted
   - Ensure Maatwebsite\Excel is properly installed and configured

## Updates and Fixes
- July 4, 2025: Fixed employee count logic for both starting and resigned employees
- July 4, 2025: Improved chart rendering logic and event handling
- July 4, 2025: Added Excel export functionality
- July 4, 2025: Fixed duplicate routes in web.php
