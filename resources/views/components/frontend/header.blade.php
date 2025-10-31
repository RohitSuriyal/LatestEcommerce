<style>
    .nav_shadow {

        box-shadow: 0.4px 0.3px 1px rgb(0, 0, 0, 0.5);

    }

    .nav_color {
        background-color: #f28b00 !important
    }

    .navimage {
        border-radius: 50% !important;
        height: 50px;
        width: 50px;
    }

    .colorwhite {
        color: white;
    }

    .btn:hover {
        color: white;
        box-shadow: none;
    }

    .gap-1>*+* {
        margin-left: 0.25rem;
    }

    .gap-2>*+* {
        margin-left: 0.5rem;
    }

    .gap-3>*+* {
        margin-left: 1rem;
    }

    .gap-4>*+* {
        margin-left: 1.5rem;
    }

    .gap-5>*+* {
        margin-left: 3rem;
    }

    .search_conatiner {
        position: relative;
    }

    .search_icon {
        position: absolute;
        top: 33%;
        left: 1%;

    }
</style>


<nav class="navbar nav_color navbar-expand-lg navbar-light nav_shadow ">
    <a class="navbar-brand" href="{{ route('frontend.home') }}"><img class="navimage"
            src="{{ asset('/images/cart.jpg') }}"></img></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse d-flex" id="navbarSupportedContent">
        <ul class="navbar-nav search_conatiner" style="flex:1">
            <input class="form-control px-5" />
            <i class="fas fa-search search_icon" style="color: #74C0FC;"></i>
        </ul>
        <div style="width:30%" class="px-3">
            <ul class="d-flex align-items-center  gap-3 mb-0 list-unstyled ">
                <a class="text-decoration-none " href="{{ route('frontend.home') }}">
                    <li class="colorwhite roboto">Home</li>
                </a>
                <div class="dropdown">
                    <button class="btn colorwhite dropdown-toggle white roboto" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Account
                    </button>
                    <div class="dropdown-menu" style="backgroud-color:white" aria-labelledby="dropdownMenuButton">
                        @if (Auth::guard('web')->check())
                            <a class="dropdown-item roboto" href="{{ route('frontend.userprofileview') }}">profile</a>
                            <a class="dropdown-item roboto" href="{{ route('frontend.logout') }}">Logout</a>
                        @else
                            <a class="dropdown-item roboto" href="{{ route('frontend.userloginview') }}">Login</a>
                        @endif
                        <a style="cursor: pointer" class="dropdown-item roboto wishlist_modal">
                            WishList
                        </a>

                        {{-- <a class="dropdown-item roboto" href="#">Something else here</a> --}}
                    </div>
                </div>
                <li class="add_to_cart" style="cursor: pointer;font-size:16px;font-weight:bold;color:white"><i class="fas fa-cart-plus mr-2"
                            style="color: #74C0FC;"></i>Cart
                </li>

            </ul>
        </div>
    </div>
</nav>
