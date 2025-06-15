<?php

namespace App\Services\Admin;

use App\Http\Traits\FileManagementTrait;
use App\Models\Rack;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RackService
{
    use FileManagementTrait;

    public function getRacks($orderBy = 'sort_order', $order = 'asc')
    {
        return Rack::orderBy($orderBy, $order)->latest();
    }
    public function getRack(string $encryptedId): Rack|Collection
    {
        return Rack::findOrFail(decrypt($encryptedId));
    }
    public function getDeletedRack(string $encryptedId): Rack|Collection
    {
        return Rack::onlyTrashed()->findOrFail(decrypt($encryptedId));
    }

    public function createRack(array $data, $file = null): Rack
    {
        return DB::transaction(function () use ($data, $file) {
           
            $data['created_by'] = admin()->id;
            $rack = Rack::create($data);
            return $rack;
        });
    }

    public function updateRack(Rack $rack, array $data, $file = null): Rack
    {
        return DB::transaction(function () use ($rack, $data, $file) {
           
            $data['updated_by'] = admin()->id;
            $rack->update($data);
            return $rack;
        });
    }

    public function delete(Rack $rack): void
    {
        $rack->update(['deleted_by' => admin()->id]);
        $rack->delete();
    }

    public function restore(string $encryptedId): void
    {
        $rack = $this->getDeletedRack($encryptedId);
        $rack->update(['updated_by' => admin()->id]);
        $rack->restore();
    }

    public function permanentDelete(string $encryptedId): void
    {
        $rack = $this->getDeletedRack($encryptedId);
        $rack->forceDelete();
    }

    public function toggleStatus(Rack $rack): void
    {
        $rack->update([
            'status' => !$rack->status,
            'updated_by' => admin()->id
        ]);
    }
}
