<?php

namespace Modules\Qna\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QnaFile extends Model
{
    use HasFactory;

    protected $guarded = ['_token'];
}
