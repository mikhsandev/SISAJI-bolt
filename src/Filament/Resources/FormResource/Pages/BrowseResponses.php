<?php

namespace LaraZeus\Bolt\Filament\Resources\FormResource\Pages;

use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use LaraZeus\Bolt\BoltPlugin;
use LaraZeus\Bolt\Filament\Actions\SetResponseStatus;
use LaraZeus\Bolt\Filament\Resources\FormResource;
use LaraZeus\Bolt\Models\Form;

/**
 * @property Form $record.
 */
class BrowseResponses extends ManageRelatedRecords
{
    protected static string $resource = FormResource::class;

    protected static string $relationship = 'responses';

    protected static string $view = 'zeus::filament.resources.response-resource.pages.browse-responses';

    protected static ?string $navigationIcon = 'heroicon-o-eye';

    public function table(Table $table): Table
    {
        return $table
            ->paginated([1])
            ->query(BoltPlugin::getModel('Response')::query()->where('form_id', $this->record->id))
            ->columns([
                ViewColumn::make('response')
                    ->label(__('Browse Entries'))
                    ->view('zeus::filament.resources.response-resource.pages.browse-entry'),
            ])
            ->actions([
                SetResponseStatus::make(),
            ], position: ActionsPosition::AfterContent)
            ->filters([
                SelectFilter::make('status')
                    ->options(BoltPlugin::getModel('FormsStatus')::query()->pluck('label', 'key'))
                    ->label(__('Status')),
            ]);
    }

    public static function getNavigationLabel(): string
    {
        return __('Browse Entries');
    }

    public function getTitle(): string
    {
        return __('Browse Entries');
    }

    public function getTableRecords(): Collection | Paginator | CursorPaginator
    {
        $formId = $this->record->id;

        if ($translatableContentDriver = $this->makeFilamentTranslatableContentDriver()) {
            $setRecordLocales = function (Collection | Paginator | CursorPaginator $records) use ($translatableContentDriver): Collection | Paginator | CursorPaginator {
                $records->transform(fn (Model $record) => $translatableContentDriver->setRecordLocale($record));

                return $records;
            };
        } else {
            $setRecordLocales = fn (Collection | Paginator | CursorPaginator $records): Collection | Paginator | CursorPaginator => $records;
        }

        if ($this->cachedTableRecords) {
            return $setRecordLocales($this->cachedTableRecords);
        }

        $query = $this->getFilteredSortedTableQuery();

        // If role is not 'Admin Super', and not 'Admin': filter by user ID
        if (! auth()->user()->hasRole(['Admin Super', 'Admin'])) {
            $query->where('user_id', auth()->id());
        }

        if (
            (! $this->getTable()->isPaginated()) ||
            ($this->isTableReordering() && (! $this->getTable()->isPaginatedWhileReordering()))
        ) {
            return $setRecordLocales($this->cachedTableRecords = $this->hydratePivotRelationForTableRecords($query->get()));
        }

        return $setRecordLocales($this->cachedTableRecords = $this->hydratePivotRelationForTableRecords($this->paginateTableQuery($query)));
    }
}
