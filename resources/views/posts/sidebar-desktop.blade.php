<div class="col-12 col-md-4 d-none d-sm-none d-md-none d-lg-block">
	<div class="sticky-top sticky-sidebar">
	
	@if( isset($feed) && $feed->count() )
		<div class="lastId d-none">{{ $feed->last()->id }}</div>
	@endif

	@livewire('creators-sidebar')

	<br>
	</div>
</div>