<header class="bg-white shadow-md p-4">
    <div class="container mx-auto flex items-center space-x-4">
        <h1 class="text-2xl font-bold text-gray-800 flex-shrink-2">ShopName</h1>
        <nav class="flex-1 mx-6">
            <ul class="flex py-2 justify-start space-x-4 flex-shrink-1">
                @foreach($menuItems as $key => $value)
                    <li><a href="{{$value}}" class="text-blue-600 hover:text-gray-200 transition-colors duration-200">{{$key}}</a></li>
                @endforeach
            </ul>
        </nav>
        <div class="flex items-center space-x-4 flex-shrink-1">
            <a href="{{route ('cart')}}" class="relative text-gray-700 hover:text-gray-900">
                <span class="text-2xl">🛒</span>
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">{{$cartCount}}</span>
            </a>
            <div>
            @if(!is_null ($user))
                    <a href="{{route ('login')}}" class="bg-info text-blue-600 px-4 py-2 rounded-lg hover:bg-info-dark transition-colors duration-200 font-medium">My Dashboard</a>
                @else
                    <a href="{{route ('login')}}" class="bg-info text-blue-600 px-4 py-2 rounded-lg hover:bg-info-dark transition-colors duration-200 font-medium">Login/Register</a>
                @endif
            </div>
        </div>
    </div>
</header>
