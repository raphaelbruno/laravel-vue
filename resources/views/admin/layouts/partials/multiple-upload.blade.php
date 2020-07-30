<div id="multiple-upload-{{ $name }}" class="multiple-upload">
    <div class="drop-here text-primary d-none">
        <i class="fas fa-upload"></i>
        <span>@lang('crud.drop-files-here')</span>
    </div>
    <div class="header d-flex justify-content-between">
        <span class="ml-1">
            @lang('crud.total'): <span class="multiple-upload-total">0</span>
        </span>
        <label for="{{ $name }}" class="btn btn-primary btn-sm">
            <input id="{{ $name }}" class="d-none" type="file" name="{{ $name }}" multiple="multiple" accept="{{ isset($extensions) ? $extensions : '' }}" {{ isset($required) && $required ? 'required' : '' }} />
            @lang('crud.choose-files')
        </label>
    </div>

    <ul class="multiple-upload-preview" class="d-flex justify-content-around flex-wrap"></ul>
</div>

<script>
    var addedItems = [
        @foreach ($addedItems as $image)
        <?php
            $url = empty(parse_url($image->src)['scheme'])
                ? asset(Storage::url($image->src))
                : $image->src;
        ?>
        { id: {{ $image->id ? $image->id : 'null' }},  src: '{{ $url }}' },
        @endforeach
    ];
    new MultipleUpload('{{ $name }}', addedItems);
</script>