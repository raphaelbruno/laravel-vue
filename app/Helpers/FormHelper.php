<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FormHelper 
{
    
    public static function input($config)
    {
        $ref = $config['ref'];
        $required = (bool) isset($config['required']) ? $config['required'] : false;
        $icon = isset($config['icon']) ? $config['icon'] : false;
        $label = trans($config['label']);
        $value = isset($config['value']) ? $config['value'] : '';
        $class = isset($config['class']) ? ' '.$config['class'] : '';
        $attributes = isset($config['attributes']) ? $config['attributes'] : [];

        $attributesString = '';
        foreach($attributes as $k => $v)
            $attributesString .= ' '.$k.'="'.$v.'"';

        return '
            <div class="form-group">
                <label for="'.$ref.'">'.$label.' '.($required ? '*' : '').'</label>
                <div class="input-group">
                    '.($icon ?
                    '<div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-'.$icon.'"></i></span>
                    </div>' : ''
                    ).'
                    <input type="text" id="'.$ref.'" name="item['.$ref.']" '.($required ? 'required' : '').' class="form-control'.$class.'" value="'.$value.'" '.$attributesString.'>
                    <div class="invalid-feedback">'.trans('crud.invalid-field', [$label]).'</div>
                </div>
            </div>
        ';
    }

    public static function textarea($config)
    {
        $ref = $config['ref'];
        $required = (bool) isset($config['required']) ? $config['required'] : false;
        $icon = isset($config['icon']) ? $config['icon'] : false;
        $label = trans($config['label']);
        $value = isset($config['value']) ? $config['value'] : '';
        $class = isset($config['class']) ? ' '.$config['class'] : '';
        $rows = isset($config['rows']) ? $config['rows'] : 3;
        $attributes = isset($config['attributes']) ? $config['attributes'] : [];

        $attributesString = '';
        foreach($attributes as $k => $v)
            $attributesString .= ' '.$k.'="'.$v.'"';

        return '
            <div class="form-group">
                <label for="'.$ref.'">'.$label.' '.($required ? '*' : '').'</label>
                <div class="input-group">
                    '.($icon ?
                    '<div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-'.$icon.'"></i></span>
                    </div>' : ''
                    ).'
                    <textarea id="'.$ref.'" rows="'.$rows.'" name="item['.$ref.']" '.($required ? 'required' : '').' class="form-control'.$class.'" '.$attributesString.'>'.$value.'</textarea>
                    <div class="invalid-feedback">'.trans('crud.invalid-field', [$label]).'</div>
                </div>
            </div>
        ';
    }

    public static function select($config)
    {
        $ref = $config['ref'];
        $required = (bool) isset($config['required']) ? $config['required'] : false;
        $icon = isset($config['icon']) ? $config['icon'] : false;
        $label = trans($config['label']);
        $chooseOption = trans(isset($config['chooseOption']) ? $config['chooseOption'] : 'crud.choose-a-option');
        $options = isset($config['options']) ? $config['options'] : [];
        $class = isset($config['class']) ? ' '.$config['class'] : '';
        $selected = isset($config['value']) ? ' '.$config['value'] : '';
        
        $optionsString = '<option value="">'.$chooseOption.'</option>';
        foreach($options as $k => $v)
            $optionsString .= '<option value="'.$k.'" '.($selected == $k ? 'selected' : '').'>'.$v.'</option>';

        return '
            <div class="form-group">
                <label for="'.$ref.'">'.$label.' '.($required ? '*' : '').'</label>
                <div class="input-group">
                    '.($icon ?
                    '<div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-'.$icon.'"></i></span>
                    </div>' : ''
                    ).'
                    <select id="'.$ref.'" class="form-control'.$class.'" '.($required ? 'required' : '').'>
                        '.$optionsString.'
                    </select>
                    <div class="invalid-feedback">'.trans('crud.invalid-field', [$label]).'</div>
                </div>
            </div>
        ';
    }

    public static function select2($config)
    {
        $class = 'select2' . (isset($config['class']) ? ' '.$config['class'] : '');
        $config['class'] = $class;
        return self::select($config);
    }

    public static function switch($config)
    {
        $ref = $config['ref'];
        $required = (bool) isset($config['required']) ? $config['required'] : false;
        $label = trans($config['label']);
        $class = isset($config['class']) ? ' '.$config['class'] : '';
        $checked = isset($config['checked']) ? (bool) $config['checked'] : false;
        
        return '
            <div class="form-group">
                <label for="'.$ref.'">'.$label.' '.($required ? '*' : '').'</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" id="'.$ref.'" name="item['.$ref.']" '.($required ? 'required' : '').' class="custom-control-input'.$class.'" '. ($checked ? 'checked' : '') .'>
                    <label class="custom-control-label" for="default"></label>
                    <div class="invalid-feedback">'.trans('crud.invalid-field', [$label]).'</div>
                </div>
            </div>
        ';
    }

    public static function file($config)
    {
        $ref = $config['ref'];
        $required = (bool) isset($config['required']) ? $config['required'] : false;
        $icon = isset($config['icon']) ? $config['icon'] : false;
        $label = trans($config['label']);
        $value = isset($config['value']) ? $config['value'] : '';
        $class = isset($config['class']) ? ' '.$config['class'] : '';
        $image = isset($config['item']) && isset($config['item']->{$ref}) ?
                    (empty(parse_url($config['item']->{$ref})['scheme'])
                        ? asset(Storage::url($config['item']->{$ref}))
                        : $config['item']->{$ref})
                    : null;

        return '
            <div class="form-group">
                <label for="'.$ref.'">'.$label.' '.($required ? '*' : '').'</label>
                <div class="input-group">
                    '.($icon ?
                    '<div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-'.$icon.'"></i></span>
                    </div>' : ''
                    ).'
                    <input type="text" class="form-control selected-file'.$class.'" readonly placeholder="'.trans('crud.choose-file').'">
                    <div class="input-group-append">
                        <label class="btn btn-primary m-0" for="'.$ref.'">
                            <input id="'.$ref.'" name="item['.$ref.']" type="file" '.($required ? 'required' : '').' class="d-none file'.$class.'">
                            <i class="fas fa-search mr-1"></i>
                        </label>
                    </div>
                    <div class="invalid-feedback">'.trans('crud.invalid-field', [$label]).'</div>
                </div>
            </div>

            '.($image ? '<img class="img-thumbnail w-100" src="{{ $image }}" alt="">' : '' ).'
        ';
    }
}