@if($investor)
    <tr>
        <th>Investor Name</th>
        <td>{{ $investor->name }}</td>
    </tr>

    <tr>
        <th>Interest %</th>
        <td>{{ $investor->percentage }}%</td>
    </tr>
@else
    <tr>
        <td colspan="2" class="text-danger">
            No investor data available
        </td>
    </tr>
@endif
