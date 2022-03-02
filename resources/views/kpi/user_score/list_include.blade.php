@foreach ($items as $k=>$v)
    @include('kpi.user_score.edit')
@endforeach


<table class="table table-stripped">

    <tr>
        <th>
            Requirement
        </th>
        <th>
            Percentage
        </th>
        <th>
            Your Score
        </th>
        <th>
            Line Manager Score
        </th>
        <th>
            Hr Score
        </th>
        <th>
            Actions
        </th>
    </tr>

    @foreach ($items as $k=>$v)

        <tr data-value-set="tag{{ $v->id }}"  data-kpi-data-id="{{ $v->id }}">
            <td>
                {{ $v->requirement }}
            </td>
            <td>
                {{ $v->percentage }}
            </td>
            <td user_score data-user-score="update_user_score_{{ $v->id }}" data-kpi-data-id="{{ $v->id }}">
                0.0
            </td>
            <td manager_score data-line-manager-score="update_line_manager_score_{{ $v->id }}">
                0.0
            </td>
            <td hr_score data-hr-score="update_hr_score_{{ $v->id }}">
                0.0
            </td>
            <td>
                @if ($hasAgreed)
                    <a data-toggle="modal" data-target="#edit-evaluation-data{{ $v->id }}" href="#" class="btn btn-sm btn-info" >Evaluate</a>
                @else
                    <a href="#" data-scroll-to="#agreement" class="btn btn-sm btn-warning" >Accept Agreement</a>
                @endif
            </td>
        </tr>

    @endforeach


</table>