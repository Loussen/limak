@php($roles = [])
@foreach (Auth::guard('admin')->user()->relAdminRoles as $role)
   @php( $roles[] = $role->relRole->label)
@endforeach
<aside class="main-sidebar fixed offcanvas b-r sidebar-tabs" data-toggle='offcanvas'>
    <div class="sidebar">
        <div class="d-flex hv-100 align-items-stretch">
            <div class="indigo text-white">
                <div class="nav mt-5 pt-5 flex-column nav-pills" id="v-pills-tab" role="tablist"
                     aria-orientation="vertical">
                    {{--<a class="nav-link" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab"--}}
                       {{--aria-controls="v-pills-home" aria-selected="true"><i class="icon-inbox2"></i></a>--}}
                    {{--<a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab"--}}
                       {{--aria-controls="v-pills-profile" aria-selected="false"><i class="icon-add"></i></a>--}}
                    <a class="nav-link blink skin_handle"  href="#"><i class="icon-lightbulb_outline"></i></a>
                    @if(hasRole('media', $roles))
                    <a class="nav-link" id="v-pills-agenda-tab" href="{{route('sp.index')}}" title="Səhifə yarat"><i class="icon-agenda"></i></a>
                    @endif
                    @if(hasRole('problematic_department', $roles) || hasRole('accountant', $roles))
                        <a class="nav-link" id="v-pills-agenda-tab" href="{{route('users.list')}}" title="İstifadəçilər"><i class="icon-users"></i></a>
                    @endif
                    @if(hasRole('operator', $roles))
                        <a class="nav-link" id="v-pills-messages-tab" href="{{route('chat.index')}}" title="Çat"><i class="icon-message"></i></a>
                        {{--<a class="nav-link" id="v-pills-agenda-tab" href="{{route('users.list')}}" title="İstifadəçilər"><i class="icon-users"></i></a>--}}
                        <a class="nav-link" id="v-pills-messages-tab" href="{{route('msg.index')}}" title="Məktub"><i class="icon-mail-envelope-closed"></i></a>
                    @endif
                    @if(hasRole('storage_home', $roles) || hasRole('casher', $roles) )
                        <a class="nav-link" id="v-pills-agenda-tab" href="{{route('depot.index')}}" title="Anbar"><i class="icon-cash-register"></i></a>
                    @endif
                    {{--<a class="nav-link" id="v-pills-settings-tab" href="#"><i class="icon-settings"></i></a>--}}
                    <a href="">
                        <figure class="avatar">
                            <span class="avatar-badge online"></span>
                        </figure>
                    </a>
                </div>
            </div>
            <div class="tab-content flex-grow-1" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                     aria-labelledby="v-pills-home-tab">
                    <div class="relative brand-wrapper sticky b-b">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <div class="text-xs-center">
                                <span class="font-weight-lighter s-18">Menu</span>
                            </div>
                        </div>
                    </div>
                    <ul class="sidebar-menu">
                        <li class="treeview">
                            <a href="/">
                                <i class="icon icon-sailing-boat-water s-24"></i> <span>Ana səhifə</span>
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="/admin/user/allData/">
                                <i class="icon icon-user s-24"></i> <span>İstifadəçi məlumatləarı</span>
                            </a>
                        </li>
                        {{--<li class="treeview"><a href="#"><i class="icon icon-user s-24"></i>istifadəçilər<i--}}
                                    {{--class=" icon-angle-left  pull-right"></i></a>--}}
                            {{--<ul class="treeview-menu">--}}
                                {{--<li><a href="panel-page-users.html"><i class="icon icon-circle-o"></i>All Users</a>--}}
                                {{--</li>--}}
                                {{--<li><a href="panel-page-users-create.html"><i class="icon icon-add"></i>Add User</a>--}}
                                {{--</li>--}}
                                {{--<li><a href="panel-page-profile.html"><i class="icon icon-user"></i>User Profile </a>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                        @if(hasRole('media', $roles))
                        <li class="treeview"><a href="#">
                                <i class="icon icon-shop s-24"></i>
                                <i class=" icon-angle-left  pull-right"></i>
                                <span>Mağazalar</span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{route('shops.index')}}"><i class="icon icon-circle-o"></i>Siyahı</a>
                                </li>
                                <li><a href="{{route('shops.create')}}"><i class="icon icon-circle-o"></i>Əlavə et</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if(hasRole('operator', $roles) || hasRole('problematic_department', $roles) || hasRole('accountant', $roles))
                        <li><a href="{{url('/admin/order/list')}}">
                                <i class="icon icon-orders s-24"></i>
                                <span>
                                    Bütün sifarişlər
                                </span>
                            </a>
                        </li>
                        @endif
                        @if(hasRole('problematic_department', $roles) || hasRole('buyer', $roles) || hasRole('express-buyer', $roles))
                        <li><a href="{{route('invoiceless.index')}}">
                                <i class="icon icon-notebook5 s-24"></i>
                                <span>
                                    Bəyənnamələri olmayanlar
                                </span>
                            </a>
                        </li>
                        @endif
                        @if(hasRole('media', $roles))
                        <li class="treeview"><a href="#">
                                <i class="icon icon-users s-24"></i>
                                <i class=" icon-angle-left  pull-right"></i>
                                <span>Partnyorlar</span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{route('partners.index')}}"><i class="icon icon-circle-o"></i>Siyahı</a>
                                </li>
                                <li><a href="{{route('partners.create')}}"><i class="icon icon-circle-o"></i>Əlavə et</a>
                                </li>
                            </ul>
                        </li>

                        <li class="treeview"><a href="#">
                                <i class="icon icon-question s-24"></i>
                                <i class=" icon-angle-left  pull-right"></i>
                                <span>Ən çox verilən suallar</span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{route('faq.index')}}"><i class="icon icon-circle-o"></i>Siyahı</a>
                                </li>
                                <li><a href="{{route('faq.create')}}"><i class="icon icon-circle-o"></i>Əlavə et</a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview"><a href="#">
                                <i class="icon icon-map-pin s-24"></i>
                                <i class=" icon-angle-left  pull-right"></i>
                                <span>Ölkələr</span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{route('countries.index')}}"><i class="icon icon-circle-o"></i>Siyahı</a>
                                </li>
                                <li><a href="{{route('countries.create')}}"><i class="icon icon-circle-o"></i>Əlavə et</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if(hasRole('media', $roles))
                            <li class="treeview"><a href="#">
                                    <i class="icon icon-image s-24"></i>
                                    <i class=" icon-angle-left  pull-right"></i>
                                    <span>Slider</span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="{{route('slider.index')}}"><i class="icon icon-circle-o"></i>Siyahı</a>
                                    </li>
                                    <li><a href="{{route('slider.create')}}"><i class="icon icon-circle-o"></i>Əlavə et</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        @if(hasRole('media', $roles))
                        <li class="treeview"><a href="#">
                                <i class="icon icon-newspaper-o s-24"></i>
                                <i class=" icon-angle-left  pull-right"></i>
                                <span>Xəbərlər</span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{route('news.index')}}"><i class="icon icon-circle-o"></i>Siyahı</a>
                                </li>
                                <li><a href="{{route('news.create')}}"><i class="icon icon-circle-o"></i>Əlavə et</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if(hasRole('buyer', $roles) || hasRole('express-buyer', $roles))
                        <li class="treeview"><a href="#">
                                <i class="icon icon-orders s-24"></i>
                                <i class=" icon-angle-left  pull-right"></i>
                                <span>Daxil olan sifarişlər</span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{route('accept')}}"><i class="icon icon-circle-o"></i>Daxil olan sifarişlər</a>
                                </li>
                                <li><a href="{{route('accept')}}#/my-orders"><i class="icon icon-circle-o"></i>İcra edilən sifarişlərim</a>
                                </li>
                                <li><a href="{{route('accept')}}#/order/logs"><i class="icon icon-circle-o"></i>Tamamlanmış sifarişlər</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if(hasRole('invoice_uploader', $roles) || hasRole('super_admin', $roles)|| hasRole('operator', $roles))
                            <li><a href="{{route('accept')}}#/invoice-upload">
                                    <i class="icon icon-notebook5 s-24"></i>
                                    <span>
                                    İnvoys yüklənəcəklər
                                </span>
                                </a>
                            </li>
                        @endif
                        @if(hasRole('super_admin',$roles))
                        <li class="treeview"><a href="#">
                                <i class="icon icon-dialpad s-24"></i>
                                <i class=" icon-angle-left  pull-right"></i>
                                <span>User Management</span>
                            </a>
                            <ul class="treeview-menu">
                                <li class="treeview"><a href="#">
                                        <i class="icon icon-documents3 s-24"></i>
                                        <i class=" icon-angle-left  pull-right"></i>
                                        <span>API - lər</span>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li><a href="{{route('apis.index')}}"><i class="icon icon-circle-o"></i>Siyahı</a>
                                        </li>
                                        <li><a href="{{route('apis.create')}}"><i class="icon icon-circle-o"></i>Əlavə et</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="treeview"><a href="#">
                                        <i class="icon icon-documents3 s-24"></i>
                                        <i class=" icon-angle-left  pull-right"></i>
                                        <span>Admin tipləri</span>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li><a href="{{route('roles.index')}}"><i class="icon icon-circle-o"></i>Siyahı</a>
                                        </li>
                                        <li><a href="{{route('roles.create')}}"><i class="icon icon-circle-o"></i>Əlavə et</a>
                                    </ul>
                                </li>
                                <li class="treeview"><a href="#">
                                        <i class="icon icon-documents3 s-24"></i>
                                        <i class=" icon-angle-left  pull-right"></i>
                                        <span>Adminlər</span>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li><a href="{{route('admins.index')}}"><i class="icon icon-circle-o"></i>Siyahı</a>
                                        </li>
                                        <li><a href="{{route('admins.create')}}"><i class="icon icon-circle-o"></i>Əlavə et</a>

                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        @endif
                        {{--<li class="treeview"><a href="#">--}}
                                {{--<i class="icon icon-contact_phone s-24"></i>--}}
                                {{--<i class=" icon-angle-left  pull-right"></i>--}}
                                {{--<span>Kontaktlar</span>--}}
                            {{--</a>--}}
                            {{--<ul class="treeview-menu">--}}
                                {{--<li><a href="{{route('admin.contact.index')}}"><i class="icon icon-circle-o"></i>Siyahı</a>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                        @if(hasRole('storage_foreign', $roles) || hasRole('problematic_department', $roles))
                            <li class="treeview"><a href="#">
                                    <i class="icon icon-document-text2 s-24"></i>
                                    <i class=" icon-angle-left  pull-right"></i>
                                    <span>Türkiyə anbarı</span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="{{route('invoices.index')}}"><i class="icon icon-circle-o"></i>Siyahı</a></li>
                                    <li><a href="{{route('sack.index')}}"><i class="icon icon-circle-o"></i>Çuvallar</a></li>
                                    <li><a href="{{route('dispatch.index')}}"><i class="icon icon-circle-o"></i>Sevkiyat</a></li>
                                </ul>
                            </li>
                        @endif
                        @if(hasRole('accountant', $roles))
                            <li class="treeview"><a href="#">
                                    <i class="icon icon-money s-24"></i>
                                    <i class=" icon-angle-left  pull-right"></i>
                                    <span>Ödəniş və problemlər</span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="{{route('accountant-way.index')}}"><i class="icon icon-circle-o"></i>Yolda olanlar</a>
                                    </li>
                                    <li><a href="{{route('rejection-for-accountant.index')}}"><i class="icon icon-circle-o"></i>İmtinalar</a>
                                    </li>
                                    <li><a href="{{route('product-rejection-for-accountant.index')}}"><i class="icon icon-circle-o"></i>Çatışmazlıqlar</a>
                                    </li>
                                    <li><a href="{{route('log-rejections.index')}}"><i class="icon icon-circle-o"></i>Çatışmazlıq Loqları</a>
                                    </li>
                                </ul>
                            </li>
                            {{--<li><a href="{{route('all-invoices.index')}}">
                                <i class="icon icon-notebook5 s-24"></i>
                                <span>
                                    Bütün invoyslar
                                </span>
                            </a>
                            </li>--}}
                        @endif
                        @if(hasRole('problematic_department', $roles))
                            {{--<li><a href="{{route('all-invoices.index')}}">
                                    <i class="icon icon-notebook5 s-24"></i>
                                    <span>
                                    Bütün invoyslar
                                </span>
                                </a>
                            </li>--}}
                        @endif
                        @if(hasRole('dispatcher', $roles))
                        <li class="treeview"><a href="#">
                                <i class="icon icon-directions_car s-24"></i>
                                <i class=" icon-angle-left  pull-right"></i>
                                <span>Kuryer</span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{route('courier')}}"><i class="icon icon-circle-o"></i>Daxil olan kuryer sifarişləri</a></li>
                                <li><a href="{{route('courier')}}#/waiting"><i class="icon icon-circle-o"></i>Göndərilən kuryerlər</a>
                                </li>
                                <li><a href="{{route('courierDeliveryLogs')}}"><i class="icon icon-circle-o"></i>Tarixçə</a>
                                </li>
                            </ul>
                        </li>
                            @endif
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <div class="relative brand-wrapper sticky b-b p-3">
                        <form>
                            <div class="form-group input-group-sm has-right-icon">
                                <input class="form-control form-control-sm light r-30" placeholder="Search" type="text">
                                <i class="icon-search"></i>
                            </div>
                        </form>
                    </div>
                    <div class="sticky slimScroll">

                        <div class="p-2">
                            <ul class="list-unstyled">
                                <!-- Alphabet with number of contacts -->
                                <li class="pt-3 pb-3 sticky p-3 b-b white">
                                    <span class="badge r-3 badge-success">A</span>
                                </li>
                                <!-- Single contact -->
                                <li class="my-1">
                                    <div class="card no-b p-3">
                                        <div class="">

                                            <div class="image mr-3  float-left">
                                                {{--<img class="w-40px" src="assets/img/dummy/u1.png" alt="User Image">--}}
                                            </div>
                                            <div>
                                                <div>
                                                    <strong>Alexander Pierce</strong>
                                                </div>
                                                <small> alexander@paper.com</small>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="my-1">
                                    <div class="card no-b p-3">
                                        <div class="">

                                            <div class="image mr-3  float-left">
                                                {{--<img class="w-40px" src="assets/img/dummy/u6.png" alt="User Image">--}}
                                            </div>
                                            <div>
                                                <div>
                                                    <strong>Alexander Pierce</strong>
                                                </div>
                                                <small> alexander@paper.com</small>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="my-1">
                                    <div class="card no-b p-3">
                                        <div class="">

                                            <div class="image mr-3  float-left">
                                                {{--<img class="w-40px" src="assets/img/dummy/u6.png" alt="User Image">--}}
                                            </div>
                                            <div>
                                                <div>
                                                    <strong>Alexander Pierce</strong>
                                                </div>
                                                <small> alexander@paper.com</small>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <ul class="list-unstyled">
                                <li class="pt-3 pb-3 sticky p-3 b-b white">
                                    <span class="badge r-3 badge-danger">B</span>
                                </li>
                                <li class="my-1">
                                    <div class="card no-b p-3">
                                        <div class="">

                                            <div class="image mr-3  float-left">
                                                {{--<img class="w-40px" src="assets/img/dummy/u2.png" alt="User Image">--}}
                                            </div>
                                            <div>
                                                <div>
                                                    <strong>Alexander Pierce</strong>
                                                </div>
                                                <small> alexander@paper.com</small>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="my-1">
                                    <div class="card no-b p-3">
                                        <div class="">

                                            <div class="image mr-3  float-left">
                                                {{--<img class="w-40px" src="assets/img/dummy/u3.png" alt="User Image">--}}
                                            </div>
                                            <div>
                                                <div>
                                                    <strong>Alexander Pierce</strong>
                                                </div>
                                                <small> alexander@paper.com</small>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="my-1">
                                    <div class="card no-b p-3">
                                        <div class="">

                                            <div class="image mr-3  float-left">
                                                {{--<img class="w-40px" src="assets/img/dummy/u4.png" alt="User Image">--}}
                                            </div>
                                            <div>
                                                <div>
                                                    <strong>Alexander Pierce</strong>
                                                </div>
                                                <small> alexander@paper.com</small>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <ul class="list-unstyled">
                                <!-- Alphabet with number of contacts -->
                                <li class="pt-3 pb-3 sticky p-3 b-b white">
                                    <span class="badge r-3 badge-success gradient">C</span>
                                </li>
                                <!-- Single contact -->
                                <li class="my-1">
                                    <div class="card no-b p-3">
                                        <div class="">

                                            <div class="image mr-3  float-left">
                                                {{--<img class="w-40px" src="assets/img/dummy/u1.png" alt="User Image">--}}
                                            </div>
                                            <div>
                                                <div>
                                                    <strong>Alexander Pierce</strong>
                                                </div>
                                                <small> alexander@paper.com</small>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="my-1">
                                    <div class="card no-b p-3">
                                        <div class="">

                                            <div class="image mr-3  float-left">
                                                {{--<img class="w-40px" src="assets/img/dummy/u6.png" alt="User Image">--}}
                                            </div>
                                            <div>
                                                <div>
                                                    <strong>Alexander Pierce</strong>
                                                </div>
                                                <small> alexander@paper.com</small>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="my-1">
                                    <div class="card no-b p-3">
                                        <div class="">

                                            <div class="image mr-3  float-left">
                                                {{--<img class="w-40px" src="assets/img/dummy/u6.png" alt="User Image">--}}
                                            </div>
                                            <div>
                                                <div>
                                                    <strong>Alexander Pierce</strong>
                                                </div>
                                                <small> alexander@paper.com</small>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="my-4">
                                    <span class="badge r-3 badge-danger purple">D</span>
                                </li>
                                <li class="my-1">
                                    <div class="card no-b p-3">
                                        <div class="">

                                            <div class="image mr-3  float-left">
                                                {{--<img class="w-40px" src="assets/img/dummy/u2.png" alt="User Image">--}}
                                            </div>
                                            <div>
                                                <div>
                                                    <strong>Alexander Pierce</strong>
                                                </div>
                                                <small> alexander@paper.com</small>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="my-1">
                                    <div class="card no-b p-3">
                                        <div class="">

                                            <div class="image mr-3  float-left">
                                                {{--<img class="w-40px" src="assets/img/dummy/u3.png" alt="User Image">--}}
                                            </div>
                                            <div>
                                                <div>
                                                    <strong>Alexander Pierce</strong>
                                                </div>
                                                <small> alexander@paper.com</small>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="my-1">
                                    <div class="card no-b p-3">
                                        <div class="">

                                            <div class="image mr-3  float-left">
                                                {{--<img class="w-40px" src="assets/img/dummy/u4.png" alt="User Image">--}}
                                            </div>
                                            <div>
                                                <div>
                                                    <strong>Alexander Pierce</strong>
                                                </div>
                                                <small> alexander@paper.com</small>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
