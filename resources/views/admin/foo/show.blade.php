@extends('admin.layouts.template-form')

@section('title')
    <i class="fas fa-copy mr-1"></i> Foos
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin::foos.index') }}"><i class="fas fa-copy"></i> Foos</a></li>
<li class="breadcrumb-item"><i class="fas fa-eye"></i> Show</li>
@endsection

@section('content')
<div class="row">
    <section class="col connectedSortable">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-eye mr-1"></i> Show (ID: {{ $item->id }})</h3>
            </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-2 text-right">Something</label>
                                <div class="col">{{ $item->something }}</div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 text-right">Created At</label>
                                <div class="col">{{ $item->created_at->format('d/m/Y H:i:s') }}</div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 text-right">Updated At</label>
                                <div class="col">{{ $item->updated_at->format('d/m/Y H:i:s') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('admin::foos.index') }}" class="btn btn-primary">Back</a>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
