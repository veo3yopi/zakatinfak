<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'site_title',
        'site_tagline',
        'site_description',
        'logo_url',
        'favicon_url',
        'about_hero_url',
        'contact_email',
        'contact_phone',
        'address',
        'hero_cta_label',
        'hero_cta_url',
        'about_title',
        'about_subtitle',
        'about_summary',
        'about_mission',
        'about_vision',
        'about_values',
        'about_principles',
        'about_goals',
        'impact_beneficiaries',
        'impact_programs',
        'impact_regions',
        'impact_volunteers',
        'program_banner_url',
        'program_hero_show_title',
        'program_hero_show_summary',
        'program_hero_show_cta',
        'program_show_categories',
        'footer_about_text',
        'footer_links',
        'footer_social_links',
        'footer_address',
        'footer_email',
        'footer_phone',
        'thankyou_title',
        'thankyou_message',
        'payment_channels',
    ];

    protected $casts = [
        'program_hero_show_title' => 'boolean',
        'program_hero_show_summary' => 'boolean',
        'program_hero_show_cta' => 'boolean',
        'program_show_categories' => 'boolean',
        'footer_links' => 'array',
        'footer_social_links' => 'array',
        'footer_address' => 'string',
        'footer_email' => 'string',
        'footer_phone' => 'string',
        'thankyou_title' => 'string',
        'thankyou_message' => 'string',
        'payment_channels' => 'array',
    ];
}
