<div class="float-right d-flex align-items-center">
    <div>
        @lang('crud.total'): {{ $pagination->total() }}
    </div>
    @if($pagination->lastPage() > 1)
    <div class="ml-2">
        {{ $pagination->links('admin.layouts.partials.pagination') }}
    </div>
    @endif
</div>