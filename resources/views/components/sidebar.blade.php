<aside class="w-64 bg-white ">
    <div class="p-4 border-b border-gray-200 flex justify-start items-center gap-3 w-full">
        <div class="w-[20%]">
            <img src="/asset/logo.png" alt="">
        </div>
        <h1 class="text-xl h-full flex justify-center items-center font-semibold  text-gray-800">Tellink</h1>
    </div>
    <div class="p-4">
        <nav class="space-y-2">
            <a href="/listuser"
            class="block px-4 py-2 rounded-md {{ Request::is('listuser*') ? 'bg-red-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            List User
         </a>
         
         <a href="/report"
            class="block px-4 py-2 rounded-md {{ Request::is('report*') ? 'bg-red-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            Report
         </a>
         
         <a href="/userpost"
            class="block px-4 py-2 rounded-md {{ Request::is('userpost*') ? 'bg-red-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            User Post
         </a>
         
        </nav>
    </div>
</aside>