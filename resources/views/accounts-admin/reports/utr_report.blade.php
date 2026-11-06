<div class="container mt-4">
    <a href="{{ route('report.utr.search',['utrno'=>$utr,'export'=>'excel']) }}"
class="btn btn-success">
Download Excel
</a>

<h4>UTR Report</h4>

<div class="card p-3 mb-3">

<b>UTR :</b> {{ $utr }} <br>
<b>Ref No :</b> {{ $refNo }}

</div>

<div class="row mb-3">

<div class="col-md-4">
<div class="bg-success text-white p-3 rounded text-center">
Total Credit
<h4>₹{{ number_format($credit,2) }}</h4>
</div>
</div>

<div class="col-md-4">
<div class="bg-danger text-white p-3 rounded text-center">
Total Debit
<h4>₹{{ number_format($debit,2) }}</h4>
</div>
</div>

<div class="col-md-4">
<div class="bg-primary text-white p-3 rounded text-center">
Profit
<h4>₹{{ number_format($profit,2) }}</h4>
</div>
</div>

</div>


<table class="table table-bordered">

<thead>
<tr>
<th>Date</th>
<th>UTR</th>
<th>Remarks</th>
<th>Name</th>
<th>CR/DR</th>
<th>Amount</th>
</tr>
</thead>

<tbody>

@foreach($records as $row)

<tr>
<td>{{ \Carbon\Carbon::parse($row->date)->format('d-m-Y') }}</td>
<td>{{ $row->utrno }}</td>
<td>{{ $row->remarks }}</td>
<td>{{ $row->name }}</td>
<td>{{ $row->cr_dr }}</td>
<td>₹{{ number_format($row->amount,2) }}</td>
</tr>

@endforeach

</tbody>

</table>

</div>