<div>

    Dear {{ $personnel->name }}, <br />

    <p>{{ $employee->name }} has just updated the checklisted item <br />
    <b>
        {{ $checklist->checklist->action }}
    </b>
    </p>


    <div>
        <b>
            <i>Comments</i>
        </b>
    </div>
    <div>
        {{ $checklist->comments }}
    </div>


    <br />
    <div>
        @if (!empty($checklist->support_document))
            <a href="{{ asset('uploads/' . $checklist->support_document) }}">
                Please download supporting document
            </a>
        @endif
    </div>

    <div>
        <a href="{{ route('onboard.start') }}?employee_id={{ $employee->id }}">Please click here to view detail</a>
    </div>


</div>