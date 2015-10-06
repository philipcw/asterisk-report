@extends('layout')

@section('content')
	<div class="row">
		<div class="col-sm-6">
			<h2>{{ $name }}</h2>
			<h4><small>Date:</small> {{ $start_date }} - {{ $end_date }}</h4>
			<h4><small>Total Calls:</small> {{ $totalcalls }}</h4>	
			<h4><small>Total Cost:</small> $0.00</h4>
		</div>
		<div class="col-sm-6 text-right">
			<br>
			<a href="javascript:history.back()" class="btn btn-danger">Back to reports</a>
		</div>
	</div>
	<br>
	<br>

	<div class="row">
		<div class="col-sm-6">
			<p class="lead"><strong>Call Summary</strong></p>
			<table class="table table-hover">
				<thead>
					<tr>
						<th data-sort="int">Number</th>
						<th data-sort="int">Total Calls</th>
						<th data-sort="string">Duration</th>
						<th data-sort="string">Cost</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($summary as $number)
						<tr>
							<td>{{ substr($number->dst, 1)  }}</td>
							<td>{{ $number->totalcalls }}</td>
							<td>{{ $number->formatTime() }}</td>
							<td></td>
						</tr>
					@endforeach
				</tbody>
			</table>	
		</div>
		<div class="col-sm-6">
			<p class="lead"><strong>Detailed call list</strong></p>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Date / Time</th>
						<th data-sort="int">Number</th>
						<th data-sort="string">Duration</th>
						<th data-sort="string">Cost</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($calls as $call)
						<tr>
							<td>{{ $call->calldate->toDayDateTimeString() }}</td>
							<td>{{ substr($call->dst, 1) }}</td>
							<td>{{ $call->formatTime() }}</td>
							<td></td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<br><br>
	
@stop