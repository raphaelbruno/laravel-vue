<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['session', 'content', 'user_id'];
    protected $appends = ['content_html_line'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getContentHtmlLineAttribute(){
        return nl2br($this->content);
    }
}
