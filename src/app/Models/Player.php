<?php

namespace App\Models;

use Czim\Paperclip\Contracts\AttachableInterface;
use Czim\Paperclip\Model\PaperclipTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model implements AttachableInterface
{
    use PaperclipTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'team_id',
        'first_name',
        'last_name',
        'image'
    ];

    /**
     * @var array
     */
    protected $appends = [
        'full_name',
        'image_url'
    ];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->hasAttachedFile('image', [
            'styles' => [
                'thumbnail' => [
                    'dimensions'      => '100x100',
                    'auto-orient'     => true,
                    'convert_options' => [
                        'quality' => 100
                    ]
                ]
            ]
        ]);

        parent::__construct($attributes);
    }

    /** Accessors */

    /**
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        if (!$this->image_file_name) {
            return '';
        }

        return asset($this->image->url('original'));
    }

    /**
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /** Relationships */

    /**
     * @return BelongsTo
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
