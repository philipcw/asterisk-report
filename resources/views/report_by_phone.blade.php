@extends('layout')

@section('content')
	<div class="row">
		<div class="col-sm-6">
			
		</div>
		<div class="col-sm-6 text-right">
			<br>
			<a href="javascript:history.back()" class="btn btn-danger"><span class="glyphicon glyphicon-chevron-left"></span> &nbsp; Back to reports</a>
		</div>
	</div>
	<br><br>

	<div class="row">
		<div class="col-sm-4">
			<h2><small>#: </small>{{ substr($number, 1) }}</h2>
			<h4><small>Date:</small> {{ $start_date }} - {{ $end_date }}</h4>
			<h4><small>Total Calls:</small> {{ $totalcalls }} </h4>	
			<h4><small>Total Cost:</small> {{ $totalcost }}</h4>	
		</div>
		<div class="col-sm-8">
			<p class="lead">Detailed call list.</p>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Date / Time</th>
						<th data-sort="string">Duration</th>
						<th data-sort="string">Cost</th>
						<th data-sort="string">Person</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($calls as $call)
						<tr>
							<td>{{ $call->calldate->toDayDateTimeString() }}</td>
							<td>{{ $call->formatTime() }}</td>
							<td>{{ $call->calculateCost() }}</td>
							<td>{{ ucwords(strtolower($call->name)) }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<br><br>
@stop