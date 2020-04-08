<div class="float-right d-flex align-items-center">
    <div>
        @lang('crud.total'): {{ $pagination->total() }}
    </div>
    <div class="ml-2">
        {{ $pagination->links('admin.layouts.partials.pagination') }}
    </div>
</div>