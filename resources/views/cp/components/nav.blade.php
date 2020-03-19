<nav class="navbar navbar-default">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/cp"><img src="{{ asset('admin/new/img/limak-logo.png') }}" alt="logo" class="img-responsive"></a>
        </div>
        <div class="navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                        aria-expanded="false">{{ auth()->user()->name }} {{ auth()->user()->surname}}  <span class="caret"></span>
                        <img src="{{ asset('admin/new/img/profil-image.jpg') }}" alt="profil"></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Profilim</a></li>
                        <li><a href="#">Tənzimləmələr</a></li>
                        <li><a href="#">Çıxış</a></li>
                    </ul>
                </li>
            </ul>
        </div>
</nav>