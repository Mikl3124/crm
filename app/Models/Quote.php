<?php

namespace App\Models;

use App\Models\Project;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{

  protected $guarded = [];
  public $timestamps = true;

  public function project()
  {
    return $this->belongsTo(Project::class);
  }
}