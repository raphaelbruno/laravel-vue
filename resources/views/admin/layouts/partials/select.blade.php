<select id="{{ $id }}" name="{{ isset($name) ? $name : 'item['.$id.']' }}" {{ isset($required) ? 'required' : '' }} class="form-control select2" >
    <option>@lang('crud.choose-a-option')</option>
    @foreach($list as $selectKey => $selectValue)
    <option value="{{ isset($key) ? $selectValue->{$key} : $selectKey }}" 
        {{ isset($selected) && (isset($key) ? $selectValue->{$key} : $selectKey) == $selected ? 'selected' : '' }} >
        {{ isset($value) ? $selectValue->{$value} : $selectValue }}
    </option>
    @endforeach
</select>
