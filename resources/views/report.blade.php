@extends('layout')

@section('content')
	<h2 class="text-center">{{ $start_date }} - {{ $end_date }}</h2>
	<br>
	<br>
	<div class="row">
		<div class="col-sm-6">
			<p class="lead"><strong>Persons who made the most calls</strong></p>
			<table class="table table-hover">
				<thead>
					<tr>
						<th data-sort="string-ins">Name</th>
						<th class="text-center" data-sort="int"># of calls</th>
						<th class="text-center" data-sort="string">Total Time</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($calls as $call)
						<tr>
							<td><a href="report/accountcode/{{ $call->accountcode }}">{{ ucwords(strtolower($call->name)) }}</a></td>
							<td class="text-center">{{ $call->totalcalls }}</td>
							<td class="text-center">{{ gmdate("H:i:s", $call->totalbill) }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>	
		</div>
		<div class="col-sm-6">
			<p class="lead"><strong>Numbers called the most</strong></p>
			<table class="table table-hover">
				<thead>
					<tr>
						<th data-sort="string-ins">Number</th>
						<th class="text-center" data-sort="int"># of calls</th>
						<th class="text-center" data-sort="string">Total Time</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($top_numbers as $top_number)
						<tr>
							<td><a href="report/phonenumber/{{ $top_number->dst }}">{{ substr($top_number->dst, 1) }}</a></td>
							<td class="text-center">{{ $top_number->totalcalls }}</td>
							<td class="text-center">{{ gmdate("H:i:s", $top_number->totaltime) }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12 text-center">
			<p><a href="report/full" class="btn btn-primary ">View full report &nbsp; <span class="glyphicon glyphicon-chevron-down"></span></a></p>
		</div>
	</div>
	<br><br>
	
	
@stop