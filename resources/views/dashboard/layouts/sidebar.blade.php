<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse sidebar-collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="/dashboard/articles">
                <span data-feather="edit" class="align-text-bottom"></span>
                Articles
                </a>
            </li>
            @can('admin')
            <li class="nav-item">
                <a class="nav-link" href="/dashboard/categories">
                <span data-feather="grid" class="align-text-bottom"></span>
                Categories
                </a>
            </li>
        @endcan
        </ul>
    </div>
</nav>