<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>Payslip</title>
</head>
<body>
    <h1>Payslip for {{ $employee->name }}</h1>
    <p>Dear {{ $employee->name }},</p>

    <p>Here are the details of your payslip for the period:</p>

    <table>
        <tr>
            <th>Description</th>
            <th>Amount</th>
        </tr>
        <tr>
            <td>Total Hours Worked</td>
            <td>{{ $employeePayment->total_hours_worked }}</td>
        </tr>
        <tr>
            <td>Total Overtime</td>
            <td>{{ $employeePayment->total_overtime }}</td>
        </tr>
        <tr>
            <td>Net Pay</td>
            <td>{{ $employeePayment->net_pay }}</td>
        </tr>
        <tr>
            <td>Gross Pay</td>
            <td>{{ $employeePayment->gross_pay }}</td>
        </tr>
        <tr>
            <td>Income Tax</td>
            <td>{{ $employeePayment->income_tax }}</td>
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
            <td>Retirement Deduction</td>
            <td>{{ $employeePayment->retirement_deduction }}</td>
        </tr>
        <tr>
            <td>Leave Pay</td>
            <td>{{ $employeePayment->leave_pay }}</td>
        </tr>
        <tr>
            <td>Retirement Pay</td>
            <td>{{ $employeePayment->retirement_pay }}</td>
        </tr>
    </table>

    <p>Thank you for your hard work!</p>
</body>
</html>