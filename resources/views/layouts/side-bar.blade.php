<button data-drawer-target="sidebar" data-drawer-toggle="sidebar" aria-controls="sidebar" type="button"
    class="inline-flex items-center p-2 mt-2 ms-3 text-gray-500 rounded-xl sm:hidden hover:bg-gray-100 focus:outline-none">
    <span class="sr-only">Open sidebar</span>
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path></svg>
</button>

<aside id="sidebar" class="fixed top-0 left-0 z-40 w-[280px] h-screen bg-white transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-8 py-8 flex flex-col items-center overflow-y-auto">
        {{-- Logo Container --}}
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-full py-4 mb-10 bg-white rounded-[20px] shadow-[0_4px_24px_-4px_rgba(0,0,0,0.08)] border border-gray-50">
            <x-application-logo class="scale-90" />
        </a>

        {{-- Navigation Links --}}
        <nav class="flex-1 w-full space-y-4">
            <a href="{{ route('dashboard') }}" class="flex justify-center items-center w-full py-3.5 px-4 rounded-full font-bold {{ request()->routeIs('dashboard') || request()->routeIs('calculator.*') ? 'bg-[#2A5C4D] text-white' : 'text-[#2A5C4D] hover:bg-gray-50' }}">
                Dashboard
            </a>
            <a href="{{ route('emissions.index') }}" class="flex justify-center items-center w-full py-3.5 px-4 rounded-full font-bold {{ request()->routeIs('emissions.*') ? 'bg-[#2A5C4D] text-white' : 'text-[#2A5C4D] hover:bg-gray-50' }}">
                Activity
            </a>
            <a href="{{ route('community.index') }}" class="flex justify-center items-center w-full py-3.5 px-4 rounded-full font-bold {{ request()->routeIs('community.*') ? 'bg-[#2A5C4D] text-white' : 'text-[#2A5C4D] hover:bg-gray-50' }}">
                Community
            </a>
            <a href="{{ route('blogs.index') }}" class="flex justify-center items-center w-full py-3.5 px-4 rounded-full font-bold {{ request()->routeIs('blogs.*') ? 'bg-[#2A5C4D] text-white' : 'text-[#2A5C4D] hover:bg-gray-50' }}">
                Blogs
            </a>

            @if(Auth::user()->isAdmin())
            <div class="pt-4 mt-4 border-t border-gray-100 w-full"></div>
            <a href="{{ route('admin.dashboard') }}" class="flex justify-center items-center w-full py-3.5 px-4 rounded-full font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-[#2A5C4D] text-white' : 'text-[#2A5C4D] hover:bg-gray-50' }}">
                Admin Panel
            </a>
            @endif
        </nav>
        
        <form method="POST" action="{{ route('logout') }}" class="w-full mt-auto pt-4 border-t border-gray-100">
            @csrf
            <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-full font-bold text-red-500 hover:bg-red-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </button>
        </form>
    </div>
</aside>