<?php

namespace App\Services\Admin;

use App\Http\Traits\FileManagementTrait;
use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AuthorService
{
    use FileManagementTrait;

    public function getAuthors($orderBy = 'sort_order', $order = 'asc')
    {
        return Author::orderBy($orderBy, $order)->latest();
    }
    public function getAuthor(string $encryptedId): Author|Collection
    {
        return Author::findOrFail(decrypt($encryptedId));
    }
    public function getDeletedAuthor(string $encryptedId): Author|Collection
    {
        return Author::onlyTrashed()->findOrFail(decrypt($encryptedId));
    }

    public function createAuthor(array $data, $file = null): Author
    {
        return DB::transaction(function () use ($data, $file) {
            if ($file) {
                $data['image'] = $this->handleFileUpload($file, 'authors', $data['name']);
            }
            $data['created_by'] = admin()->id;
            $author = Author::create($data);
            return $author;
        });
    }
    public function updateAuthor(Author $author, array $data, $file = null): Author
    {
        return DB::transaction(function () use ($author, $data, $file) {
            if ($file) {
                $data['image'] = $this->handleFileUpload($file, 'authors', $data['name']);
                $this->fileDelete($author->image);
            }
            $data['updated_by'] = admin()->id;
            $author->update($data);
            return $author;
        });
    }
    public function delete(Author $author): void
    {
        $author->update(['deleted_by' => admin()->id]);
        $author->delete();
    }
    public function restore(string $encryptedId): void
    {
        $author = $this->getDeletedAuthor($encryptedId);
        $author->update(['updated_by' => admin()->id]);
        $author->restore();
    }
    public function permanentDelete(string $encryptedId): void
    {
        $author = $this->getDeletedAuthor($encryptedId);
        $author->forceDelete();
    }
    public function toggleStatus(Author $author): void
    {
        $author->update([
            'status' => !$author->status,
            'updated_by' => admin()->id
        ]);
    }

}
