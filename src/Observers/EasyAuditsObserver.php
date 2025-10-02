<?php

namespace Jonasschen\LaravelEasyAudits\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Jonasschen\LaravelEasyAudits\Enums\AuditEventTypeEnum;
use Jonasschen\LaravelEasyAudits\Facades\EasyAuditsFacade;

final class EasyAuditsObserver
{
    public function created(Model $model): void
    {
        EasyAuditsFacade::store(
            $this->serializeData($model, AuditEventTypeEnum::INSERT),
        );
    }

    public function updated(Model $model): void
    {
        $modelAuditableAttributes = $this->getModelAuditableAttributes($model);
        if ($model->wasChanged($modelAuditableAttributes)) {
            EasyAuditsFacade::store(
                $this->serializeData($model, AuditEventTypeEnum::UPDATE),
            );
        }
    }

    public function deleted(Model $model): void
    {
        EasyAuditsFacade::store(
            $this->serializeData($model, AuditEventTypeEnum::DELETE),
        );
    }

    public function restored(Model $model): void
    {
        EasyAuditsFacade::store(
            $this->serializeData($model, AuditEventTypeEnum::RESTORE),
        );
    }

    public function forceDeleted(Model $model): void
    {
        EasyAuditsFacade::store(
            $this->serializeData($model, AuditEventTypeEnum::FORCE_DELETE),
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
