<?php

namespace App\Services\Admin;

use App\Http\Traits\FileManagementTrait;
use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BookService
{
    use FileManagementTrait;

    public function getBooks($orderBy = 'sort_order', $order = 'asc')
    {
        return Book::orderBy($orderBy, $order)->latest();
    }
    public function getBook(string $encryptedId): Book|Collection
    {
        return Book::findOrFail(decrypt($encryptedId));
    }
    public function getDeletedBook(string $encryptedId): Book|Collection
    {
        return Book::onlyTrashed()->findOrFail(decrypt($encryptedId));
    }

    public function createBook(array $data, $file = null, $pdf = null): Book
    {
        return DB::transaction(function () use ($data, $file, $pdf) {
            if ($file) {
                $data['cover_image'] = $this->handleFileUpload($file, 'books');
            }
            if ($pdf) {
                $data['file'] = $this->handleFileUpload($pdf, 'books');
            }
            $data['created_by'] = admin()->id;
            $book = Book::create($data);
            return $book;
        });
    }

    public function updateBook(Book $book, array $data, $file = null, $pdf = null): Book
    {
        return DB::transaction(function () use ($book, $data, $file , $pdf) {
            if ($file) {
                $data['cover_image'] = $this->handleFileUpload($file, 'books');
                $this->fileDelete($book->cover_image);
            }
            if ($pdf) {
                $data['file'] = $this->handleFileUpload($pdf, 'books');
                $this->fileDelete($book->file);
            }
            $data['updated_by'] = admin()->id;
            $book->update($data);
            return $book;
        });
    }



    public function delete(Book $book): void
    {
        $book->update(['deleted_by' => admin()->id]);
        $book->delete();
    }

    public function restore(string $encryptedId): void
    {
        $book = $this->getDeletedBook($encryptedId);
        $book->update(['updated_by' => admin()->id]);
        $book->restore();
    }

    public function permanentDelete(string $encryptedId): void
    {
        $book = $this->getDeletedBook($encryptedId);
        $book->forceDelete();
    }

    public function toggleStatus(Book $book): void
    {
        $book->update([
            'status' => !$book->status,
            'updated_by' => admin()->id
        ]);
    }
}
