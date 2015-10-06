@extends('layout')

@section('content')
	<div class="row">
		<div class="col-sm-6">
			<h2>{{ $name }}</h2>
			<h4><small>Date:</small> {{ $start_date }} - {{ $end_date }}</h4>
			<h4><small>Total Calls:</small> {{ $totalcalls }}</h4>	
			<h4><small>Total Cost:</small> {{ $totalcost }}</h4>
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
						<th>Cost</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($summary as $number)
						<tr>
							<td>{{ substr($number->dst, 1)  }}</td>
							<td>{{ $number->totalcalls }}</td>
							<td>{{ $number->formatTime() }}</td>
							<td>{{ $number->calculateCost() }}</td>
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
						<th>Cost</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($calls as $call)
						<tr>
							<td>{{ $call->calldate->toDayDateTimeString() }}</td>
							<td>{{ substr($call->dst, 1) }}</td>
							<td>{{ $call->formatTime() }}</td>
							<td>{{ $call->calculateCost() }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<br><br>
	
@stop