<?php
namespace App\Library\Builders;

use Illuminate\Support\HtmlString;

class FormBuilder
{

    /**
     * Usage
    {{ Form::input([
        'ref' => 'name',
        'label' => 'translate.name',
        'required' => true,
        'icon' => 'user',
        'value' => !empty(old('item.name')) ? old('item.name') : ( isset($item) ? $item->name : '' ),
    ]) }}
     */
    public function input($config = [])
    {
        $type = isset($config['type']) ? $config['type'] : 'text';
        $ref = isset($config['ref']) ? $config['ref'] : '';
        $id = isset($config['id']) ? $config['id'] : $ref;
        $name = isset($config['name']) ? $config['name'] : "item[{$ref}]";
        $required = (bool) isset($config['required']) ? $config['required'] : false;
        $icon = isset($config['icon']) ? $config['icon'] : false;
        $label = isset($config['label']) ? trans($config['label']) : '';
        $value = isset($config['value']) ? $config['value'] : '';
        $class = isset($config['class']) ? ' '.$config['class'] : '';
        $attributes = isset($config['attributes']) ? $config['attributes'] : [];

        $attributesString = '';
        foreach($attributes as $k => $v)
            $attributesString .= ' '.$k.'="'.$v.'"';

        return new HtmlString('
            <div class="form-group">
                <label for="'.$id.'">'.$label.' '.($required ? '*' : '').'</label>
                <div class="input-group">
                    '.($icon ?
                    '<div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-'.$icon.'"></i></span>
                    </div>' : ''
                    ).'
                    <input type="'.$type.'" id="'.$id.'" name="'.$name.'" '.($required ? 'required' : '').' class="form-control'.$class.'" value="'.$value.'" '.$attributesString.'>
                    <div class="invalid-feedback">'.trans('crud.invalid-field', [$label]).'</div>
                </div>
            </div>
        ');
    }

    /**
     * Usage
    {{ Form::password([
        'ref' => 'password',
        'label' => 'translate.password',
        'required' => true,
        'icon' => 'key',
        'value' => !empty(old('item.password')) ? old('item.password') : ( isset($item) ? $item->password : '' ),
    ]) }}
     */
    public function password($config)
    {
        $config['type'] = 'password';
        return self::input($config);;
    }
    
    /**
     * Usage
    {{ Form::textarea([
        'ref' => 'observation',
        'label' => 'translate.observation',
        'icon' => 'align-left',
        'value' => !empty(old('item.observation')) ? old('item.observation') : ( isset($item) ? $item->observation : '' ),
    ]) }}
     */
    public function textarea($config = [])
    {
        $ref = isset($config['ref']) ? $config['ref'] : '';
        $id = isset($config['id']) ? $config['id'] : $ref;
        $name = isset($config['name']) ? $config['name'] : "item[{$ref}]";
        $required = (bool) isset($config['required']) ? $config['required'] : false;
        $icon = isset($config['icon']) ? $config['icon'] : false;
        $label = isset($config['label']) ? trans($config['label']) : '';
        $value = isset($config['value']) ? $config['value'] : '';
        $class = isset($config['class']) ? ' '.$config['class'] : '';
        $rows = isset($config['rows']) ? $config['rows'] : 3;
        $attributes = isset($config['attributes']) ? $config['attributes'] : [];

        $attributesString = '';
        foreach($attributes as $k => $v)
            $attributesString .= ' '.$k.'="'.$v.'"';

        return new HtmlString('
            <div class="form-group">
                <label for="'.$id.'">'.$label.' '.($required ? '*' : '').'</label>
                <div class="input-group">
                    '.($icon ?
                    '<div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-'.$icon.'"></i></span>
                    </div>' : ''
                    ).'
                    <textarea id="'.$id.'" rows="'.$rows.'" name="'.$name.'" '.($required ? 'required' : '').' class="form-control'.$class.'" '.$attributesString.'>'.$value.'</textarea>
                    <div class="invalid-feedback">'.trans('crud.invalid-field', [$label]).'</div>
                </div>
            </div>
        ');
    }

    /**
     * Usage
    {{ Form::select([
        'ref' => 'name',
        'label' => 'translate.name',
        'required' => true,
        'icon' => 'user',
        'options' => [1 => 'Fulano', 2 => 'Sicrano'],
        'value' => !empty(old('item.name')) ? old('item.name') : ( isset($item) ? $item->name : '' ),
    ]) }}
     */
    public function select($config = [])
    {
        $ref = isset($config['ref']) ? $config['ref'] : '';
        $id = isset($config['id']) ? $config['id'] : $ref;
        $name = isset($config['name']) ? $config['name'] : "item[{$ref}]";
        $required = (bool) isset($config['required']) ? $config['required'] : false;
        $icon = isset($config['icon']) ? $config['icon'] : false;
        $label = isset($config['label']) ? trans($config['label']) : '';
        $chooseOption = trans(isset($config['chooseOption']) ? $config['chooseOption'] : 'crud.choose-a-option');
        $options = isset($config['options']) ? $config['options'] : [];
        $class = isset($config['class']) ? ' '.$config['class'] : '';
        $selected = isset($config['value']) ? ' '.$config['value'] : '';

        $optionsString = '<option value="">'.$chooseOption.'</option>';
        foreach($options as $k => $v)
            $optionsString .= '<option value="'.$k.'" '.($selected == $k ? 'selected' : '').'>'.$v.'</option>';

        return new HtmlString('
            <div class="form-group">
                <label for="'.$id.'">'.$label.' '.($required ? '*' : '').'</label>
                <div class="input-group">
                    '.($icon ?
                    '<div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-'.$icon.'"></i></span>
                    </div>' : ''
                    ).'
                    <select id="'.$id.'" name="'.$name.'" class="form-control'.$class.'" '.($required ? 'required' : '').'>
                        '.$optionsString.'
                    </select>
                    <div class="invalid-feedback">'.trans('crud.invalid-field', [$label]).'</div>
                </div>
            </div>
        ');
    }

    /**
     * Usage
    {{ Form::select2([
        'ref' => 'name',
        'label' => 'translate.name',
        'required' => true,
        'icon' => 'user',
        'options' => [1 => 'Fulano', 2 => 'Sicrano'],
        'value' => !empty(old('item.name')) ? old('item.name') : ( isset($item) ? $item->name : '' ),
    ]) }}
     */
    public function select2($config = [])
    {
        $config['class'] = 'select2' . (isset($config['class']) ? ' '.$config['class'] : '');
        return self::select($config);
    }

    /**
     * Usage
    {{ Form::switch([
        'ref' => 'is_true',
        'label' => 'translate.is_true',
        'checked' => (bool) (!empty(old('item.is_true')) ? old('item.is_true') : ( isset($item) && isset($item->is_true) ? $item->is_true : false ) ),
    ]) }}
     */
    public function switch($config = [])
    {
        $ref = isset($config['ref']) ? $config['ref'] : '';
        $id = isset($config['id']) ? $config['id'] : $ref;
        $name = isset($config['name']) ? $config['name'] : "item[{$ref}]";
        $required = (bool) isset($config['required']) ? $config['required'] : false;
        $label = isset($config['label']) ? trans($config['label']) : '';
        $class = isset($config['class']) ? ' '.$config['class'] : '';
        $checked = isset($config['checked']) ? (bool) $config['checked'] : false;

        return new HtmlString('
            <div class="form-group">
                <label for="'.$id.'">'.$label.' '.($required ? '*' : '').'</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" id="'.$id.'" name="'.$name.'" '.($required ? 'required' : '').' class="custom-control-input'.$class.'" '. ($checked ? 'checked' : '') .'>
                    <label class="custom-control-label" for="'.$id.'"></label>
                    <div class="invalid-feedback">'.trans('crud.invalid-field', [$label]).'</div>
                </div>
            </div>
        ');
    }

    /**
     * Usage
    {{ Form::file([
        'ref' => 'image',
        'label' => 'translate.image',
        'icon' => 'image',
        'image' => !empty(old('item.image')) ? old('item.image') : ( isset($item) ? $item->image : '' ),
    ]) }}    
     */
    public function file($config = [])
    {
        $ref = isset($config['ref']) ? $config['ref'] : '';
        $id = isset($config['id']) ? $config['id'] : $ref;
        $name = isset($config['name']) ? $config['name'] : "item[{$ref}]";
        $required = (bool) isset($config['required']) ? $config['required'] : false;
        $icon = isset($config['icon']) ? $config['icon'] : false;
        $label = isset($config['label']) ? trans($config['label']) : '';
        $class = isset($config['class']) ? ' '.$config['class'] : '';
        $image = TemplateHelper::filePath($config['image']);
        
        return new HtmlString('
            <div class="form-group">
                <label for="'.$id.'">'.$label.' '.($required ? '*' : '').'</label>
                <div class="input-group">
                    '.($icon ?
                    '<div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-'.$icon.'"></i></span>
                    </div>' : ''
                    ).'
                    <input type="text" class="form-control selected-file'.$class.'" readonly placeholder="'.trans('crud.choose-file').'">
                    <div class="input-group-append">
                        <label class="btn btn-primary m-0" for="'.$id.'">
                            <input id="'.$id.'" name="'.$name.'" type="file" '.($required ? 'required' : '').' class="d-none file'.$class.'">
                            <i class="fas fa-search mr-1"></i>
                        </label>
                    </div>
                    <div class="invalid-feedback">'.trans('crud.invalid-field', [$label]).'</div>
                </div>
            </div>
            <div class="text-center">
            '.(isset($image) ? '<img class="img-thumbnail img-fluid" src="'.$image.'" alt="">' : '' ).'
            </div>
        ');
    }
}
