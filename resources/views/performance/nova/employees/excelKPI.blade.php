<table>
    <thead>
    <tr>
        <th>S/N</th>
        <th>KPI</th>
        <th>Weight</th>
        <th>Target</th>
        <th>Actual</th>
        <th>Score</th>
        <th>Measurement</th>
        <th>Data Source</th>
        <th>Frequency of Data</th>
        <th>Responsible Collation Unit</th>
    </tr>
    </thead>
    <tbody>
    @foreach($kpis as $kpi)
        <tbody>
        <tr>
            <td>{{ $loop->index+1 }}</td>
            <td>{{ $kpi->kpi_question }}</td>
            <td>{{ $kpi->weight }}</td>
            <td>{{ $kpi->target }}</td>
            <td>{{ $kpi->actual }}</td>
            <td>{{ $kpi->score }}</td>
            <td>{{ $kpi->measurement }}</td>
            <td>{{ $kpi->data_source }}</td>
            <td>{{ $kpi->frequency_of_data }}</td>
            <td>{{ $kpi->responsible_collation_unit }}</td>
        </tr>
        </tbody>
    @endforeach
</table>