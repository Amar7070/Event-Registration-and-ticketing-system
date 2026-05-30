<?php

namespace App\Models;

use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Str;

class Event extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected static function newFactory(): EventFactory
    {
        return EventFactory::new();
    }

    protected $guarded = [];

    protected $appends = ['banner'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_start' => 'datetime',
        'registration_end' => 'datetime',
        'tags' => 'array',
        'faqs' => 'array',
        'is_featured' => 'boolean',
        'is_recurring' => 'boolean',
        'requires_approval' => 'boolean',
    ];


    // Boot method for slug generation (substitute for HasSlug)
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title) . '-' . Str::random(6);
            }
        });
    }

    // Relationships
    public function organizer() { return $this->belongsTo(User::class, 'organizer_id'); }
    public function category() { return $this->belongsTo(EventCategory::class); }
    public function venue() { return $this->belongsTo(Venue::class); }
    public function ticketTypes() { return $this->hasMany(TicketType::class)->orderBy('price', 'asc'); }
    public function tickets() { return $this->hasMany(Ticket::class); }
    public function sessions() { return $this->hasMany(EventSession::class); }
    public function speakers() { return $this->hasMany(Speaker::class); }
    public function sponsors() { return $this->hasMany(Sponsor::class); }
    public function waitlists() { return $this->hasMany(Waitlist::class); }
    public function copyrightReports() { return $this->hasMany(CopyrightReport::class); }
    public function promotions() { return $this->hasMany(EventPromotion::class); }
    public function activePromotion() { return $this->hasOne(EventPromotion::class)->where('status', 'approved')->where('end_date', '>', now()); }
    public function reviews() { return $this->hasMany(Review::class); }

    // Scopes
    public function scopePublished($q) { return $q->where('status', 'published'); }
    public function scopeUpcoming($q) { return $q->where('end_date', '>=', now()); }
    public function scopeFeatured($q) { return $q->where('is_featured', true); }

    // Accessors
    public function getAvailableCapacityAttribute() {
        return $this->total_capacity - $this->registered_count;
    }
    public function getIsFullAttribute() {
        return $this->registered_count >= $this->total_capacity;
    }
    public function getBannerAttribute() {
        $curatedBanners = [
            'Ecosystem Symphony & Green Horizon 2026' => 'https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?auto=format&fit=crop&q=80&w=600',
            'Global Eco-Tech Alliance Summit' => 'https://images.unsplash.com/photo-1497435334941-8c899ee9e8e9?auto=format&fit=crop&q=80&w=600',
            'Global Tech Summit 2026' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?auto=format&fit=crop&q=80&w=600',
            'AI Workshop: Practical LLMs' => 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?auto=format&fit=crop&q=80&w=600',
            'Creative Design Futures 2026' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&q=80&w=600',
            'Starlight Rhythm & Harmony Festival' => 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?auto=format&fit=crop&q=80&w=600',
            'Econ-Socio Innovation Forum' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&q=80&w=600',
            'Ultimate Fitness & Athletic Challenge' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&q=80&w=600',
        ];
        $fallback = $curatedBanners[$this->title] ?? 'https://loremflickr.com/600/400/' . ($this->category->slug ?? 'event') . '?lock=' . $this->id;
        return $this->getFirstMediaUrl('banners') ?: $fallback;
    }
    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(400)
              ->height(225)
              ->sharpen(10);
    }
}
