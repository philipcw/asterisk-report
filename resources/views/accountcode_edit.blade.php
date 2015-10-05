@extends('layout')

@section('content')
	<div class="row">
		<div class="col-sm-6">
			<p class="lead">Account Codes</p>
			<table class="table table-hover">
				<thead>
					<tr>
						<th data-sort="string-ins">Name</th>
						<th class="text-center" data-sort="int">Account Code</th>
						<th class="text-center" data-sort="string">Edit</th>
					</tr>
				</thead>
				
				<tbody>
					@foreach ($accountcodes as $code)
						<tr>
							<td>{{ ucwords(strtolower($code->name)) }}</td>
							<td class="text-center">{{ $code->accountcode }}</td>
							<td class="text-center">
								<a href="/accountcode/{{ $code->id }}/edit"><span class="glyphicon glyphicon-pencil"></span></a>
								&nbsp;

								<form class="delete-form" action="/accountcode/{{ $code->id }}" method="POST">
									{!! csrf_field() !!}
									{!! method_field('DELETE') !!}
									<button class="delete-btn" type="submit"><span class="text-danger glyphicon glyphicon-remove"></button>
								</form>
								
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		<div class="col-md-4 col-md-offset-1">
			<p class="lead">Edit account code - <strong>{{ $account->accountcode }}</strong></p>

			@if (count($errors) > 0)
			    <div class="alert alert-danger">
			        <ul>
			            @foreach ($errors->all() as $error)
			                <li>{{ $error }}</li>
			            @endforeach
			        </ul>
			    </div>
			@endif

			<form action="/accountcode/{{ $account->id }}" method="POST">
				{!! csrf_field() !!}
				{!! method_field('PATCH') !!}
				<div class="form-group">
					<input type="text" class="form-control" name="name" placeholder="Name" value="{{ $account->name }}" required>	
				</div>
				
				<button type="submit" class="btn btn-primary">Update</button>
			</form>
		</div>
	</div>
	
@stop