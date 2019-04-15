<?php

namespace App\Models;

use Czim\Paperclip\Contracts\AttachableInterface;
use Czim\Paperclip\Model\PaperclipTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model implements AttachableInterface
{
    use PaperclipTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'image'
    ];

    /**
     * @var array
     */
    protected $appends = [
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

    /** Relationships */

    /**
     * @return HasMany
     */
    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }
}
