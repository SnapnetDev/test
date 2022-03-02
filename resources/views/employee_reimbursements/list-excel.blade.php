<table>
    <thead>
    <tr><th>Employee Name</th>
        <th>Expense Reimbursement Type</th>
        <th>Title</th>
        <th>Expense Date</th>
        <th>Amount</th>
        <th>Description</th>
        <th>Approval Status</th>
        <th>Payment Status</th></tr>
    </thead>
    <tbody>
    @foreach($employee_reimbursements as $er)
        <tr>
        <td>{{$er->user?$er->user->name:""}}</td>
        <td>{{$er->employee_reimbursement_type?$er->employee_reimbursement_type->name:""}}</td>
        <td>{{$er->title}}</td>
        <td>{{date("m/d/Y", strtotime($er->expense_date))}}</td>

        <td>{{round($er->amount,2)}}</td>
        <td>{{$er->description}}</td>
        <td style="color:{{$er->status==0?'yellow':($er->status==1?'green':'red')}}"><span  >{{$er->status==0?'pending':($er->status==1?'approved':'rejected')}}</span></td>
        <td style="color:{{$er->status==0?'yellow':($er->status==1?'green':'red')}}"><span  >{{$er->disbursed==0?'pending':($er->disbursed==1?'disbursed':'rejected')}}</span></td>

        </tr>
    @endforeach
    </tbody>
</table>
