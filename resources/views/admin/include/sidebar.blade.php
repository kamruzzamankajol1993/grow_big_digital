<nav id="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('/') }}{{ $front_logo_name }}" alt="{{ $ins_name }} Logo" height="50px !important;" >
    </div>
    <ul class="nav flex-column" id="sidebar-menu">
 
        {{-- Dashboard --}}
        <li class="nav-item">
            <a class="nav-link {{ Route::is('home')  ? 'active' : '' }}" href="{{route('home')}}">
                <i data-feather="grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        
      <li class="sidebar-title">
            <span>Requested Product</span>
        </li>
        {{-- Requested Product List --}}
        <li class="nav-item">
             <a class="nav-link {{Route::is('order.edit') || Route::is('order.index') || Route::is('order.show') ? 'active' : ''}}" href="{{route('order.index')}}">
                <i data-feather="shopping-bag"></i>
                <span> Product List</span>
            </a>
        </li>

        <li class="sidebar-title">
            <span>Product</span>
        </li>
     
        {{-- Product List --}}
        <li class="nav-item">
            <a class="nav-link {{ Route::is('product.index') || Route::is('product.edit') || Route::is('product.show') ? 'active' : '' }}" href="{{route('product.index')}}">
                <i data-feather="list"></i>
                <span>Product List</span>
            </a>
        </li>
        
        {{-- Add Product --}}
        <li class="nav-item">
            <a class="nav-link {{Route::is('product.create') ? 'active' : ''}}" href="{{route('product.create')}}">
                <i data-feather="plus-circle"></i>
                <span>Add Product</span>
            </a>
        </li>
    
        {{-- Category --}}
        <li class="nav-item">
            <a class="nav-link {{ Route::is('category.index') || Route::is('category.edit') || Route::is('category.create') ? 'active' : '' }}" href="{{ route('category.index') }}">
                <i data-feather="layers"></i>
                <span>Category</span>
            </a>
        </li>

        {{-- Company --}}
        <li class="nav-item">
            <a class="nav-link {{ Route::is('brand.index') || Route::is('brand.edit') || Route::is('brand.create') ? 'active' : '' }}" href="{{ route('brand.index') }}">
                <i data-feather="briefcase"></i>
                <span>Company</span>
            </a>
        </li>

        {{-- NEW: Company Category --}}
        <li class="nav-item">
            <a class="nav-link {{ Route::is('company-category.index') || Route::is('company-category.edit') || Route::is('company-category.create') ? 'active' : '' }}" href="{{ route('company-category.index') }}">
                <i data-feather="menu"></i>
                <span>Company Category</span>
            </a>
        </li>
        
        {{-- Customers --}}
        <li class="nav-item">
            <a class="nav-link {{ Route::is('customer.index') || Route::is('customer.edit') || Route::is('customer.create') ? 'active' : '' }}" href="{{ route('customer.index') }}">
                <i data-feather="users"></i>
                <span>Customers</span>
            </a>
        </li>

        <li class="sidebar-title">
            <span>Settings</span>
        </li>

          
        <li class="nav-item">
            <a class="nav-link {{ Route::is('socialLink.index') || Route::is('socialLink.edit') || Route::is('socialLink.create') ? 'active' : '' }}" href="{{ route('socialLink.index') }}"> <i data-feather="link"></i><span>Social Link</span></a>
        </li>
                

        <li class="nav-item">
            <a class="nav-link {{ Route::is('message.index') || Route::is('message.edit') || Route::is('message.create') ? 'active' : '' }}" href="{{ route('message.index') }}"> <i data-feather="mail"></i><span>Message</span></a>
        </li>
                  

        {{-- Panel Settings --}}
        <li class="nav-item">
            <a class="nav-link {{ Route::is('systemInformation.index') || Route::is('systemInformation.edit') || Route::is('systemInformation.create') ? 'active' : '' }}" href="{{ route('systemInformation.index') }}">
                <i data-feather="settings"></i>
                <span>Panel Settings</span>
            </a>
        </li>
    
        {{-- User --}}
        <li class="nav-item mb-5">
            <a class="nav-link {{ Route::is('users.show') || Route::is('users.index') || Route::is('users.edit') || Route::is('users.create') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <i data-feather="user"></i>
                <span>User</span>
            </a>
        </li>
       
    </ul>
</nav>