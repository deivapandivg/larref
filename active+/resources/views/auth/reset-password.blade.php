<x-guest-layout>
    <section class="flexbox-container">
        <div class="col-12 d-flex align-items-center justify-content-center">
            <div class="col-lg-4 col-md-6 col-10 box-shadow-2 p-0 " >
                <div class="card border-grey border-lighten-3 px-2 py-2  m-0">
                    <div class="card-header border-0">
                        <div class="text-center mb-1">
                            <img src="{{ asset('public/accsource/logo.png') }}" alt="branding logo"  style="width: 30%;">
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="formAuthentication" class="mb-2" action="{{ route('password.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">
                            <div class="mb-2">
                                <label for="email" class="form-label">Email</label>
                                <input class="form-control" type="email" name="email" value="{{ $request->email }}" required autofocus autocomplete="email" />
                                
                            </div>
                            <div class="mb-2">
                                <label for="email" class="form-label">Password</label>
                                <input class="form-control" type="password" name="password" required autocomplete="new-password" />
                                
                            </div>
                            <div class="mb-2">
                                <label for="email" class="form-label">Confirm Password</label>
                                <input class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                                
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn round btn-block btn-glow btn-bg-gradient-x-purple-blue col-12 mr-1 mb-1">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
