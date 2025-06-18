<?php

namespace App\Services\Admin;

use App\Http\Traits\FileManagementTrait;
use App\Models\Newspaper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class NewspaperService
{
    use FileManagementTrait;

    public function getNewspapers($orderBy = 'sort_order', $order = 'asc')
    {
        return Newspaper::orderBy($orderBy, $order)->latest();
    }
    public function getNewspaper(string $encryptedId, string $type = 'encrypted'): Newspaper|Collection
    {
        if ($type == 'slug') {
            return Newspaper::where('slug', $encryptedId)->first();
        }
        return Newspaper::findOrFail(decrypt($encryptedId));
    }
    public function getDeletedNewspaper(string $encryptedId): Newspaper|Collection
    {
        return Newspaper::onlyTrashed()->findOrFail(decrypt($encryptedId));
    }

    public function createNewspaper(array $data, $file = null): Newspaper
    {
        return DB::transaction(function () use ($data, $file) {
            if ($file) {
                $data['cover_image'] = $this->handleFileUpload($file, 'newspapers');
            }
            $data['created_by'] = admin()->id;
            $newspaper = Newspaper::create($data);
            return $newspaper;
        });
    }

    public function updateNewspaper(Newspaper $newspaper, array $data, $file = null): Newspaper
    {
        return DB::transaction(function () use ($newspaper, $data, $file) {
            if ($file) {
                $data['cover_image'] = $this->handleFileUpload($file, 'newspapers');
                $this->fileDelete($newspaper->image);
            }
            $data['updated_by'] = admin()->id;
            $newspaper->update($data);
            return $newspaper;
        });
    }



    public function delete(Newspaper $newspaper): void
    {
        $newspaper->update(['deleted_by' => admin()->id]);
        $newspaper->delete();
    }

    public function restore(string $encryptedId): void
    {
        $newspaper = $this->getDeletedNewspaper($encryptedId);
        $newspaper->update(['updated_by' => admin()->id]);
        $newspaper->restore();
    }

    public function permanentDelete(string $encryptedId): void
    {
        $newspaper = $this->getDeletedNewspaper($encryptedId);
        $newspaper->forceDelete();
    }

    public function toggleStatus(Newspaper $newspaper): void
    {
        $newspaper->update([
            'status' => !$newspaper->status,
            'updated_by' => admin()->id
        ]);
    }
}
