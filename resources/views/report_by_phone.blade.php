@extends('layout')

@section('content')
	<div class="row">
		<div class="col-sm-6">
			
		</div>
		<div class="col-sm-6 text-right">
			<br>
			<a href="javascript:history.back()" class="btn btn-danger">Back to reports</a>
		</div>
	</div>
	<br><br>

	<div class="row">
		<div class="col-sm-4">
			<h2><small>#: </small>{{ substr($number, 1) }}</h2>
			<h4><small>Date:</small> {{ $start_date }} - {{ $end_date }}</h4>
			<h4><small>Total Calls:</small> </h4>	
			<h4><small>Total Cost:</small> $0.00</h4>	
		</div>
		<div class="col-sm-8">
			<p class="lead">Detailed call list.</p>
			<table class="table table-hover">
				<thead>
					<tr>
						<th data-sort="string">Date / Time</th>
						<th data-sort="string">Duration</th>
						<th data-sort="string">Cost</th>
						<th data-sort="string">Person</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($calls as $call)
						<tr>
							<td>{{ $call->calldate->toDayDateTimeString() }}</td>
							<td>{{ gmdate("H:i:s", $call->billsec) }}</td>
							<td>$0.00</td>
							<td>{{ ucwords(strtolower($call->name)) }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<br><br>
@stop