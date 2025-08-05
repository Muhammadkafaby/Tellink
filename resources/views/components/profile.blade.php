<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body class="w-64 bg-white h-full">
    <div class="p-4 border-b border-gray-200 flex justify-start items-center gap-3 w-full">
        <div class="w-[20%]">
            @if(Auth::check() && Auth::user()->avatar)
                <img src="{{ asset(Auth::user()->avatar) }}" alt="" class="rounded-full">
            @else
                <img src="/asset/profile.svg" alt="">
            @endif
        </div>
        <h1 class="text-xl h-full flex justify-center items-center font-semibold  text-gray-800">
            {{ Auth::check() ? Auth::user()->name : 'Guest' }}
        </h1>
    </div>
    <div class="p-4 h-[90%]">
        <nav class="space-y-2 h-full flex flex-col justify-between">
            <div>
                <a href="/profile"
                    class="px-4 py-2 rounded-md flex gap-3 items-center justify-start {{ Request::is('profile*') ? 'bg-red-500 text-white' : 'text-gray-700 hover:bg-red-500 hover:text-white' }}">
                    <i class="fas fa-circle-user text-2xl"></i>
                    <span class="h-full">Profile</span>

                </a>

            </div>
        
         
            <a href="/logout"
            class="px-4 py-2 rounded-md flex gap-3 items-center justify-start text-gray-700 hover:bg-red-500 hover:text-white">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span class="h-full">Logout</span>
        </a>
         

         
        </nav>
    </div>
</body>

</html>

