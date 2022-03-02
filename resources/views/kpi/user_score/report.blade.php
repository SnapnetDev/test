<div style="font-family: monospace;">
    <h4 style="text-align: center;">
        EVALUATION REPORT {{ $interval }}
    </h4>
</div>
@php
    $tuser = 0;
    $tmanager = 0;
    $thr = 0;
    $count_ = 0;
    $c = &$count_;
@endphp
<div style="font-family: monospace;">
    @php
      $title = 'Functional KPIs';
      $list = $department_list;
      $bgParent = '#62a8ea';
      $bgChild = '#9dc5ea';
    @endphp
    @include('kpi.user_score.report_reuse')

    @php
        $title = 'Functional KPIs (Personal)';
        $list = $individual_department_list;
    @endphp
    @if ($has_individual_department_list)
     @include('kpi.user_score.report_reuse')
    @endif

    @php
        $title = 'Organizational KPIs';
        $list = $organisation_list;
        $bgParent = '#ff9736';
        $bgChild = '#ecb077';
    @endphp
    @include('kpi.user_score.report_reuse')

    @php
        $title = 'Organizational KPIs (Personal)';
        $list = $individual_organisation_list;
    @endphp
    @if ($has_individual_organisation_list)
      @include('kpi.user_score.report_reuse')
    @endif


</div>
<div style="font-family: monospace;">
   Average User Score: <b>{{ number_format(($avgUser),2) }}</b><br />
   Average Line-Manager Score: <b>{{ number_format(($avgLineManager),2) }}</b><br />
   Average HR Score: <b>{{ number_format(($avgHr),2) }}</b><br />
</div>

<div style="font-family: monospace;">
    <h3 style="text-align: right;font-size: 25px;color: green;">
        Percentage Score : {{ number_format(($avgUser + $avgLineManager + $avgHr),2) }} %
    </h3>
</div>