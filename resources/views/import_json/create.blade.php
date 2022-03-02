@if (session()->has('message'))
<div style="color: green;">
    <b>{{ session()->get('message') }}</b>
</div>
@endif
<div>
    <form action="{{ route('importuser.store') }}" method="post">
        @csrf
        <input type="hidden" name="type" value="user" />
        <button>Import Users</button>
    </form>
</div>



<div>
    <form action="{{ route('importuser.store') }}" method="post">
        @csrf
        <input type="hidden" name="type" value="job_ava" />
        <button>Import Job Ava</button>
    </form>
</div>



<div>
    <form action="{{ route('importuser.store') }}" method="post">
        @csrf
        <input type="hidden" name="type" value="kpi_agreement" />
        <button>Import Kpi Agreement</button>
    </form>
</div>




<div>
    <form action="{{ route('importuser.store') }}" method="post">
        @csrf
        <input type="hidden" name="type" value="kpi_data" />
        <button>Import Kpi Data</button>
    </form>
</div>


<div>
    <form action="{{ route('importuser.store') }}" method="post">
        @csrf
        <input type="hidden" name="type" value="kpi_interval" />
        <button>Import Kpi Interval</button>
    </form>
</div>



<div>
    <form action="{{ route('importuser.store') }}" method="post">
        @csrf
        <input type="hidden" name="type" value="kpi_session" />
        <button>Import Kpi Session</button>
    </form>
</div>


<div>
    <form action="{{ route('importuser.store') }}" method="post">
        @csrf
        <input type="hidden" name="type" value="kpi_user_score" />
        <button>Import Kpi User Score</button>
    </form>
</div>


<div>
    <form action="{{ route('importuser.store') }}" method="post">
        @csrf
        <input type="hidden" name="type" value="kpi_year" />
        <button>Import Kpi Year</button>
    </form>
</div>
