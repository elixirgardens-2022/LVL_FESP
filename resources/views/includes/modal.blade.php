<aside id="{{ $id_name ?? 'modal' }}">
    <div style="width: {{ $width }};">
        <span class="display-id fl"></span>
        
        <div class="modal-close-button"><a href="" onclick="return false;"><span style="font-size: 12px;">(Esc) </span>&times;</a></div>
        
        <form id="modal_form" class="form-style" method="post">
            @CSRF
            <!-- <input type="hidden" name="action" value="editOrder"> -->
            <input type="hidden" name="id" value="">
            
            @foreach($fields as $name => $extra)
            <input type="hidden" name="input_{{ $name }}_orig">
            <input type="text" name="input_{{ $name }}" placeholder="{{ $name }}" autocomplete="off" {!! $extra !!}>
            @endforeach
            
            <br><input type="submit" value="Save" class="btn fs20" style="width: 300px;">
        </form>
    </div>
</aside>