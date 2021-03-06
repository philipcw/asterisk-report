@extends('layout.internal')

@section('content')
	<div class="row">
		<div class="col-sm-6">
			<h2>{{ $report['reportHeadings']['name'] }}</h2>
			<h4><small>Date:</small> {{ $dates['start'] }} - {{ $dates['end'] }}</h4>
			<h4><small>Total Calls:</small> {{ $report['reportHeadings']['totalCalls'] }}</h4>	
			<h4><small>Total Cost:</small> ${{ $report['reportHeadings']['totalCost'] }}</h4>
		</div>
		<div class="col-sm-6 text-right">
			<br>
			<a href="javascript:history.back()" class="btn btn-danger"><span class="glyphicon glyphicon-chevron-left"></span> &nbsp; Back to reports</a>
		</div>
	</div>
	<br>
	<br>

	<div class="row">
		<div class="col-sm-6">
			<p class="lead text-center">Call Summary</p>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Number</th>
						<th data-sort="int">Total Calls</th>
						<th data-sort="string">Duration</th>
						<th>Cost ($)</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($report['callSummary'] as $call)
						<tr>
							<td>{{ substr($call->dst, 1)  }}</td>
							<td>{{ $call->totalcalls }}</td>
							<td>{{ $call->formatTime() }}</td>
							<td>{{ $call->formatCost() }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>	
		</div>
		<div class="col-sm-6">
			<p class="lead text-center">Detailed call list</p>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Date / Time</th>
						<th>Number</th>
						<th data-sort="string">Duration</th>
						<th>Cost ($)</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($report['callList'] as $call)
						<tr>
							<td>{{ $call->calldate->toDayDateTimeString() }}</td>
							<td>{{ substr($call->dst, 1) }}</td>
							<td>{{ $call->formatTime() }}</td>
							<td>{{ $call->formatCost() }}</td>
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