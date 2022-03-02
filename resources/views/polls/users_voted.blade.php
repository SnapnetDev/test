<table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
    <thead>
    <tr>
        <th>Name</th>
        <th>Department</th>
        <th>Time Voted</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ \App\Department::find($user->department_id)->name }}</td>
            <td>
               {{ $user->poll_response_time->format('d, M Y H:i s A') }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>