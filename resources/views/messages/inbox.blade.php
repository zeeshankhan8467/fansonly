@extends('welcome')

@section('seo_title') @lang('navigation.messages') - @endsection

@section('content')

<div class="white-smoke-bg pt-4 pb-3">
<div class="container no-padding">

    @livewire('message')
    {{-- @include('livewire.message-in') --}}

</div>
</div>

@endsection

@push('extraCSS')
<style>
    #messages-container, #people-container {
        height: 500px;
        overflow: scroll;
    }
</style>
@endpush

{{-- attention, this is dynamically appended using stack() and push() functions of BLADE --}}
@push('extraJS')
<script>
    // listen to livewire growl messages
    window.livewire.on('scroll-to-last', function () {
        var elem = document.getElementById('messages-container');
        elem.scrollTop = elem.scrollHeight;
    });

    // reset message field
    window.livewire.on('reset-message', function () {
        var elem = document.getElementById('message-inp').value = "";
    });

    // scroll to last on switching users
    function hasClass(elem, className) {
        return elem.className.split(' ').indexOf(className) > -1;
    }

    function scrollToLast() {
        window.livewire.emit('scroll-to-last');
        window.livewire.emit('scrollToLast');
    }
</script>
@endpush