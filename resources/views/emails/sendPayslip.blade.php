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
    {{-- <h1>CAMEROON DEVELOPMENT CORPERATION</h1>
    <h2>PAYSLIP</h2>
    <div class="dateContainer">
       <div><p> <b>Department</b>: <span>engineering</span></p></div>
       <div><p>Pay Period: {{ getMonthName() }} </p></div>
    </div>
    <p>Dear {{ $employee->name }},</p>

    <p>Here are the details of your payslip for the period: <b> {{ getMonthName() }}</b></p> --}}

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

    <table>
        <tr>
            <th>Description</th>
            <th>Amount</th>
        </tr>
        <tr>
            <td>Total Hours Worked</td>
            <td>{{ $employeePayment->total_normal_pay_hours }}</td>
        </tr>
        <tr>
            <td>Total Overtime</td>
            <td>{{ $employeePayment->total_overtime }}</td>
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
    <p>Thank you for your hard work!</p>
    </div>
</body>
</html>
