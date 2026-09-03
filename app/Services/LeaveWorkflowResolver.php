<?php
// app/Services/LeaveWorkflowResolver.php

namespace App\Services;

use App\Models\LeaveWorkflow;
use App\Models\SiteLeaveWorkflowSetting;
use Illuminate\Support\Collection;

class LeaveWorkflowResolver
{
    /**
     * Résoudre un workflow pour un site donné.
     * - Si le workflow est local (site_id non null) → retourné tel quel.
     * - Si le workflow est global (site_id null) → on cherche un override
     *   pour le site et on applique les valeurs surchargées.
     */
    public function resolve(LeaveWorkflow $workflow, int $siteId): \stdClass
    {
        // Si le workflow n'est pas global, on le retourne en local
        if (!$workflow->isGlobal()) {
            return $this->buildLocalObject($workflow);
        }

        // Workflow global : chercher un override pour le site
        $override = SiteLeaveWorkflowSetting::where('site_id', $siteId)
            ->where('leave_workflow_id', $workflow->id)
            ->first();

        return $this->buildResolvedObject($workflow, $override);
    }

    /**
     * Résoudre une collection de workflows pour un site donné.
     */
    public function resolveCollection($workflows, int $siteId): Collection
    {
        return collect($workflows)->map(function ($workflow) use ($siteId) {
            return $this->resolve($workflow, $siteId);
        });
    }

    /**
     * Construire l'objet pour un workflow local (non global).
     */
    private function buildLocalObject(LeaveWorkflow $workflow): \stdClass
    {
        $obj = new \stdClass();
        // Copier tous les attributs du modèle
        foreach ($workflow->getAttributes() as $key => $value) {
            $obj->{$key} = $value;
        }
        $obj->is_global = false;
        $obj->site_name = $workflow->site->Nom ?? '—';
        $obj->is_customizable = false;
        $obj->is_overridden = false;
        $obj->override_id = null;
        $obj->leave_type_name = $workflow->leaveType->name ?? null;

        // Décoder les steps en tableau si c'est une chaîne JSON
        if (is_string($obj->steps)) {
            $obj->steps = json_decode($obj->steps, true);
        }

        return $obj;
    }

    /**
     * Construire l'objet résolu pour un workflow global avec éventuel override.
     */
    private function buildResolvedObject(LeaveWorkflow $workflow, ?SiteLeaveWorkflowSetting $override): \stdClass
    {
        $obj = new \stdClass();
        $obj->id = $workflow->id;
        $obj->site_id = $workflow->site_id; // null pour global
        $obj->is_global = true;
        $obj->site_name = '—'; // pas de site spécifique
        $obj->is_customizable = $workflow->is_customizable;
        $obj->is_overridden = !is_null($override);
        $obj->override_id = $override?->id;
        $obj->deleted_at = $workflow->deleted_at;

        // Champs résolus (override ou global)
        $obj->name = $override->name ?? $workflow->name;
        $obj->description = $override->description ?? $workflow->description;
        $obj->steps = $override->steps ?? $workflow->steps;
        $obj->is_default = $override->is_default ?? $workflow->is_default;
        $obj->is_active = $override->is_active ?? $workflow->is_active;
        $obj->leave_type_id = $override->leave_type_id ?? $workflow->leave_type_id;

        // Récupérer le nom du type de congé
        if ($obj->leave_type_id) {
            $leaveType = \App\Models\LeaveType::find($obj->leave_type_id);
            $obj->leave_type_name = $leaveType?->name ?? null;
        } else {
            $obj->leave_type_name = null;
        }

        // Décoder les steps en tableau
        if (is_string($obj->steps)) {
            $obj->steps = json_decode($obj->steps, true);
        }

        return $obj;
    }
}