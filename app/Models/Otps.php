<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable(['user_id', 'phone', 'code', 'expires_at', 'used_at'])]
#[Hidden([])]
class Otps extends Model
{
    //
}
