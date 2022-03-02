<table class="table table striped">
    <thead>
    <tr>
        <th class="hidden-sm-down">Project</th>
        <th class="hidden-sm-down">empNum</th>
        <th class="hidden-sm-down">Expected Amount</th>
        <th class="hidden-sm-down">To Pay</th>
        <th class="hidden-sm-down">Status</th>
    </tr>
    </thead>
    <tbody>
    @for($i=0; $i<20; $i++)
        <tr>
            <td>
                <select name="project" id="">
                    @foreach($opportunities as $opportunity)
                        <option value="{{$opportunity->name}}">{{$opportunity->project_name}}</option>
                    @endforeach
                </select>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td>pending</td>
        </tr>
    @endfor
    </tbody>
</table>