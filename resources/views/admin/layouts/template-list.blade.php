@extends('admin.layouts.template')

@section('main')
    @yield('content')

    <!-- Modal Delete Confirmation -->
    <script>
        function openDeleteComfirmation(url, item){
            $('#deleteConfirm form').attr('action', url);
            if(item) $('#deleteConfirm form .item').html(' <b>('+item+')</b>');
            $('#deleteConfirm').modal('show');
        }
    </script>

    <div class="modal fade" id="deleteConfirm" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmLabel">Delete Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this item<span class="item"></span>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
