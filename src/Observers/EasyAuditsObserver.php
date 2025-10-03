<?php

namespace Jonasschen\LaravelEasyAudits\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Jonasschen\LaravelEasyAudits\Enums\AuditEventTypeEnum;
use Jonasschen\LaravelEasyAudits\Facades\EasyAuditsFacade;

final class EasyAuditsObserver
{
    public function created(Model $model): void
    {
        if (!in_array('insert', $model->getEnabledTriggers())) {
            return;
        }

        EasyAuditsFacade::store(
            $this->serializeData($model, AuditEventTypeEnum::INSERT),
        );
    }

    public function updated(Model $model): void
    {
        if (!in_array('update', $model->getEnabledTriggers())) {
            return;
        }

        $modelAuditableAttributes = $this->getModelAuditableAttributes($model);
        if ($model->wasChanged($modelAuditableAttributes)) {
            EasyAuditsFacade::store(
                $this->serializeData($model, AuditEventTypeEnum::UPDATE),
            );
        }
    }

    public function deleted(Model $model): void
    {
        $eventType = $this->getDeleteEventType($model);
        if ($this->shouldSkipAudit($model, $eventType)) {
            return;
        }

        EasyAuditsFacade::store(
            $this->serializeData($model, $eventType),
        );
    }

    private function getDeleteEventType($model): AuditEventTypeEnum
    {
        if (!in_array(SoftDeletes::class, class_uses_recursive($model))) {
            return AuditEventTypeEnum::DELETE;
        }

        if (method_exists($model, 'isForceDeleting') && $model->isForceDeleting()) {
            return AuditEventTypeEnum::FORCE_DELETE;
        }

        return AuditEventTypeEnum::SOFT_DELETE;
    }

    private function shouldSkipAudit($model, AuditEventTypeEnum $eventType): bool
    {
        $triggerName = match ($eventType) {
            AuditEventTypeEnum::DELETE => 'delete',
            AuditEventTypeEnum::FORCE_DELETE => 'force_delete',
            AuditEventTypeEnum::SOFT_DELETE => 'soft_delete',
            default => strtolower($eventType->value),
        };

        return !in_array($triggerName, $model->getEnabledTriggers());
    }

    public function restored(Model $model): void
    {
        if (!in_array('restore', $model->getEnabledTriggers())) {
            return;
        }

        EasyAuditsFacade::store(
            $this->serializeData($model, AuditEventTypeEnum::RESTORE),
        );
    }

    private function getIp(): string
    {
        if (app()->runningInConsole()) {
            return 'console';
        }

        return request()->ip();
    }

    private function serializeData(Model $model, AuditEventTypeEnum $eventType): array
    {
        return [
            'user_id' => $this->getUserId(),
            'ip' => $this->getIp(),
            'event_type' => $eventType->value,
            'table' => $model->getTable(),
            'old_values' => $this->getAuditableFields($model, $model->getOriginal()),
            'new_values' => $this->getAuditableFields($model, $model->getAttributes()),
        ];
    }

    private function getAuditableFields(Model $model, array $changedAttributes): array
    {
        $auditableAttributes = $this->getModelAuditableAttributes($model);
        $allowedAttributes = Arr::only($changedAttributes, $auditableAttributes);

        $nonAuditableAttributes = $model->getNonAuditableAttributes();

        return Arr::except($allowedAttributes, $nonAuditableAttributes);
    }

    private function getModelAuditableAttributes(Model $model): array
    {
        $auditableAttributes = $model->getAuditableAttributes();
        if (in_array('*', $auditableAttributes)) {
            $auditableAttributes = array_keys($model->getAttributes());
        }

        return $auditableAttributes;
    }

    private function getUserId(): int
    {
        $guard = auth()->getDefaultDriver();
        $driver = auth($guard);

        if (!$driver->check()) {
            return 0;
        }

        return $driver->id();
    }
}
