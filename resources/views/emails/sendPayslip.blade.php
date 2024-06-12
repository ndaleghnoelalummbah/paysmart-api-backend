<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payslip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        h1, h2 {
            text-align: center
        }
        h3 {
            margin-top: 25px;
        }
        .payslip-container {
            width: 100%;
            /* max-width: 600px; */
            margin: 0 auto;
            padding: 20px;
            /* border: 1px solid #000; */
        }
        .payslip-header {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .flex-container{
            display: flex;
            flex-direction: row;
            justify-content:space-between;
             margin-bottom: 10px;
        }
        .payslip-section {
            margin-bottom: 15px;
        }
        .payslip-label {
            font-weight: bold;
            text-transform: capitalize;
            margin-right: 10px;
        }
        .payslip-value {
            display: inline-block;
            min-width: 150px;
        }
        .payslip-line {
            border-bottom: 1px solid #000;
            margin-bottom: 25px;
        }

    </style>
</head>
<body>
     <div class="payslip-container">
        <div class="payslip-header">
            <p>Cameroon Development Corporation </p>
            <p>Payslip</p>
        </div>
        
       <div class="flex-container">
            <div>
                <span class="payslip-label">Department:</span>
                <span class="payslip-value">{{ $department->name }}</span>
            </div>
            <div>
                <span class="payslip-label">Pay Period:</span>
                <span class="payslip-value"> {{ getMonthName() }}</span>
            </div>
       </div>
        <div class="payslip-line"></div>
        
        <div class="payslip-section">
            <span class="payslip-label">Employee:</span>
            <span class="payslip-value">{{ $employee->matricule }}</span>
            <span class="payslip-value">{{ $employee->name }}</span>
        </div>
        
        
        <div class="payslip-section">
            <span class="payslip-label">Position:</span>
            <span class="payslip-value">{{ $employee->position }}</span>
        </div>
    <h3><u>Workers's Daily Operations</u></h3>
    <table>
        <tr>
            <th>Date</th>
            <th>Work Status</th>
            <th>Normal Pay Hours</th>
            <th>Overtime Hours</th>
        </tr>
       @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->work_date->format('Y-m-d') }}</td>
                    <td>{{ $attendance->status }}</td>
                    <td>{{ $attendance->normal_pay_hours }}</td>
                    <td>{{ $attendance->overtime_hour }}</td>
                </tr>
            @endforeach
        <tr>
            <td colspan="2">Total Hours Paid</td>
            <td>{{ $employeePayment->total_normal_pay_hours }}</td>
            <td>{{ $employeePayment->total_overtime }}</td>
        </tr>
    </table>
        <table>
        <tr>
            <th>Description</th>
            <th>Amount</th>
        </tr>
        <tr>
            <td>Income Tax</td>
            <td>{{ $employeePayment->income_tax }}</td>
        </tr>
        <tr>
            <td>Retirement Deduction</td>
            <td>{{ $employeePayment->retirement_deduction }}</td>
        </tr>
        <tr>
            <td>House Allowance Pay</td>
            <td>{{ $employeePayment->house_allowance_pay }}</td>
        </tr>
        <tr>
            <td>Longevity Allowance Pay</td>
            <td>{{ $employeePayment->longevity_allowance_pay }}</td>
        </tr>
        <tr>
            <td>Overtime Pay</td>
            <td>{{ $employeePayment->overtime_pay }}</td>
        </tr>
        @if ($employeePayment->leave_pay > 0)
        <tr>
            <td>Leave Pay</td>
            <td>{{ $employeePayment->leave_pay }}</td>
        </tr>
        @endif
        @if ($employeePayment->retirement_pay > 0)
        <tr>
            <td>Retirement Pay</td>
            <td>{{ $employeePayment->retirement_pay }}</td>
        </tr>
        @endif
        <tr>
            <td>Net Pay</td>
            <td>{{ $employeePayment->net_pay }}</td>
        </tr>
        <tr>
            <td>Gross Pay</td>
            <td>{{ $employeePayment->gross_pay }}</td>
        </tr>
    </table>
    <table>
         <tr>
            <th>DAYS WORKED</th>
            <th>ABSENT</th>
            <th>SICK</th>
            <th>HOLIDAY</th>
            <th>TOTAL</th>
        </tr>
         <tr>
            <td>{{ $totalDaysWorked }}</td>
            <td>{{$totalAbsence }}</td>
            <td>{{$totalSickRest}}</td>
            <td>{{ $totalHolidays }}</td>
            <td>{{ $totalDaysWorked  + $totalSickRest + $totalHolidays }}</td>
        </tr>
    </table>
    <p>Thank you for your hard work!</p>
    </div>
</body>
</html>
