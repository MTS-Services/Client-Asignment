<?php

namespace App\Services\Admin\IssuesManagement;

use App\Models\BookIssues;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BookIssuesService
{

    public function getBookIssuess($orderBy = 'sort_order', $order = 'asc')
    {
        return BookIssues::orderBy($orderBy, $order)->latest();
    }
    public function getBookIssues(string $encryptedId, string $type = 'encrypted'): BookIssues|Collection
    {
        if($type == 'issue_code') {
            return BookIssues::where('issue_code', $encryptedId)->first();
        }
        return BookIssues::findOrFail(decrypt($encryptedId));
    }
    public function getDeletedBookIssues(string $encryptedId): BookIssues|Collection
    {
        return BookIssues::onlyTrashed()->findOrFail(decrypt($encryptedId));
    }

    public function createBookIssues(array $data): BookIssues
    {
        return DB::transaction(function () use ($data) {
            $bookIssues = BookIssues::create($data);
            return $bookIssues;
        });
    }

    public function updateBookIssues(BookIssues $bookIssues, array $data): BookIssues
    {
        return DB::transaction(function () use ($bookIssues, $data) {
            $bookIssues->update($data);
            return $bookIssues;
        });
    }

    public function delete(BookIssues $bookIssues): void
    {
        $bookIssues->update([
            'deleter_id' => admin()->id,
            'deleter_type' => get_class(admin())
        ]);
        $bookIssues->delete();
    }

    public function restore(string $encryptedId): void
    {
        $bookIssues = $this->getDeletedBookIssues($encryptedId);
        $bookIssues->update([
            'updater_id' => admin()->id,
            'updater_type' => get_class(admin())
        ]);
        $bookIssues->restore();
    }

    public function permanentDelete(string $encryptedId): void
    {
        $bookIssues = $this->getDeletedBookIssues($encryptedId);
        $bookIssues->forceDelete();
    }

    public function toggleStatus(BookIssues $bookIssues): void
    {
        $bookIssues->update([
            'status' => !$bookIssues->status,
            'updater_id' => admin()->id,
            'updater_type' => get_class(admin())
        ]);
    }

    public function updateReturnBookIssue(string $encryptedId, array $data): BookIssues
    {
        $bookIssue = $this->getBookIssues($encryptedId);

        $data['status'] = BookIssues::STATUS_RETURNED;
        $returnDate = \Carbon\Carbon::parse($data['return_date']);
        $data['return_date'] = $returnDate;
        $data['fine_amount'] = $data['fine_amount'] ?? 0;
        $data['fine_status'] = $data['fine_status'] ?? null;
        $data['updater_id'] = admin()->id;
        $data['updater_type'] = get_class(admin());

        $bookIssue->update($data);

        return $bookIssue;
    }
    public function updateBookLost(string $encryptedId, array $data): BookIssues
    {
        $bookIssue = $this->getBookIssues($encryptedId);

        $data['status'] = BookIssues::STATUS_LOST;
        $data['fine_amount'] = $data['fine_amount'] ?? 0;
        $data['fine_status'] = $data['fine_status'] ?? null;
        $data['updater_id'] = admin()->id;
        $data['updater_type'] = get_class(admin());

        $bookIssue->update($data);

        return $bookIssue;
    }

    public function updateFineStatus(string $encryptedId, string $fineStatus)
    {
        $bookIssue = $this->getBookIssues($encryptedId);
        $bookIssue->update([
            'fine_status' => BookIssues::FINE_PAID,
            'updater_id' => admin()->id,
            'updater_type' => get_class(admin())
        ]);
    }
}
