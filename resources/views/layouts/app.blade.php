<!DOCTYPE html>
<html lang="en">
@include('layouts.partials.head')

<body>
<div class="content ht-100v pd-0">
    @include('layouts.partials.header')
    @include('layouts.partials.sidebar')

    <div class="content-body">
        <div class="container">
            @include('layouts.partials.breadcrum')
            <br>
            @yield('content')
        </div>
    </div>
</div>
@include('layouts.partials.btmjs')
</body>
</html>
