<?php

namespace App\Filament\Resources\CareInvoices\Pages;

use App\Filament\Resources\CareInvoices\CareInvoiceResource;
use Filament\Resources\Pages\ListRecords;

class ListCareInvoices extends ListRecords
{
    protected static string $resource = CareInvoiceResource::class;
}
