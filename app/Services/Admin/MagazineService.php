<?php

namespace App\Services\Admin;

use App\Http\Traits\FileManagementTrait;
use App\Models\Magazine;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MagazineService
{
    use FileManagementTrait;

    public function getMagazines($orderBy = 'sort_order', $order = 'asc')
    {
        return Magazine::orderBy($orderBy, $order)->latest();
    }
    public function getMagazine(string $encryptedId, string $type = 'encrypted'): Magazine|Collection
    {
        if ($type == 'slug') {
            return Magazine::where('slug', $encryptedId)->first();
        }
        return Magazine::findOrFail(decrypt($encryptedId));
    }
    public function getDeletedMagazine(string $encryptedId): Magazine|Collection
    {
        return Magazine::onlyTrashed()->findOrFail(decrypt($encryptedId));
    }

    public function createMagazine(array $data, $file = null): Magazine
    {
        return DB::transaction(function () use ($data, $file) {
            if ($file) {
                $data['cover_image'] = $this->handleFileUpload($file, 'magazines');
            }
            $data['created_by'] = admin()->id;
            $magazine = Magazine::create($data);
            return $magazine;
        });
    }

    public function updateMagazine(Magazine $magazine, array $data, $file = null): Magazine
    {
        return DB::transaction(function () use ($magazine, $data, $file) {
            if ($file) {
                $data['cover_image'] = $this->handleFileUpload($file, 'magazines');
                $this->fileDelete($magazine->image);
            }
            $data['updated_by'] = admin()->id;
            $magazine->update($data);
            return $magazine;
        });
    }



    public function delete(Magazine $magazine): void
    {
        $magazine->update(['deleted_by' => admin()->id]);
        $magazine->delete();
    }

    public function restore(string $encryptedId): void
    {
        $magazine = $this->getDeletedMagazine($encryptedId);
        $magazine->update(['updated_by' => admin()->id]);
        $magazine->restore();
    }

    public function permanentDelete(string $encryptedId): void
    {
        $magazine = $this->getDeletedMagazine($encryptedId);
        $magazine->forceDelete();
    }

    public function toggleStatus(Magazine $magazine): void
    {
        $magazine->update([
            'status' => !$magazine->status,
            'updated_by' => admin()->id
        ]);
    }
}
