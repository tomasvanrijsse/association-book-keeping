<x-layout-guest>
    <div class="container">
        <div class="row">
            <div class="span6">
                <h3>Login</h3>
                <div class="well">
                    <form action="/login" method="POST" enctype="multipart/form-data" >
                        @csrf
                        <div class="control-group">
                            <label class="control-label" for="type">Email</label>
                            <div class="controls">
                                <input type="text" value="" name="email">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="type">Password</label>
                            <div class="controls">
                                <input type="password" value="" name="password">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </div>
                    </form>
                    @if ($errors->any())
                        <div class="alert alert-error">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout-guest>
