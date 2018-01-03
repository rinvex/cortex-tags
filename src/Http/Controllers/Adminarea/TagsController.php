<?php

declare(strict_types=1);

namespace Cortex\Tags\Http\Controllers\Adminarea;

use Rinvex\Tags\Contracts\TagContract;
use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Tags\DataTables\Adminarea\TagsDataTable;
use Cortex\Tags\Http\Requests\Adminarea\TagFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class TagsController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'tags';

    /**
     * Display a listing of the resource.
     *
     * @param \Cortex\Tags\DataTables\Adminarea\TagsDataTable $tagsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(TagsDataTable $tagsDataTable)
    {
        return $tagsDataTable->with([
            'id' => 'cortex-tags',
            'phrase' => trans('cortex/tags::common.tags'),
        ])->render('cortex/foundation::adminarea.pages.datatable');
    }

    /**
     * Get a listing of the resource logs.
     *
     * @param \Rinvex\Tags\Contracts\TagContract $tag
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logs(TagContract $tag)
    {
        return request()->ajax() && request()->wantsJson()
            ? app(LogsDataTable::class)->with(['resource' => $tag])->ajax()
            : intend(['url' => route('adminarea.tags.edit', ['tag' => $tag]).'#logs-tab']);
    }

    /**
     * Show the form for create/update of the given resource.
     *
     * @param \Rinvex\Tags\Contracts\TagContract $tag
     *
     * @return \Illuminate\Http\Response
     */
    public function form(TagContract $tag)
    {
        $groups = app('rinvex.tags.tag')->distinct()->get(['group'])->pluck('group', 'group')->toArray();
        $logs = app(LogsDataTable::class)->with(['id' => 'logs-table'])->html()->minifiedAjax(route('adminarea.tags.logs', ['tag' => $tag]));

        return view('cortex/tags::adminarea.pages.tag', compact('tag', 'groups', 'logs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Cortex\Tags\Http\Requests\Adminarea\TagFormRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TagFormRequest $request)
    {
        return $this->process($request, app('rinvex.tags.tag'));
    }

    /**
     * Update the given resource in storage.
     *
     * @param \Cortex\Tags\Http\Requests\Adminarea\TagFormRequest $request
     * @param \Rinvex\Tags\Contracts\TagContract                  $tag
     *
     * @return \Illuminate\Http\Response
     */
    public function update(TagFormRequest $request, TagContract $tag)
    {
        return $this->process($request, $tag);
    }

    /**
     * Process the form for store/update of the given resource.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \Rinvex\Tags\Contracts\TagContract      $tag
     *
     * @return \Illuminate\Http\Response
     */
    protected function process(FormRequest $request, TagContract $tag)
    {
        // Prepare required input fields
        $data = $request->validated();

        // Save tag
        $tag->fill($data)->save();

        return intend([
            'url' => route('adminarea.tags.index'),
            'with' => ['success' => trans('cortex/tags::messages.tag.saved', ['slug' => $tag->slug])],
        ]);
    }

    /**
     * Delete the given resource from storage.
     *
     * @param \Rinvex\Tags\Contracts\TagContract $tag
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(TagContract $tag)
    {
        $tag->delete();

        return intend([
            'url' => route('adminarea.tags.index'),
            'with' => ['warning' => trans('cortex/tags::messages.tag.deleted', ['slug' => $tag->slug])],
        ]);
    }
}
