<?php
// app/Models/EntrepriseSiege.php

namespace App\Models;

use App\Scopes\SiegeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Loggable;

class EntrepriseSiege extends Model
{
    use Loggable;
    public string $logLabelField = 'Nom';

    protected $table = 'Entreprises_sieges';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    
    protected $fillable = [
        'Nom',
        'Nom_Lieu_Ville',
        'Pays',
        'Actived',
    ];
    
    protected $casts = [
        'Actived' => 'boolean',
        'CreatedAt' => 'datetime'
    ];
    
    public function entreprises(): HasMany
    {
        return $this->hasMany(Entreprise::class, 'SiegeID', 'ID');
    }
    
    public function employes(): HasMany
    {
        return $this->hasMany(Employe::class, 'SiegeID', 'ID');
    }
    
    public function pointages(): HasMany
    {
        return $this->hasMany(Pointage::class, 'SiegeID', 'ID');
    }
    
    public function administrateurs(): HasMany
    {
        return $this->hasMany(Administration::class, 'SiegeID', 'ID');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SiegeScope());
    }

    public function sellers()
    {
        return $this->belongsToMany(
            Administration::class,
            'seller_sieges',
            'SiegeID',
            'SellerID',
            'ID',
            'ID'
        )->withPivot('CreatedAt');
    }
}