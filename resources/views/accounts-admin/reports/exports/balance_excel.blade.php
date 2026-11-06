<table border="1">
    <thead>
        <tr>
            <th>Date</th>
            <th>UTR No</th>
            <th>Remarks</th>
            <th>Name</th>
            <th>CR/DR</th>
            <th>Amount</th>
            <th>Running Balance</th>
            <th>Ref No</th>
        </tr>
    </thead>
    <tbody>
        @php $balance = 0; @endphp
        @foreach($entries as $e)
            @php
                if($e->cr_dr == 'CR') $balance += $e->amount;
                else $balance -= $e->amount;
            @endphp
            <tr>
                <td>{{ \Carbon\Carbon::parse($e->date)->format('d-m-Y') }}</td>
                <td>{{ $e->utrno }}</td>
                <td>{{ $e->remarks }}</td>
                <td>{{ $e->name }}</td>
                <td>{{ $e->cr_dr }}</td>
                <td>{{ number_format($e->amount, 2) }}</td>
                <td>{{ number_format($balance, 2) }}</td>
                <td>{{ $e->conform_ref_no }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
