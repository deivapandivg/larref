<x-guest-layout>
    <section class="flexbox-container">
        <div class="col-12 d-flex align-items-center justify-content-center">
            <div class="col-lg-4 col-md-6 col-10 box-shadow-2 p-0">
                <div class="card border-grey border-lighten-3 px-2 py-2 m-0">
                    <div class="card-header border-0">
                        <div class="text-center mb-1">
                            <img src="{{ asset('public/accsource/logo.png') }}" alt="branding logo" style="width:30%;">
                        </div>
                        <div class="font-large-1 text-center">
                            Recover Password
                        </div>
                        <!-- <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
                            <span>We will send you a link to reset password.</span>
                        </h6> -->
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form-horizontal" method="POST"  action="{{ route('password.email') }}" novalidate>
                                @csrf
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="email" class="form-control round" id="user-email" placeholder="Your Email Address" name="email" required>
                                    <div class="form-control-position">
                                        <i class="ft-mail"></i>
                                    </div>
                                </fieldset>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn round btn-block btn-glow btn-bg-gradient-x-purple-blue col-12 mr-1 mb-1">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                     <div class="text-center">
                      <a href="{{url('login')}}" class="d-flex align-items-center justify-content-center">
                      <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                       Back to login
                        </a>
                    </div>
                    <!-- <div class="card-footer border-0 p-0">
                        <p class="float-sm-center text-center">Not a member ?
                            @if (Route::has('register'))
                              <a href="{{ route('register') }}" class="card-link">Sign Up</a>
                            @endif
                        </p>
                    </div> -->
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
