<button data-drawer-target="sidebar" data-drawer-toggle="sidebar" aria-controls="sidebar" type="button"
    class="inline-flex items-center p-2 mt-2 ms-3 text-gray-500 rounded-xl sm:hidden hover:bg-gray-100 focus:outline-none">
    <span class="sr-only">Open sidebar</span>
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path></svg>
</button>

<aside id="sidebar" class="fixed top-0 left-0 z-40 w-[280px] h-screen bg-white transition-transform -translate-x-full sm:translate-x-0 border-r border-gray-100 shadow-[4px_0_24px_rgba(0,0,0,0.01)]" aria-label="Sidebar">
    <div class="h-full px-6 py-8 flex flex-col items-center overflow-y-auto">
        
        @if(request()->routeIs('admin.*'))
            {{-- Admin Console Logo (Consistent with branding image) --}}
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 w-full px-2 mb-10 select-none">
                <img src="{{ asset('images/logo.png') }}" alt="Act4Climate Logo" class="h-10 w-auto object-contain shrink-0" onerror="this.style.display='none'">
                <div>
                    <span class="text-[19px] font-black text-[#2D5A4C] tracking-tight leading-none block">Act4Climate</span>
                    <span class="text-[8px] font-black text-gray-400 uppercase tracking-[0.18em] mt-1.5 block">ADMIN CONSOLE</span>
                </div>
            </a>
            
            {{-- Admin Navigation Links (Mockup visual layout) --}}
            <nav class="w-full space-y-4">
                <a href="{{ route('admin.dashboard') }}" class="flex justify-center items-center w-full py-3.5 px-4 rounded-full font-bold text-sm tracking-wide transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#2D5A4C] text-white shadow-sm' : 'text-[#2D5A4C] hover:bg-gray-50' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.communities') }}" class="flex justify-center items-center w-full py-3.5 px-4 rounded-full font-bold text-sm tracking-wide transition-all {{ request()->routeIs('admin.communities*') ? 'bg-[#2D5A4C] text-white shadow-sm' : 'text-[#2D5A4C] hover:bg-gray-50' }}">
                    Community
                </a>
                <a href="{{ route('admin.blogs.index') }}" class="flex justify-center items-center w-full py-3.5 px-4 rounded-full font-bold text-sm tracking-wide transition-all {{ request()->routeIs('admin.blogs*') ? 'bg-[#2D5A4C] text-white shadow-sm' : 'text-[#2D5A4C] hover:bg-gray-50' }}">
                    Blogs
                </a>
                <a href="{{ route('admin.quizzes') }}" class="flex justify-center items-center w-full py-3.5 px-4 rounded-full font-bold text-sm tracking-wide transition-all {{ request()->routeIs('admin.quizzes*') ? 'bg-[#2D5A4C] text-white shadow-sm' : 'text-[#2D5A4C] hover:bg-gray-50' }}">
                    Quiz
                </a>
                <a href="{{ route('admin.users') }}" class="flex justify-center items-center w-full py-3.5 px-4 rounded-full font-bold text-sm tracking-wide transition-all {{ request()->routeIs('admin.users*') ? 'bg-[#2D5A4C] text-white shadow-sm' : 'text-[#2D5A4C] hover:bg-gray-50' }}">
                    User Management
                </a>
            </nav>
            
            {{-- Back to App & Profile Widget at the bottom --}}
            <div class="w-full mt-auto pt-4 border-t border-gray-100 flex flex-col gap-2">

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-2 px-4 rounded-xl font-bold text-xs text-red-500 hover:bg-red-50 transition-all">
                        Logout
                    </button>
                </form>
            </div>
            
        @else
            {{-- User Side Bar Logo --}}
            @if(request()->has('preview'))
                <div class="flex items-center justify-center w-full py-4 mb-10 bg-white rounded-[20px] shadow-[0_4px_24px_-4px_rgba(0,0,0,0.08)] border border-gray-50 opacity-80 select-none">
                    <x-application-logo class="scale-90" />
                </div>
            @else
                <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-full py-4 mb-10 bg-white rounded-[20px] shadow-[0_4px_24px_-4px_rgba(0,0,0,0.08)] border border-gray-50">
                    <x-application-logo class="scale-90" />
                </a>
            @endif

            {{-- User Navigation Links --}}
            <nav class="flex-1 w-full space-y-4">
                @if(request()->has('preview'))
                    <a href="javascript:void(0)" class="flex justify-center items-center w-full py-3.5 px-4 rounded-full font-bold text-gray-400 opacity-50 cursor-not-allowed pointer-events-none">
                        Dashboard
                    </a>
                    <a href="javascript:void(0)" class="flex justify-center items-center w-full py-3.5 px-4 rounded-full font-bold text-gray-400 opacity-50 cursor-not-allowed pointer-events-none">
                        Activity
                    </a>
                    <a href="javascript:void(0)" class="flex justify-center items-center w-full py-3.5 px-4 rounded-full font-bold bg-[#2A5C4D] text-white pointer-events-none">
                        Community
                    </a>
                    <a href="javascript:void(0)" class="flex justify-center items-center w-full py-3.5 px-4 rounded-full font-bold text-gray-400 opacity-50 cursor-not-allowed pointer-events-none">
                        Blogs
                    </a>
                @else
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
                @endif

                @if(Auth::user()->isAdmin())
                    <div class="pt-4 mt-4 border-t border-gray-100 w-full"></div>
                    <a href="{{ route('admin.dashboard') }}" class="flex justify-center items-center w-full py-3.5 px-4 rounded-full font-bold text-xs text-amber-600 bg-amber-50 hover:bg-amber-100 border border-amber-200 transition">
                        🛡️ Admin Panel
                    </a>
                @endif
            </nav>
            
            {{-- User Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="w-full mt-auto pt-4 border-t border-gray-100">
                @csrf
                <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-full font-bold text-red-500 hover:bg-red-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        @endif
    </div>
</aside>