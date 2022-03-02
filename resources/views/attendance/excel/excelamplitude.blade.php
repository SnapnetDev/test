<div class="table-responsive">
    <table class="table table striped">
        <tbody>
        @if(count($records)>0)
            @foreach($records as $record)
                <tr>
                    <td>{{$record}}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td>Nothing yet</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>