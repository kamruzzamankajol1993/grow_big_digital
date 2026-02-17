<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteConfig extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // Branding
        'site_name',
        'logo',
        'mobile_version_logo',
        
        // UI/Buttons
        'quick_button_text',
        'book_appointment_button_text',
         'book_appointment_link',
        'icon',
        
        // Contact
        'email',
        'phone',
        'whatsapp_number',
        'address',
        
        // Footer
        'footer_short_description',

        // SEO
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'google_analytics_code',
    ];

    /**
     * Optional: Casting certain fields if needed.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}