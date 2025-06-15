<?php

namespace App\Services\Admin\PublishManagement;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PublisherService
{
    
    public function getPublishers($orderBy = 'sort_order', $order = 'asc')
    {
        return Publisher::orderBy($orderBy, $order)->latest();
    }
    public function getPublisher(string $encryptedId): Publisher|Collection
    {
        return Publisher::findOrFail(decrypt($encryptedId));
    }
    public function getDeletedPublisher(string $encryptedId): Publisher|Collection
    {
        return Publisher::onlyTrashed()->findOrFail(decrypt($encryptedId));
    }

    public function createPublisher(array $data): Publisher
    {
        return DB::transaction(function () use ($data) {
            
            $data['created_by'] = admin()->id;
            $publisher = Publisher::create($data);
            return $publisher;
        });
    }

    public function updatePublisher(Publisher $publisher, array $data): Publisher
    {
        return DB::transaction(function () use ($publisher, $data) {
            $data['updated_by'] = admin()->id;
            $publisher->update($data);
            return $publisher;
        });
    }

    public function delete(Publisher $publisher): void
    {
        $publisher->update(['deleted_by' => admin()->id]);
        $publisher->delete();
    }

    public function restore(string $encryptedId): void
    {
        $publisher = $this->getDeletedPublisher($encryptedId);
        $publisher->update(['updated_by' => admin()->id]);
        $publisher->restore();
    }

    public function permanentDelete(string $encryptedId): void
    {
        $publisher = $this->getDeletedPublisher($encryptedId);
        $publisher->forceDelete();
    }

    public function toggleStatus(Publisher $publisher): void
    {
        $publisher->update([
            'status' => !$publisher->status,
            'updated_by' => admin()->id
        ]);
    }
}

