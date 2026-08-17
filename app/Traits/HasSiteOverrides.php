<?php

namespace App\Traits;

trait HasSiteOverrides
{
    /**
     * Récupère le setting pour un site donné
     */
    public function getSettingForSite(int $siteId)
    {
        return $this->siteSettings()->where('site_id', $siteId)->first();
    }

    /**
     * Vérifie si le site a un override
     */
    public function hasOverrideForSite(int $siteId): bool
    {
        return $this->getSettingForSite($siteId) !== null;
    }

    /**
     * Récupère la valeur résolue pour un champ donné
     */
    public function getResolvedValue(string $field, int $siteId, $default = null)
    {
        $override = $this->getSettingForSite($siteId);
        return $override->{$field} ?? $this->{$field} ?? $default;
    }

    /**
     * Récupère tous les champs résolus pour un site
     */
    public function getResolvedAttributes(int $siteId): array
    {
        $override = $this->getSettingForSite($siteId);
        $fields = $this->getOverrideableFields();
        
        $resolved = [];
        foreach ($fields as $field) {
            $resolved[$field] = $override->{$field} ?? $this->{$field};
        }
        
        return $resolved;
    }

    /**
     * Définit les champs qui peuvent être surchargés
     */
    protected function getOverrideableFields(): array
    {
        return [
            'name', 'start_date', 'end_date', 'submission_deadline',
            'allow_rollover', 'max_rollover_days', 'rollover_expiry_date',
            'is_default', 'status', 'is_active'
        ];
    }
}