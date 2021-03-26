<div class="row">
@forelse($creators as $p)

    <div class="col-12 col-md-6 @if(isset($cols)) col-lg-{{ $cols }} @else col-lg-4 @endif mb-4">
        <div class="card shadow rounded">
            
            <img src="{{ url(secure_image($p->coverPicture, 520, 280)) }}" class="img-fluid rounded"/>

           
            <div class="rounded-circle p-1 loop-rounded-pic">
                <a href="{{ route('profile.show', ['username' => $p->username]) }}">
                    <img src="{{ url(secure_image($p->profilePic, 100, 100)) }}" class="rounded-circle img-fluid profilePicExtraSmall"/>
                </a>
            </div>

            <div class="profile-content rounded-bottom pt-1 badge-content">
                <a href="{{ route('profile.show', ['username' => $p->username]) }}" class="text-white text-bold text-wrap text-bold font-18">
                    {{ $p->name }} <i class="fas fa-check-circle"></i> 
                </a>
                <br>
                <a href="{{ route('profile.show', ['username' => $p->username]) }}" class="text-white">
                    {{ $p->handle }}
                </a>
                <br>
                <a href="{{ route('browseCreators', ['category' => $p->category_id, 'category_name' => str_slug($p->category->category) ]) }}" class="text-white">
                    <small><i class="fas fa-tags"></i> {{ $p->category->category }}</small>
                </a>
            </div>

        </div>
    </div>
@empty
    <div class="card shadow-sm p-3 text-center">
        @lang('general.noCreators')
    </div>
@endforelse

</div>
<div class="container sidebarLinks">
@if(method_exists($creators, 'links'))
{{ $creators->links() }}
@endif
</div>