@extends('layout')

@section('content')
	<h2 class="text-center">{{ $start_date }} - {{ $end_date }}</h2>
	<br>
	<div class="col-sm-12 text-right">
		<a href="javascript:history.back()" class="btn btn-danger"><span class="glyphicon glyphicon-chevron-left"></span> &nbsp; Report Summary</a>	
	</div>
	<br>
	<br>
	<br><br>

	<div class="row">
		<div class="col-sm-6">
			<p class="lead text-center">Persons who made the most calls.</p>
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
							<td><a href="/report/accountcode/{{ $call->accountcode }}">{{ ucwords(strtolower($call->name)) }}</a></td>
							<td class="text-center">{{ $call->totalcalls }}</td>
							<td class="text-center">{{ $call->formatTime() }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>	
		</div>
		<div class="col-sm-6">
			<p class="lead text-center">Numbers called the most </p>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Number</th>
						<th class="text-center" data-sort="int"># of calls</th>
						<th class="text-center" data-sort="string">Total Time</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($top_numbers as $top_number)
						<tr>
							<td><a href="/report/phonenumber/{{ $top_number->dst }}">{{ substr($top_number->dst, 1) }}</a></td>
							<td class="text-center">{{ $top_number->totalcalls }}</td>
							<td class="text-center">{{ $top_number->formatTime() }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

	<br><br>	
@stop