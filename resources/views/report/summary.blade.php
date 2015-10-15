@extends('layout.internal')

@section('content')
	<h2 class="text-center">{{ $dates['start'] }} - {{ $dates['end'] }}</h2>
	<br>
	<br>
	<p class="text-center">
	<a href="/download/report" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-export"></span> Download Report</a>	
	</p>
	<br>
	<br>
	<div class="row">
		<div class="col-sm-6">
			<p class="lead text-center">Persons who made the most calls</p>
			<table class="table table-hover">
				<thead>
					<tr>
						<th data-sort="string">Name</th>
						<th class="text-center" data-sort="int"># of calls</th>
						<th class="text-center" data-sort="string">Total Time</th>
						<th class="text-center">Total Cost ($)</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($report['accountsUsed'] as $account)
						<tr>
							<td><a href="{{ $account->accountcode }}/report">{{ $account->name }}</a></td>
							<td class="text-center">{{ $account->totalcalls }}</td>
							<td class="text-center">{{ $account->formatTime() }}</td>
							<td class="text-center">{{ $account->formatCost() }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>	
		</div>
		<div class="col-sm-6">
			<p class="lead text-center">Numbers called the most</p>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Number</th>
						<th class="text-center" data-sort="int"># of calls</th>
						<th class="text-center" data-sort="string">Total Time</th>
						<th class="text-center">Total Cost ($)</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($report['numbersCalled'] as $number)
						<tr>
							<td><a href="{{ $number->dst }}/report">{{ substr($number->dst, 1) }}</a></td>
							<td class="text-center">{{ $number->totalcalls }}</td>
							<td class="text-center">{{ $number->formatTime() }}</td>
							<td class="text-center">{{ $number->formatCost() }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<br><br>
	
	<footer>
		<a href="#top" class="go-top" title="Back to Top"><span class="glyphicon glyphicon-chevron-up"></span></a>
	</footer>
	
@stop