@extends('layout')

@section('content')
	<p class="lead text-center">Select a date range to generate a report.</p>
	
	<form method="POST" action="report">
		{!! csrf_field() !!}
		<div class="row">
			<div class="col-sm-6">
				<h4>Start Date: </h4>
				<div class="form-group">
	                <div class='input-group date'>
	                    <input type='text' class="form-control" id='start-date' name="start-date" required/>
	                    <span class="input-group-addon">
	                        <span class="glyphicon glyphicon-calendar"></span>
	                    </span>
	                </div>
            	</div>
			</div>
			<div class="col-sm-6">
				<h4>End Date: </h4>
				<div class="form-group">
	                <div class='input-group date' >
	                    <input type='text' class="form-control" id='end-date' name="end-date" required />
	                    <span class="input-group-addon">
	                        <span class="glyphicon glyphicon-calendar"></span>
	                    </span>
	                </div>
            	</div>	
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 text-center">
				<button class="btn btn-primary btn-lg" type="submit">Generate Report</button>	
			</div>
		</div>
	</form>
@stop