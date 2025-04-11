<header class="bg-white shadow-md p-4">
    <div class="container mx-auto flex items-center space-x-4">
        <h1 class="text-2xl font-bold text-gray-800 flex-shrink-2">ShopName</h1>
        <nav class="flex-1 mx-6">
            <ul class="flex py-2 justify-start space-x-4 flex-shrink-1">
                @foreach($menuItems as $key => $value)
                    <li><a href="{{ route($value) }}" class="text-blue-600 hover:text-gray-200 transition-colors duration-200">{{$key}}</a></li>
                @endforeach
            </ul>
        </nav>

        @if(!is_null ($user))
            <a href="{{route ('chat.index')}}" class="relative text-gray-700 hover:text-gray-900">
                <span class="text-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="30px" width="30px" version="1.1" id="Capa_1" viewBox="0 0 59 59" xml:space="preserve">
                        <g>
                            <path style="fill:#9BC0EA;" d="M1,58l4.988-14.963C3.458,38.78,2,33.812,2,28.5C2,12.76,14.76,0,30.5,0S59,12.76,59,28.5   S46.24,57,30.5,57c-4.789,0-9.299-1.187-13.26-3.273L1,58z"/>
                            <path style="fill:#B1D2F2;" d="M28.5,31c3.987,0,7.74,0.994,11.028,2.747c5.212,2.779,11.588-0.748,12.31-6.611   c0.208-1.688,0.219-3.438,0.009-5.227C50.691,12.084,42.75,4.203,32.915,3.132C19.959,1.72,9,11.83,9,24.5c0,0.046,0,0.092,0,0.138   c0.037,5.802,5.949,9.804,11.394,7.799C22.921,31.508,25.651,31,28.5,31z"/>
                            <circle style="fill:#FFFFFF;" cx="17" cy="29" r="3"/>
                            <circle style="fill:#FFFFFF;" cx="30" cy="29" r="3"/>
                            <circle style="fill:#FFFFFF;" cx="43" cy="29" r="3"/>
                            <path style="fill:#87AFD8;" d="M25.976,51.783c-0.12-0.54-0.651-0.877-1.193-0.76l-7.76,1.727c-0.007,0.002-0.012,0.007-0.02,0.009   c-0.006,0.001-0.012,0-0.017,0.001L2.533,56.563l4.403-13.209c0.175-0.524-0.108-1.091-0.632-1.265   c-0.525-0.176-1.091,0.108-1.265,0.632L0.051,57.684c-0.116,0.349-0.032,0.732,0.219,1C0.462,58.889,0.728,59,1,59   c0.085,0,0.17-0.011,0.254-0.033l16.203-4.264l0,0l7.76-1.727C25.756,52.856,26.096,52.322,25.976,51.783z"/>
                        </g>
                    </svg>
                </span>
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">{{$unReadMessageCount}}</span>
            </a>
        @endif
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
