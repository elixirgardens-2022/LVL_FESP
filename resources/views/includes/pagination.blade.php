@if (session('page'))
<div class="mr30 dib fl pagi-container">
    <div class="fl mr40">
        @if (session('page') > 1)
        <a href="{{ session('prev_link') }}"><div class="prev-next btn flip">&#10140;</div></a>
        @else
        <div style="background: #fff;" class="prev-next p-n-disable flip no-sel">&#10140;</div>
        @endif
        
        <div class='dib ml6 mr6'>{{ session('pageXofY') }}</div>
        
        @if (session('page') != session('total_pages'))
        <a href="{{ session('next_link') }}"><div class="prev-next btn">&#10140;</div></a>
        @else
        <div class="prev-next p-n-disable no-sel">&#10140;</div>
        @endif
    </div>
    
    <form method="get" class="fl mr30">
        <input type="hidden" name="page" value="1">
        <input type="text" name="limit" value="{{ session('limit') }}" autocomplete="off" class="w40">
        <input type="submit" class="btn ml6 mt-4 lh1-43 h20" name="update_limit" value="Limit">
    </form>
    
    <form action="{{ route('products') }}" method="get" class="fl">
        <input type="hidden" name="limit" value="{{ session('limit') }}">
        <input type="text" name="page" value="{{ session('page') }}" autocomplete="off" class="w40">
        <input type="submit" class="btn ml6 mt-4 lh1-43 h20" name="goto" value="Page">
    </form>
</div>
@else
<div class="w470 dib fl">
    <a href="{{ route('products') }}" class="btn h30 lh30 fl">All Products</a>
</div>
@endif