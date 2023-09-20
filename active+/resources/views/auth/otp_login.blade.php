<x-guest-layout>
    <section class="flexbox-container">
        <div class="col-12 d-flex align-items-center justify-content-center">
            <div class="col-lg-4 col-md-6 col-10 box-shadow-2 p-0">
                <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
                    <div class="card-header border-0">
                        <div class="text-center mb-1">
                            <img src="{{ asset('public/accsource/logo.png') }}" alt="branding logo"  style="width: 30%;">
                        </div>
                       <!--  <div class="font-large-1  text-center">
                            Member Login
                        </div> -->
                    </div>
                    <div class="card-content">
                        <!-- <x-auth-session-status class="mb-4" :status="session('status')" /> -->
                        <!-- <x-auth-validation-errors class="mb-4" :errors="$errors" /> -->
                        <div class="card-body">
                            <form method="POST" class="form-horizontal" action="{{ route('otp_submit') }}" novalidate>
                                @csrf
                                <fieldset class="form-group position-relative has-icon-left">
                                    <input type="email" class="form-control round" id="user-name" placeholder="Your email" name="email" required>
                                    <div class="form-control-position">
                                        <i class="ft-mail"></i>
                                    </div>
                                </fieldset>
                                <div class="form-group row">
                                    <div class="col-md-12 col-12 float-sm-left text-center text-sm-left">
                                       <center> <a href="{{ route('login') }}" class="card-link">Login With UserName & Password</a></center>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn round btn-block btn-glow btn-bg-gradient-x-purple-blue col-12 mr-1 mb-1">Send OTP</button>
                                </div>

                            </form>
                        </div>
                        <!-- <p class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-2 "><span>OR Sign Up Using</span></p> -->
                        <!-- <div class="text-center">
                            <a href="#" class="btn btn-social-icon round mr-1 mb-1 btn-facebook"><span class="ft-facebook"></span></a>
                            <a href="#" class="btn btn-social-icon round mr-1 mb-1 btn-twitter"><span class="ft-twitter"></span></a>
                            <a href="#" class="btn btn-social-icon round mr-1 mb-1 btn-instagram"><span class="ft-instagram"></span></a>
                        </div> -->

                        <p class="card-subtitle text-muted text-right font-small-3 mx-2 my-1">
                           <span>Don't have an account ? 
                              @if (Route::has('register'))
                              <a href="{{ route('register') }}" class="card-link">Sign Up</a>
                              @endif
                           </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
