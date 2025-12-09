<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'site_tagline',
        'site_description',
        'logo_url',
        'favicon_url',
        'contact_email',
        'contact_phone',
        'address',
        'hero_cta_label',
        'hero_cta_url',
        'about_title',
        'about_subtitle',
        'about_mission',
        'about_vision',
        'about_values',
        'impact_beneficiaries',
        'impact_programs',
        'impact_regions',
        'impact_volunteers',
    ];
}
