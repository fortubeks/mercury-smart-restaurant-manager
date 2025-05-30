<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreIssueStoreItem extends Model
{
    protected $fillable = ['store_id', 'store_issue_id', 'store_item_id', 'qty'];
}
