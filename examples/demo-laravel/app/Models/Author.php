<?php

namespace App\Models;

use App\Models\Traits\UserAccessTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okami101\LaravelAdmin\Traits\RequestMediaTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

/**
 * App\Author
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $backlinks
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $books
 * @property-read int|null $books_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author query()
 * @mixin \Eloquent
 * @property-read mixed $translations
 * @property-read \Illuminate\Database\Eloquent\Collection|Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author hasUser(\App\Models\User $user)
 */
class Author extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use RequestMediaTrait;
    use UserAccessTrait;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'backlinks',
    ];

    protected $casts = [
        'backlinks' => 'array',
    ];

    public $translatable = ['description'];

    protected function getLocale(): string
    {
        return request()->header('locale') ?: app()->getLocale();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('small')
            ->width(120)
            ->height(80)
            ->nonOptimized();

        $this->addMediaConversion('medium')
            ->width(400)
            ->height(300)
            ->nonOptimized();

        $this->addMediaConversion('large')
            ->width(800)
            ->height(600)
            ->nonOptimized();
    }
}
