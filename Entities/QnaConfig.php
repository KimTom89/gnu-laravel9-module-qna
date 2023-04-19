<?php

namespace Modules\Qna\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QnaConfig extends Model
{
    use HasFactory;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $guarded = ['_token'];
}
