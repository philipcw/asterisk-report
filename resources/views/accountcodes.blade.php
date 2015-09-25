@extends('layout')

@section('content')
	<h2 class="text-center">Account Codes</h2>
	
	<br><br>
	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
			<table class="table table-hover">
				<thead>
					<tr>
						<th data-sort="string-ins">Name</th>
						<th class="text-center" data-sort="int">Account Code</th>
						<th class="text-center" data-sort="string">Actions</th>
					</tr>
				</thead>
				
				<tbody>
					@foreach ($accountcodes as $code)
						<tr>
							<td>{{ ucwords(strtolower($code->name)) }}</td>
							<td class="text-center">{{ $code->accountcode }}</td>
							<td class="text-center"></td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	
@stop