
@foreach($query_threads as $query)
<tr>
    <td>
        <a class="avatar avatar-lg" href="javascript:void(0)">
            <img src="{{$query->createdby->user_image}}" alt="...">
        </a> @ {{$query->created_at->diffForHumans()}}
    </td>
    <td id="query_parent">
        {!! $query->content !!}
    </td>
</tr>
@endforeach
