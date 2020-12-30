<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Total Attempt</th>
        <th>Total Skip</th>
        <th>Total Wrong</th>
        <th>Total Right</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $result)
        <tr>
            <td>{{ $result->user->full_name }}</td>
            <td>{{ $result->total_attempted }}</td>
            <td>{{ $result->total_skipped }}</td>
            <td>{{ $result->total_wrong }}</td>
            <td>{{ $result->total_right }}</td>
        </tr>
    @endforeach
    </tbody>
</table>