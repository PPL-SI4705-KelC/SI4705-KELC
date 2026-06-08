<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    protected $fillable = [
        'post_id',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Human-readable file size (e.g. "2.3 MB").
     */
    public function fileSizeForHuman(): string
    {
        $bytes = $this->file_size;
        if ($bytes < 1024)       return $bytes . ' B';
        if ($bytes < 1048576)    return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }

    /**
     * Tailwind colour + icon identifier for the file type.
     * Returns an array: ['color' => 'text-red-500', 'label' => 'PDF']
     */
    public function typeInfo(): array
    {
        return match (true) {
            str_contains($this->mime_type, 'pdf')        => ['color' => 'text-red-500',    'bg' => 'bg-red-50',    'label' => 'PDF'],
            str_contains($this->mime_type, 'word')       => ['color' => 'text-blue-600',   'bg' => 'bg-blue-50',   'label' => 'DOC'],
            str_contains($this->mime_type, 'excel')      ,
            str_contains($this->mime_type, 'spreadsheet')=> ['color' => 'text-emerald-600','bg' => 'bg-emerald-50','label' => 'XLS'],
            str_contains($this->mime_type, 'presentation')=> ['color' => 'text-orange-500','bg' => 'bg-orange-50', 'label' => 'PPT'],
            str_contains($this->mime_type, 'text')       => ['color' => 'text-gray-600',   'bg' => 'bg-gray-100',  'label' => 'TXT'],
            default                                      => ['color' => 'text-gray-500',   'bg' => 'bg-gray-100',  'label' => 'FILE'],
        };
    }

    /**
     * Public URL to download the file.
     */
    public function url(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
