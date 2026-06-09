<x-app-layout>
    <x-slot name="title">{{ $community->name }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            {{-- Back Button --}}
            <button onclick="history.back()"
                class="w-9 h-9 rounded-full bg-white border border-gray-200 shadow-sm flex items-center justify-center text-[#2D5A4C] hover:bg-[#2D5A4C]/5 hover:border-[#2D5A4C]/30 transition shrink-0"
                title="Back">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <div>
                <h1 class="text-xl font-bold text-content">{{ $community->name }}</h1>
                <p class="text-sm text-content-muted">{{ $community->members_count ?? $community->member_count }} members</p>
            </div>
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
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-6 flex flex-col md:flex-row gap-6 items-stretch" id="post-{{ $post->id }}" x-data="{ editing: false, content: {{ Js::from($post->content) }}, originalContent: {{ Js::from($post->content) }} }">
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
                        
                        @if(!request()->has('preview') && ($post->user_id === Auth::id() || Auth::user()->isAdmin()))
                        <div class="flex items-center gap-1">
                            @if($post->user_id === Auth::id())
                            <button type="button" @click="editing = true; setTimeout(() => document.getElementById('edit-post-content-{{ $post->id }}').focus(), 50)" class="w-8 h-8 rounded-full hover:bg-gray-50 text-gray-400 hover:text-[#2D5A4C] flex items-center justify-center transition" title="Edit post" x-show="!editing">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </button>
                            @endif

                            <form method="POST" action="{{ route('posts.destroy', $post) }}" data-confirm="Are you sure you want to delete this post?" x-show="!editing">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-full hover:bg-red-50 text-gray-400 hover:text-red-500 flex items-center justify-center transition" title="Delete post">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Post Body -->
                    <div class="mb-4">
                        @if($post->image)
                        <div class="mb-4 rounded-2xl overflow-hidden bg-gray-50 border border-gray-100 flex justify-center">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" class="max-w-full h-auto max-h-[500px] object-contain">
                        </div>
                        @endif
                        
                        <div x-show="!editing">
                            <p class="text-[15px] text-[#334155] leading-relaxed break-words whitespace-pre-line" x-text="content">{{ $post->content }}</p>
                        </div>

                        @if($post->user_id === Auth::id())
                        <div x-show="editing" style="display: none;" class="mt-2">
                            <form method="POST" action="{{ route('posts.update', $post) }}">
                                @csrf
                                @method('PUT')
                                <textarea name="content" id="edit-post-content-{{ $post->id }}" x-model="content" required rows="3" class="w-full rounded-2xl border-gray-200 focus:border-[#2D5A4C] focus:ring-[#2D5A4C] text-[15px] p-3 shadow-sm placeholder-gray-400 font-medium" placeholder="Edit your post..."></textarea>
                                <div class="flex items-center justify-end gap-2 mt-2">
                                    <button type="button" @click="editing = false; content = originalContent" class="px-4 py-1.5 rounded-full border border-gray-200 text-gray-500 hover:bg-gray-50 font-bold text-[12px] transition">Cancel</button>
                                    <button type="submit" class="px-4 py-1.5 rounded-full bg-[#2D5A4C] hover:bg-[#1e4237] text-white font-bold text-[12px] transition">Save</button>
                                </div>
                            </form>
                        </div>
                        @endif
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

                    </div>
                </div>
                
                <!-- Comments Section (Right Side) -->
                <div class="w-full md:w-[320px] shrink-0 border border-gray-200 rounded-[28px] overflow-hidden flex flex-col bg-[#fdfdfd] relative min-h-[300px] max-h-[450px]">
                    <!-- Comments List -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
                        @forelse($post->comments as $comment)

                        {{-- Top-level comment --}}
                        <div class="comment-thread" id="comment-{{ $comment->id }}">
                            <div class="flex gap-2.5">
                                <div class="w-6 h-6 rounded-full overflow-hidden shrink-0 border border-gray-100 bg-white mt-0.5">
                                    <img src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&background=E2E8F0&color=2A5C4D' }}" alt="" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="bg-gray-50 rounded-2xl px-3 py-2">
                                        <div class="flex items-center gap-1.5 justify-between mb-0.5">
                                            <p class="font-bold text-[#1E293B] text-[11px]">{{ '@'.$comment->user->username }}</p>
                                            @if($comment->user_id === Auth::id() || Auth::user()->isAdmin())
                                            <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-gray-300 hover:text-red-400 transition">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-gray-600 break-words leading-relaxed">{{ $comment->content }}</p>
                                    </div>
                                    {{-- Reply button --}}
                                    @if($isMember)
                                    <button type="button"
                                        onclick="toggleReplyInput({{ $comment->id }})"
                                        class="mt-1 ml-1 text-[10px] font-bold text-[#2D5A4C] hover:text-[#1e4237] transition flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6M3 10l6-6"/></svg>
                                        Reply
                                    </button>
                                    @endif
                                </div>
                            </div>

                            {{-- Nested Replies --}}
                            @if($comment->replies->count() > 0)
                            <div class="mt-2 ml-8 space-y-2">
                                @foreach($comment->replies as $reply)
                                <div class="flex gap-2" id="comment-{{ $reply->id }}">
                                    <div class="w-5 h-5 rounded-full overflow-hidden shrink-0 border border-gray-100 bg-white mt-0.5">
                                        <img src="{{ $reply->user->avatar ? asset('storage/' . $reply->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($reply->user->name).'&background=E2E8F0&color=2A5C4D' }}" alt="" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="bg-[#f0faf6] border border-[#2D5A4C]/10 rounded-2xl px-3 py-2">
                                            <div class="flex items-center gap-1.5 justify-between mb-0.5">
                                                <p class="font-bold text-[#2D5A4C] text-[10px]">{{ '@'.$reply->user->username }}</p>
                                                @if($reply->user_id === Auth::id() || Auth::user()->isAdmin())
                                                <form method="POST" action="{{ route('comments.destroy', $reply) }}" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-gray-300 hover:text-red-400 transition">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                            <p class="text-[10px] text-gray-600 break-words leading-relaxed">{{ $reply->content }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            {{-- Inline Reply Input (hidden by default) --}}
                            @if($isMember)
                            <div id="reply-input-{{ $comment->id }}" class="hidden mt-2 ml-8">
                                <form method="POST" action="{{ route('posts.comments.store', $post) }}">
                                    @csrf
                                    <input type="hidden" name="parent_comment_id" value="{{ $comment->id }}">
                                    <div class="flex items-center gap-2 bg-white border border-[#2D5A4C]/20 rounded-2xl px-3 py-1.5 shadow-sm focus-within:border-[#2D5A4C]/50 focus-within:shadow-md transition-all">
                                        <input type="text" name="content"
                                            id="reply-text-{{ $comment->id }}"
                                            required
                                            placeholder="Reply to @{{ $comment->user->username }}..."
                                            class="flex-1 bg-transparent border-0 text-[11px] focus:ring-0 py-0.5 placeholder-gray-400 min-w-0">
                                        <div class="flex items-center gap-1 shrink-0">
                                            <button type="button"
                                                onclick="insertMentionInReply({{ $comment->id }})"
                                                class="w-5 h-5 rounded-full hover:bg-[#2D5A4C]/10 flex items-center justify-center text-[#2D5A4C] font-bold text-[11px] leading-none select-none transition"
                                                title="Mention someone">@</button>
                                            <button type="submit"
                                                class="w-5 h-5 rounded-full bg-[#2D5A4C] hover:bg-[#1e4237] flex items-center justify-center text-white transition">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @endif
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
                            <div class="flex items-center gap-2 bg-gray-50 rounded-2xl px-3 py-1.5 focus-within:bg-white focus-within:shadow-sm transition-all">
                                <input type="text" name="content"
                                    id="comment-input-{{ $post->id }}"
                                    required
                                    placeholder="Comment here..."
                                    class="flex-1 bg-transparent border-0 text-[12px] focus:ring-0 py-0.5 placeholder-gray-400 min-w-0"
                                    autocomplete="off">
                                <div class="flex items-center gap-1 shrink-0">
                                    <button type="button"
                                        onclick="insertMentionInComment({{ $post->id }})"
                                        class="w-6 h-6 rounded-full hover:bg-[#2D5A4C]/10 flex items-center justify-center text-[#2D5A4C] font-bold text-[12px] leading-none select-none transition"
                                        title="Mention someone">@</button>
                                    <button type="submit" class="text-[#2D5A4C] hover:text-[#1e4237] transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </button>
                                </div>
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
            <script>
                window.communitySidebarData = {
                    online: {!! json_encode($onlineMembers->map(fn($u) => ['username' => $u->username, 'name' => $u->name, 'avatar_url' => $u->avatar ? asset('storage/' . $u->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=E2E8F0&color=2A5C4D'])) !!},
                    all: {!! json_encode($allMembers->map(fn($u) => ['username' => $u->username, 'name' => $u->name, 'avatar_url' => $u->avatar ? asset('storage/' . $u->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=E2E8F0&color=2A5C4D', 'is_online' => $u->isOnline()])) !!}
                };
            </script>
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-6 mb-6"
                 x-data="communitySidebar('{{ route('community.sidebar', $community) }}', window.communitySidebarData.online, window.communitySidebarData.all)">
                
                <!-- Online Members -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-[12px] font-bold text-gray-400 uppercase tracking-wider">Online</h4>
                        <span class="px-2.5 py-0.5 rounded-full bg-green-50 text-green-600 text-[10px] font-black flex items-center justify-center shadow-sm" x-text="onlineMembers.length">0</span>
                    </div>
                    <div class="space-y-3 max-h-[200px] overflow-y-auto custom-scrollbar pr-1">
                        <template x-for="u in onlineMembers" :key="u.username">
                            <div class="flex items-center gap-2.5">
                                <div class="relative w-8 h-8 shrink-0">
                                    <div class="w-8 h-8 rounded-full overflow-hidden border border-gray-100 bg-white">
                                        <img :src="u.avatar_url" alt="" class="w-full h-full object-cover">
                                    </div>
                                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-400 ring-2 ring-white"></span>
                                </div>
                                <p class="text-[13px] font-bold text-[#1E293B] truncate" x-text="'@' + u.username"></p>
                            </div>
                        </template>
                        <div x-show="onlineMembers.length === 0" class="text-xs text-gray-400 italic py-1">
                            No other members online.
                        </div>
                    </div>
                </div>
                
                <!-- All Community Members -->
                <div>
                    <h4 class="text-[12px] font-bold text-gray-400 uppercase tracking-wider mb-3">Members</h4>
                    <div class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar pr-1">
                        <template x-for="u in allMembers" :key="u.username">
                            <div class="flex items-center gap-2.5">
                                <div class="relative w-8 h-8 shrink-0">
                                    <div class="w-8 h-8 rounded-full overflow-hidden border border-gray-100 bg-white">
                                        <img :src="u.avatar_url" alt="" class="w-full h-full object-cover">
                                    </div>
                                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white"
                                          :class="u.is_online ? 'bg-green-400' : 'bg-gray-300'"
                                          :title="u.is_online ? 'Online' : 'Offline'"></span>
                                </div>
                                <p class="text-[13px] font-bold text-[#1E293B] truncate" x-text="'@' + u.username"></p>
                            </div>
                        </template>
                        <div x-show="allMembers.length === 0" class="text-xs text-gray-400 italic py-1">
                            No members yet.
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Sticky Bottom Posting Widget -->
        @if($isMember)
        <div class="fixed bottom-6 left-0 right-0 z-40 sm:ml-[280px] pointer-events-none px-8">
            <div class="max-w-[880px] mx-auto pointer-events-auto">
                <div class="bg-white/90 backdrop-blur-xl rounded-[24px] border border-gray-200/50 shadow-[0_8px_30px_rgb(0,0,0,0.08)] p-4 pr-5 transition-all focus-within:shadow-[0_8px_40px_rgb(45,90,76,0.15)] focus-within:bg-white focus-within:border-[#2D5A4C]/30">
                    <form method="POST" action="{{ route('community.posts.store', $community) }}" enctype="multipart/form-data" id="post-form" class="flex flex-col gap-3">
                        @csrf
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full overflow-hidden shrink-0 border border-gray-100 bg-gray-50">
                                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=E2E8F0&color=2A5C4D' }}" alt="" class="w-full h-full object-cover">
                            </div>
                            <input type="text" name="content" id="post-content-input" required placeholder="Discuss here or Mention someone..." class="flex-1 bg-transparent border-0 text-[15px] focus:ring-0 px-2 py-2 placeholder-gray-400 font-medium w-full">
                        </div>

                        <!-- Media Preview Area -->
                        <div id="post-media-preview" class="hidden relative ml-12 rounded-xl overflow-hidden border border-gray-100 max-h-[150px] inline-block bg-gray-50">
                            <img src="" id="preview-img" class="h-full object-contain">
                            <video id="preview-video" class="h-full object-contain hidden" controls></video>
                            <button type="button" onclick="clearMediaPreview()" class="absolute top-2 right-2 w-6 h-6 bg-black/50 hover:bg-black/70 text-white rounded-full flex items-center justify-center transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <!-- Hidden file inputs -->
                        <input type="file" name="image" id="post-photo-input" class="hidden" accept="image/*" onchange="handleMediaSelect(this, 'photo')">
                        <input type="file" name="video" id="post-video-input" class="hidden" accept="video/*" onchange="handleMediaSelect(this, 'video')">

                        <div class="flex items-center justify-between ml-12 border-t border-gray-100 pt-3">
                            <div class="flex items-center gap-1.5 text-gray-500">

                                <!-- "+" button with dropdown -->
                                <div class="relative" id="upload-menu-wrapper">
                                    <button type="button" id="upload-plus-btn"
                                        onclick="toggleUploadMenu()"
                                        class="w-9 h-9 rounded-full hover:bg-[#2D5A4C]/10 flex items-center justify-center transition-all duration-200 text-[#2D5A4C] font-bold text-xl leading-none select-none"
                                        title="Upload media">
                                        +
                                    </button>

                                    <!-- Dropdown menu -->
                                    <div id="upload-dropdown"
                                        class="upload-dropdown-hidden absolute bottom-full left-0 mb-2 bg-white rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.12)] border border-gray-100 overflow-hidden z-50 min-w-[160px]">
                                        <button type="button" onclick="document.getElementById('post-photo-input').click(); toggleUploadMenu();"
                                            class="flex items-center gap-3 w-full px-4 py-3 text-[13px] font-semibold text-gray-700 hover:bg-[#2D5A4C]/8 hover:text-[#2D5A4C] transition-colors duration-150">
                                            <span class="text-base">📷</span> Upload Foto
                                        </button>
                                        <div class="h-px bg-gray-100 mx-3"></div>
                                        <button type="button" onclick="document.getElementById('post-video-input').click(); toggleUploadMenu();"
                                            class="flex items-center gap-3 w-full px-4 py-3 text-[13px] font-semibold text-gray-700 hover:bg-[#2D5A4C]/8 hover:text-[#2D5A4C] transition-colors duration-150">
                                            <span class="text-base">🎥</span> Upload Video
                                        </button>
                                    </div>
                                </div>

                                <!-- Hashtag "#" button -->
                                <button type="button" onclick="insertHashtag()" title="Add hashtag"
                                    class="w-9 h-9 rounded-full hover:bg-[#2D5A4C]/10 flex items-center justify-center transition text-[#2D5A4C] font-bold text-[15px] leading-none select-none">
                                    #
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
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #e2e8f0; border-radius: 10px; }

        /* Upload dropdown animation */
        .upload-dropdown-hidden {
            opacity: 0; transform: translateY(6px) scale(0.97); pointer-events: none;
            transition: opacity 0.18s ease, transform 0.18s ease;
        }
        .upload-dropdown-visible {
            opacity: 1; transform: translateY(0) scale(1); pointer-events: auto;
            transition: opacity 0.18s ease, transform 0.18s ease;
        }

        /* Autocomplete suggestion popup — floats via JS (position:fixed on body) */
        .ac-popup {
            min-width: 200px;
            max-width: 300px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.13), 0 2px 8px rgba(0,0,0,0.06);
            z-index: 99999;
            overflow: hidden;
            animation: acFadeIn 0.13s ease;
        }
        @keyframes acFadeIn {
            from { opacity: 0; transform: translateY(4px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .ac-popup .ac-item {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 14px; cursor: pointer; font-size: 12px;
            color: #1e293b; transition: background 0.1s;
        }
        .ac-popup .ac-item:hover, .ac-popup .ac-item.active {
            background: #f0faf6; color: #2D5A4C;
        }
        .ac-popup .ac-item .ac-avatar {
            width: 24px; height: 24px; border-radius: 50%; object-fit: cover;
            border: 1px solid #e5e7eb; flex-shrink: 0;
        }
        .ac-popup .ac-item .ac-username { font-weight: 700; }
        .ac-popup .ac-item .ac-name { color: #94a3b8; font-size: 10px; }
        .ac-popup .ac-item .ac-hash { font-weight: 700; color: #2D5A4C; }
        .ac-popup .ac-item .ac-count { color: #94a3b8; font-size: 10px; margin-left: auto; }
        .ac-popup .ac-header {
            padding: 6px 14px 4px; font-size: 9px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8;
            border-bottom: 1px solid #f1f5f9;
        }
    </style>
    
    @push('scripts')
    <script>
        // ── Config (injected from Blade) ─────────────────────────────────
        const HASHTAG_URL = '{{ route("community.hashtags", $community) }}';
        const MEMBERS_URL = '{{ route("community.members", $community) }}';

        // ── Community Sidebar Alpine component ───────────────────────────
        function communitySidebar(url, initialOnline, initialAll) {
            return {
                onlineMembers: initialOnline || [],
                allMembers: initialAll || [],
                init() { setInterval(() => this.fetchData(), 10000); },
                async fetchData() {
                    try {
                        const res = await fetch(url);
                        const data = await res.json();
                        this.onlineMembers = data.onlineMembers;
                        this.allMembers = data.allMembers;
                    } catch (e) { console.error('sidebar fetch failed', e); }
                }
            };
        }

        // ── Upload dropdown ──────────────────────────────────────────────
        let uploadMenuOpen = false;
        function toggleUploadMenu() {
            const dropdown = document.getElementById('upload-dropdown');
            uploadMenuOpen = !uploadMenuOpen;
            dropdown.classList.toggle('upload-dropdown-visible', uploadMenuOpen);
            dropdown.classList.toggle('upload-dropdown-hidden', !uploadMenuOpen);
        }
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('upload-menu-wrapper');
            if (wrapper && !wrapper.contains(e.target) && uploadMenuOpen) {
                uploadMenuOpen = false;
                const dropdown = document.getElementById('upload-dropdown');
                dropdown.classList.remove('upload-dropdown-visible');
                dropdown.classList.add('upload-dropdown-hidden');
            }
        });

        // ── Media preview ────────────────────────────────────────────────
        function handleMediaSelect(input, type) {
            const file = input.files[0];
            if (!file) return;
            const previewArea = document.getElementById('post-media-preview');
            const previewImg  = document.getElementById('preview-img');
            const previewVid  = document.getElementById('preview-video');
            previewArea.classList.remove('hidden');
            const url = URL.createObjectURL(file);
            if (type === 'photo') {
                previewImg.src = url; previewImg.classList.remove('hidden');
                previewVid.classList.add('hidden'); previewVid.src = '';
                document.getElementById('post-video-input').value = '';
            } else {
                previewVid.src = url; previewVid.classList.remove('hidden');
                previewImg.classList.add('hidden'); previewImg.src = '';
                document.getElementById('post-photo-input').value = '';
            }
        }
        function clearMediaPreview() {
            document.getElementById('post-photo-input').value = '';
            document.getElementById('post-video-input').value = '';
            document.getElementById('preview-img').src = '';
            document.getElementById('preview-video').src = '';
            document.getElementById('preview-img').classList.add('hidden');
            document.getElementById('preview-video').classList.add('hidden');
            document.getElementById('post-media-preview').classList.add('hidden');
        }

        // ── Reply toggler ────────────────────────────────────────────────
        function toggleReplyInput(commentId) {
            const box = document.getElementById('reply-input-' + commentId);
            if (!box) return;
            const isHidden = box.classList.contains('hidden');
            document.querySelectorAll('[id^="reply-input-"]').forEach(el => el.classList.add('hidden'));
            if (isHidden) {
                box.classList.remove('hidden');
                const input = document.getElementById('reply-text-' + commentId);
                if (input) setTimeout(() => input.focus(), 50);
            }
        }

        // ── Base floating autocomplete ────────────────────────────────────
        /**
         * FloatingAC: renders a popup appended directly to <body> so it floats
         * above all overflow:hidden containers and scroll boxes.
         * Uses pointerdown (not mousedown) so the pick fires BEFORE blur.
         */
        class FloatingAC {
            constructor(input, { triggerChar, fetchUrl, buildHeader, buildItem, pickFn }) {
                this.input      = input;
                this.triggerChar = triggerChar;
                this.fetchUrl   = fetchUrl;
                this.buildHeader = buildHeader;
                this.buildItem  = buildItem;
                this.pickFn     = pickFn;

                this.popup      = null;
                this.timer      = null;
                this.active     = -1;
                this.items      = [];
                this.triggerPos = -1;
                this._scrollLn  = null; // scroll listener for repositioning

                input.addEventListener('input',   () => this.onInput());
                input.addEventListener('keydown',  e  => this.onKey(e));
                // blur: close only if pointer is NOT inside popup
                input.addEventListener('blur', () => {
                    // Give pointerdown time to fire first
                    setTimeout(() => {
                        if (!this._pointerInsidePopup) this.close();
                    }, 120);
                });
            }

            onInput() {
                const val = this.input.value;
                const cur = this.input.selectionStart;
                let idx = -1;
                for (let i = cur - 1; i >= 0; i--) {
                    if (val[i] === this.triggerChar) { idx = i; break; }
                    if (val[i] === ' ' || val[i] === '\n') break;
                }
                if (idx === -1) { this.close(); return; }
                const query = val.slice(idx + 1, cur);
                this.triggerPos = idx;
                clearTimeout(this.timer);
                this.timer = setTimeout(() => this._fetch(query), 200);
            }

            async _fetch(q) {
                try {
                    const res = await fetch(this.fetchUrl + '?q=' + encodeURIComponent(q));
                    this.items = await res.json();
                    if (this.items.length) this._render();
                    else this.close();
                } catch { this.close(); }
            }

            _render() {
                this.close();
                this.active = -1;
                this._pointerInsidePopup = false;

                const popup = document.createElement('div');
                popup.className = 'ac-popup';
                popup.innerHTML = `<div class="ac-header">${this.buildHeader()}</div>`;

                this.items.forEach((item) => {
                    const el = document.createElement('div');
                    el.className = 'ac-item';
                    el.innerHTML = this.buildItem(item);
                    // pointerdown fires before blur — no race condition
                    el.addEventListener('pointerdown', (e) => {
                        e.preventDefault();
                        this._pointerInsidePopup = true;
                        this.pickFn(item, this);
                    });
                    popup.appendChild(el);
                });

                // Track pointer inside popup to guard blur
                popup.addEventListener('pointerenter', () => { this._pointerInsidePopup = true; });
                popup.addEventListener('pointerleave', () => { this._pointerInsidePopup = false; });

                document.body.appendChild(popup);
                this.popup = popup;
                this._positionPopup();

                // Reposition on scroll/resize
                this._scrollLn = () => this._positionPopup();
                window.addEventListener('scroll', this._scrollLn, true);
                window.addEventListener('resize', this._scrollLn);
            }

            _positionPopup() {
                if (!this.popup) return;
                const rect = this.input.getBoundingClientRect();
                const popupH = this.popup.offsetHeight || 200;
                // Prefer above the input; fall back to below if no room
                const spaceAbove = rect.top;
                const spaceBelow = window.innerHeight - rect.bottom;
                let top;
                if (spaceAbove >= popupH + 6 || spaceAbove > spaceBelow) {
                    // above
                    top = rect.top - popupH - 6 + window.scrollY;
                    if (window.scrollY === 0) top = rect.top - popupH - 6;
                } else {
                    // below
                    top = rect.bottom + 6;
                    if (window.scrollY === 0) top = rect.bottom + 6;
                }
                // Use fixed positioning so scroll inside containers doesn't matter
                this.popup.style.position  = 'fixed';
                this.popup.style.left      = rect.left + 'px';
                this.popup.style.top       = (spaceAbove >= popupH + 6 || spaceAbove > spaceBelow)
                    ? (rect.top - this.popup.offsetHeight - 6) + 'px'
                    : (rect.bottom + 6) + 'px';
                this.popup.style.width     = Math.max(rect.width, 200) + 'px';
                this.popup.style.maxWidth  = '300px';
                this.popup.style.zIndex    = '99999';
            }

            _pick(insertText) {
                const val    = this.input.value;
                const cur    = this.input.selectionStart;
                const before = val.slice(0, this.triggerPos);
                const after  = val.slice(cur);
                this.input.value = before + insertText + ' ' + after;
                const newPos = before.length + insertText.length + 1;
                this.input.setSelectionRange(newPos, newPos);
                this.input.focus();
                this._pointerInsidePopup = false;
                this.close();
            }

            onKey(e) {
                if (!this.popup) return;
                const items = this.popup.querySelectorAll('.ac-item');
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    this.active = Math.min(this.active + 1, items.length - 1);
                    this._highlight(items);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    this.active = Math.max(this.active - 1, 0);
                    this._highlight(items);
                } else if (e.key === 'Enter' && this.active >= 0) {
                    e.preventDefault();
                    this.pickFn(this.items[this.active], this);
                } else if (e.key === 'Escape') {
                    this.close();
                }
            }

            _highlight(items) {
                items.forEach((el, i) => el.classList.toggle('active', i === this.active));
                if (this.active >= 0) items[this.active]?.scrollIntoView({ block: 'nearest' });
            }

            close() {
                if (this.popup) {
                    this.popup.remove();
                    this.popup = null;
                }
                if (this._scrollLn) {
                    window.removeEventListener('scroll', this._scrollLn, true);
                    window.removeEventListener('resize', this._scrollLn);
                    this._scrollLn = null;
                }
                this._pointerInsidePopup = false;
            }
        }

        // ── Hashtag autocomplete ─────────────────────────────────────────
        class HashtagAC extends FloatingAC {
            constructor(input) {
                super(input, {
                    triggerChar: '#',
                    fetchUrl: HASHTAG_URL,
                    buildHeader: () => '# Hashtag suggestions',
                    buildItem: (h) => `<span class="ac-hash">#${h.name}</span><span class="ac-count">${h.usage_count || 0} posts</span>`,
                    pickFn: (item, ac) => ac._pick('#' + item.name),
                });
            }
        }

        // ── Mention autocomplete ─────────────────────────────────────────
        class MentionAC extends FloatingAC {
            constructor(input) {
                super(input, {
                    triggerChar: '@',
                    fetchUrl: MEMBERS_URL,
                    buildHeader: () => '@ Mention a member',
                    buildItem: (u) => `<img class="ac-avatar" src="${u.avatar_url}" alt=""><div><div class="ac-username">@${u.username}</div><div class="ac-name">${u.name}</div></div>`,
                    pickFn: (item, ac) => ac._pick('@' + item.username),
                });
            }
        }


        // ── Wire up autocomplete on DOM ready ────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            // Hashtag AC: posting bar only
            const postInput = document.getElementById('post-content-input');
            if (postInput) new HashtagAC(postInput);

            // Comment inputs: hashtag + mention
            document.querySelectorAll('[id^="comment-input-"]').forEach(el => {
                new HashtagAC(el);
                new MentionAC(el);
            });

            // Reply inputs: hashtag + mention
            document.querySelectorAll('[id^="reply-text-"]').forEach(el => {
                new HashtagAC(el);
                new MentionAC(el);
            });

            // Edit post textareas: hashtag + mention
            document.querySelectorAll('[id^="edit-post-content-"]').forEach(el => {
                new HashtagAC(el);
                new MentionAC(el);
            });
        });

        // ── Insert helpers (used by @ / # buttons) ───────────────────────
        function insertHashtag() {
            const input = document.getElementById('post-content-input');
            if (!input) return;
            insertChar(input, '#');
            // Trigger autocomplete immediately
            input.dispatchEvent(new Event('input'));
        }
        function insertMentionInComment(postId) {
            const input = document.getElementById('comment-input-' + postId);
            if (!input) return;
            insertChar(input, '@');
            input.dispatchEvent(new Event('input'));
        }
        function insertMentionInReply(commentId) {
            const input = document.getElementById('reply-text-' + commentId);
            if (!input) return;
            insertChar(input, '@');
            input.dispatchEvent(new Event('input'));
        }
        function insertChar(input, char) {
            const pos = input.selectionStart;
            const before = input.value.substring(0, pos);
            const after  = input.value.substring(pos);
            const prefix = (before.length > 0 && before[before.length - 1] !== ' ') ? ' ' + char : char;
            input.value = before + prefix + after;
            const newPos = pos + prefix.length;
            input.setSelectionRange(newPos, newPos);
            input.focus();
        }

        // Show session error or validation error if present
        @if($errors->any())
            window.addEventListener('DOMContentLoaded', () => {
                showCustomAlert('{{ $errors->first() }}', 'Peringatan');
            });
        @endif

        @if(session('success'))
            window.addEventListener('DOMContentLoaded', () => {
                showCustomAlert('{{ session('success') }}', 'Berhasil');
            });
        @endif
    </script>
    @endpush
</x-app-layout>
