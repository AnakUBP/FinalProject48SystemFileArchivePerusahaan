<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogObserver
{
    public function created(Model $model): void
    {
        $this->log('created', $model);
    }

    public function updated(Model $model): void
    {
        $this->log('updated', $model);
    }

    public function deleted(Model $model): void
    {
        $this->log('deleted', $model);
    }

    protected function log(string $action, Model $model): void
    {
        // Kamu bisa modifikasi deskripsi agar lebih deskriptif
        $description = ucfirst($action) . ' ' . class_basename($model) . ' dengan ID ' . $model->id;

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'loggable_id' => $model->id,
            'loggable_type' => get_class($model),
        ]);
    }
}
