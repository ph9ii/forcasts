@if ($message = Session::get('success'))
<div class="col-md-8 col-md-offset-2">
	<div class="alert alert-success alert-block">
		<button type="button" class="close" data-dismiss="alert">×</button>	
	        <strong>{{ $message }}</strong>
	</div>
</div>
@endif

@if ($message = Session::get('error'))
<div class="col-md-8 col-md-offset-2">
	<div class="alert alert-danger alert-block">
		<button type="button" class="close" data-dismiss="alert">×</button>	
	        <strong>{{ $message }}</strong>
	</div>
</div>
@endif