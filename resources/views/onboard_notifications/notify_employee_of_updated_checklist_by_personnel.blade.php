<div>

    Dear {{ $employee->name }}, <br />

    <p>{{ $personnel->name }} has just responded to the checklisted item <br />
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

</div>