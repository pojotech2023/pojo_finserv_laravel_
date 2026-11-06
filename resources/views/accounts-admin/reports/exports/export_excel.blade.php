<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>UTR No</th>
            <th>Remarks</th>
            <th>Name</th>
            <th>Amount</th>
            <th>CR/DR</th>
            <th>Conform Ref No</th>
        </tr>
    </thead>
    <tbody>
        @foreach($statements as $s)
            <tr>
                <td>{{ \Carbon\Carbon::parse($s->date)->format('d-m-Y') }}</td>
                <td>{{ $s->utrno }}</td>
                <td>{{ $s->remarks }}</td>
                <td>{{ $s->name }}</td>
                <td>{{ number_format($s->amount, 2) }}</td>
                <td>{{ $s->cr_dr }}</td>
                <td>{{ $s->conform_ref_no }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
