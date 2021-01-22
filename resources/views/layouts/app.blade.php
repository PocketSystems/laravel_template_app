<!DOCTYPE html>
<html lang="en">
@include('layouts.partials.head')

<body>
@include('layouts.partials.sidebar')
<div class="content ht-100v pd-0" id="app">

    <div class="content ht-100v pd-0">
        @include('layouts.partials.header')
        <div class="content-body">
            <div class="container pd-x-0">
                @include('layouts.partials.breadcrum')

                @yield('content')
            </div>
        </div>
    </div>
</div>
@include('layouts.partials.btmjs')
</body>
</html>
