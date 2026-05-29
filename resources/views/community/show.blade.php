<x-app-layout>
    <x-slot name="title">{{ $community->name }}</x-slot>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-content">{{ $community->name }}</h1>
            <p class="text-sm text-content-muted">{{ $community->members_count ?? $community->member_count }} members</p>
        </div>
        @if(!$isMember)
        <form method="POST" action="{{ route('community.join', $community) }}">@csrf <button class="btn-primary text-sm">Join Community</button></form>
        @else
        <form method="POST" action="{{ route('community.leave', $community) }}">@csrf <button class="btn-ghost text-red-500 text-sm border border-red-200 rounded-full px-4 py-1.5 hover:bg-red-50">Leave Community</button></form>
        @endif
    </x-slot>
    
    <div class="max-w-[1200px] mx-auto animate-fade-in relative flex flex-col lg:flex-row gap-8 items-start mt-6">
        
        <!-- Main Feed Column -->
        <div class="flex-1 w-full flex flex-col gap-8 pb-32">
            
            @forelse($posts as $post)
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-6 flex flex-col md:flex-row gap-6 items-stretch" id="post-{{ $post->id }}">
                <!-- Post Content (Left Side) -->
                <div class="flex-1 flex flex-col min-w-0">
                    <!-- Author Header -->
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 border border-gray-100">
                            <img src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($post->user->name).'&background=E2E8F0&color=2A5C4D' }}" alt="{{ $post->user->username }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-bold text-[#1E293B] text-[15px] leading-tight">{{ '@'.$post->user->username }}</p>
                            <p class="text-[11px] font-medium text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    
                    <!-- Post Body -->
                    <div class="mb-4">
                        @if($post->image)
                        <div class="mb-4 rounded-2xl overflow-hidden bg-gray-50 border border-gray-100 flex justify-center">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="max-w-full h-auto max-h-[500px] object-contain">
                        </div>
                        @endif
                        <p class="text-[15px] text-[#334155] leading-relaxed break-words whitespace-pre-line">{{ $post->content }}</p>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-auto pt-4 flex items-center gap-4 text-gray-500">
                        <!-- Like -->
                        <form method="POST" action="{{ route('posts.like', $post) }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-1.5 hover:text-[#2D5A4C] transition {{ in_array($post->id, $likedPostIds) ? 'text-[#2D5A4C]' : '' }}">
                                <svg class="w-[22px] h-[22px]" fill="{{ in_array($post->id, $likedPostIds) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                </svg>
                                <span class="text-sm font-bold">{{ $post->likes_count > 0 ? $post->likes_count : '' }}</span>
                            </button>
                        </form>
                        
                        <!-- Save (Bookmark) -->
                        <form method="POST" action="{{ route('posts.save', $post) }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-1.5 hover:text-[#2D5A4C] transition {{ in_array($post->id, $savedPostIds) ? 'text-[#2D5A4C]' : '' }}">
                                <svg class="w-[22px] h-[22px]" fill="{{ in_array($post->id, $savedPostIds) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                            </button>
                        </form>
                        
                        <!-- Share -->
                        <div x-data="{ copied: false }" class="relative">
                            <button type="button" @click="navigator.clipboard.writeText('{{ route('community.show', $community) }}#post-{{ $post->id }}'); copied = true; setTimeout(() => copied = false, 2000);" class="flex items-center gap-1.5 hover:text-[#2D5A4C] transition ml-1">
                                <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                </svg>
                            </button>
                            <span x-show="copied" style="display: none;" class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2.5 py-1 text-[10px] font-bold text-white bg-slate-900 rounded-md shadow-sm whitespace-nowrap z-30 transition-all duration-300">Link copied!</span>
                        </div>
                    </div>
                </div>
                
                <!-- Comments Section (Right Side) -->
                <div class="w-full md:w-[320px] shrink-0 border border-gray-200 rounded-[28px] overflow-hidden flex flex-col bg-[#fdfdfd] relative min-h-[300px] max-h-[450px]">
                    <!-- Comments List -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar">
                        @forelse($post->comments as $comment)
                        <div class="flex gap-2.5">
                            <div class="w-6 h-6 rounded-full overflow-hidden shrink-0 border border-gray-100 bg-white">
                                <img src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&background=E2E8F0&color=2A5C4D' }}" alt="" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5 justify-between">
                                    <p class="font-bold text-[#1E293B] text-[12px] truncate">{{ '@'.$comment->user->username }}</p>
                                    <div class="flex gap-2 text-gray-400">
                                        @if($comment->user_id === Auth::id() || Auth::user()->isAdmin())
                                        <form method="POST" action="{{ route('comments.destroy', $comment) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="hover:text-red-500 ml-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-[11px] text-gray-600 mt-0.5 break-words">{{ $comment->content }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="h-full flex items-center justify-center text-center px-4">
                            <p class="text-xs text-gray-400 italic">No comments yet. Be the first to reply!</p>
                        </div>
                        @endforelse
                    </div>
                    
                    <!-- Comment Input -->
                    @if($isMember)
                    <div class="border-t border-gray-200 p-3 bg-white">
                        <form method="POST" action="{{ route('posts.comments.store', $post) }}">
                            @csrf
                            <input type="text" name="content" required placeholder="Comment Here..." class="w-full bg-transparent border-0 text-[12px] focus:ring-0 px-1 py-1 placeholder-gray-400" autocomplete="off">
                            <div class="flex items-center justify-between px-1 mt-2">
                                <div class="flex items-center gap-2 text-gray-400">
                                    <button type="button" class="hover:text-[#2D5A4C]"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg></button>
                                </div>
                                <button type="submit" class="text-[#2D5A4C] hover:text-[#1e4237]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                    @else
                    <div class="border-t border-gray-200 p-4 text-center bg-gray-50">
                        <p class="text-xs text-gray-500">Join the community to comment.</p>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-16 bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col items-center justify-center">
                <span class="text-5xl mb-4">🌱</span>
                <p class="text-gray-500 font-medium">No posts in this community yet.</p>
                @if($isMember)
                <p class="text-gray-400 text-sm mt-1">Be the first to start the discussion!</p>
                @endif
            </div>
            @endforelse
            
            <div class="mt-2">{{ $posts->links() }}</div>

        </div>
        
        <!-- Right Sidebar (Fixed on Desktop) -->
        <div class="hidden lg:block w-[280px] shrink-0 sticky top-32">
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-6 mb-6">
                
                <!-- Online Users -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-[12px] font-bold text-gray-400 uppercase tracking-wider">Online</h4>
                        <span class="w-4 h-4 rounded-full bg-gray-100 text-gray-500 text-[9px] font-bold flex items-center justify-center">+{{ $onlineUsers->count() + 2 }}</span>
                    </div>
                </div>
                
                <!-- Member Community -->
                <div class="mb-6">
                    <h4 class="text-[12px] font-bold text-gray-400 uppercase tracking-wider mb-3">Community Members</h4>
                    <div class="space-y-3">
                        @forelse($memberCommunity as $u)
                        <div class="flex items-center gap-2.5">
                            <div class="w-6 h-6 rounded-full overflow-hidden shrink-0 border border-gray-100">
                                <img src="{{ $u->avatar ? asset('storage/' . $u->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=E2E8F0&color=2A5C4D' }}" alt="" class="w-full h-full object-cover">
                            </div>
                            <p class="text-[13px] font-bold text-[#1E293B] truncate">{{ '@'.$u->username }}</p>
                        </div>
                        @empty
                        <p class="text-xs text-gray-400 italic">No other members yet.</p>
                        @endforelse
                    </div>
                </div>
                
                <!-- Friends -->
                <div>
                    <h4 class="text-[12px] font-bold text-gray-400 uppercase tracking-wider mb-3">Friends</h4>
                    <div class="space-y-3">
                        @foreach($friends as $u)
                        <div class="flex items-center gap-2.5">
                            <div class="w-6 h-6 rounded-full overflow-hidden shrink-0 border border-gray-100">
                                <img src="{{ $u->avatar ? asset('storage/' . $u->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=E2E8F0&color=2A5C4D' }}" alt="" class="w-full h-full object-cover">
                            </div>
                            <p class="text-[13px] font-bold text-[#1E293B] truncate">{{ '@'.$u->username }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Sticky Bottom Posting Widget -->
        @if($isMember)
        <div class="fixed bottom-6 left-0 right-0 z-40 sm:ml-[280px] pointer-events-none px-8">
            <div class="max-w-[880px] mx-auto pointer-events-auto">
                <div class="bg-white/90 backdrop-blur-xl rounded-[24px] border border-gray-200/50 shadow-[0_8px_30px_rgb(0,0,0,0.08)] p-4 pr-5 transition-all focus-within:shadow-[0_8px_40px_rgb(45,90,76,0.15)] focus-within:bg-white focus-within:border-[#2D5A4C]/30">
                    <form method="POST" action="{{ route('community.posts.store', $community) }}" enctype="multipart/form-data" class="flex flex-col gap-3">
                        @csrf
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full overflow-hidden shrink-0 border border-gray-100 bg-gray-50">
                                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=E2E8F0&color=2A5C4D' }}" alt="" class="w-full h-full object-cover">
                            </div>
                            <input type="text" name="content" required placeholder="Discuss here or Mention someone..." class="flex-1 bg-transparent border-0 text-[15px] focus:ring-0 px-2 py-2 placeholder-gray-400 font-medium w-full">
                        </div>
                        
                        <!-- Image Preview Area -->
                        <div id="post-image-preview" class="hidden relative ml-12 rounded-xl overflow-hidden border border-gray-100 max-h-[150px] inline-block bg-gray-50">
                            <img src="" id="preview-img" class="h-full object-contain">
                            <button type="button" onclick="document.getElementById('post-image').value=''; document.getElementById('post-image-preview').classList.add('hidden');" class="absolute top-2 right-2 w-6 h-6 bg-black/50 hover:bg-black/70 text-white rounded-full flex items-center justify-center transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <div class="flex items-center justify-between ml-12 border-t border-gray-100 pt-3">
                            <div class="flex items-center gap-2 text-gray-500">
                                <label class="w-9 h-9 rounded-full hover:bg-gray-100 flex items-center justify-center cursor-pointer transition text-[#2D5A4C]">
                                    <input type="file" name="image" id="post-image" class="hidden" accept="image/*" onchange="if(this.files[0]){document.getElementById('post-image-preview').classList.remove('hidden'); document.getElementById('preview-img').src = URL.createObjectURL(this.files[0]);}else{document.getElementById('post-image-preview').classList.add('hidden');}">
                                    <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </label>
                                <button type="button" class="w-9 h-9 rounded-full hover:bg-gray-100 flex items-center justify-center transition text-[#2D5A4C]">
                                    <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                </button>
                                <button type="button" class="w-9 h-9 rounded-full hover:bg-gray-100 flex items-center justify-center transition text-[#2D5A4C]">
                                    <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                                </button>
                            </div>
                            
                            <button type="submit" class="bg-[#2D5A4C] hover:bg-[#1e4237] text-white px-6 py-2.5 rounded-full font-bold text-[13px] tracking-wide transition shadow-sm hover:shadow active:scale-[0.97]">
                                Posting
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
        
    </div>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #e2e8f0;
            border-radius: 10px;
        }
    </style>
</x-app-layout>
