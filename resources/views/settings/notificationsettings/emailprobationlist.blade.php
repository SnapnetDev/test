<html>
<head>
    <style>
        #customers {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
        }

        #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 12px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 5px;
            padding-bottom: 5px;
            text-align: left;
            background-color: #36459b;
            color: white;
            font-size: 12px;
        }

        tbody {
            page-break-inside: avoid;
        }

        thead {
            display: table-header-group;
            margin-top: 100px;
        }
    </style>

</head>
<body>


<table id="customers">
    <thead>
    <tr>
        <td>Name</td>
        <td>Hire Year-Month</td>
        <td>Expected Confirmation Year-Month</td>
    </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)
        <tr>
            <td>{{$user->name}}</td>
            <td>{{$user->getMonth($user->hire_date)}}</td>
            <td>

                @php $user->autoChangeStatus($user,$probationPolicies); @endphp
                @if($user->probation_period==0)
                {{$user->getMonth($user->hiredate,$probationPolicies->probation_period)}}
                    @else
                    {{$user->getMonth($user->hiredate,$user->probation_period)}}
                @endif
            </td>
        </tr>

    @endforeach
    </tbody>
</table>


</body>
