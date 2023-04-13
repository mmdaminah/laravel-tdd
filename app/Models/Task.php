<?php

namespace App\Models;

use App\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    use RecordsActivity;
    protected $fillable = ['body', 'completed'];
    protected $touches = ['project'];
    protected $casts = ['completed' => 'boolean'];

    protected static $recordableEvents = ['created', 'deleted'];

    public function path()
    {
       return '/projects/'.$this->project->id.'/tasks/'.$this->id;
    }

    public function project()
    {
       return $this->belongsTo(Project::class);
    }

    public function complete()
    {
        $this->update(['completed' => true]);

        $this->recordActivity('completed_task');
    }

    public function inComplete()
    {
        $this->update(['completed' => false]);

        $this->recordActivity('incompleted_task');
    }


    public function activities()
    {
       return $this->morphMany(Activity::class, 'subject');
    }
}
