<x-app-layout>
    <x-slot name="title">{{ $community->name }} · Community</x-slot>

    {{-- ─── Header ─── --}}
    <x-slot name="header">
        <div class="relative w-full max-w-[480px]">
            <span class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10"/>
                </svg>
            </span>
            <input type="text" placeholder="Search Forum"
                class="w-full pl-10 pr-10 py-2.5 bg-white border border-gray-200 rounded-full text-[14px] text-gray-700 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#2A5C4D]/20 focus:border-[#2A5C4D] transition-all">
            <span class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
        </div>

        <div class="flex items-center gap-3">
            {{-- Bell --}}
            <div class="relative">
                <button class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-[#2A5C4D] transition shadow-sm">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </button>
                <span class="absolute top-1 right-1 w-2 h-2 bg-[#2A5C4D] rounded-full border-2 border-white"></span>
            </div>

            {{-- Avatar --}}
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" class="flex items-center gap-1.5 bg-white rounded-full pl-1 pr-2.5 py-1 border border-gray-200 shadow-sm hover:shadow transition focus:outline-none">
                    <div class="w-8 h-8 rounded-full overflow-hidden shrink-0">
                        <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=2A5C4D&color=fff&bold=true' }}" class="w-full h-full object-cover">
                    </div>
                    <svg class="w-3.5 h-3.5 text-gray-400" :class="{'rotate-180':open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                     class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.12)] border border-gray-100 z-50 p-2.5"
                     style="display:none;">
                    <div class="px-3 py-2 mb-1 border-b border-gray-100">
                        <p class="text-xs font-bold text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">{{ '@'.Auth::user()->username }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-[13px] font-semibold text-gray-700 rounded-xl hover:bg-gray-50 transition">
                        <svg class="w-4 h-4 text-[#2A5C4D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 w-full px-3 py-2 text-[13px] font-semibold text-red-500 rounded-xl hover:bg-red-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- ─── Flash Message ─── --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0"
         class="fixed top-4 left-1/2 -translate-x-1/2 z-[999] flex items-center gap-2 bg-[#2D5A4C] text-white text-[13px] font-semibold px-5 py-2.5 rounded-2xl shadow-lg">
        <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- ════════════════════════════════════════
         MAIN 2-COLUMN LAYOUT: Feed + Sidebar
    ════════════════════════════════════════ --}}
    <div class="flex gap-5 items-start justify-center" id="forum-layout" style="padding-bottom: 150px;">


        {{-- ══════════════ FEED ══════════════ --}}
        <div class="flex-1 min-w-0 max-w-[880px] space-y-4" id="feed-container">

            {{-- ── Active hashtag filter banner ─────────────────── --}}
            @if($activeHashtag)
            <div class="flex items-center gap-3 bg-[#2D5A4C]/5 border border-[#2D5A4C]/20 rounded-2xl px-4 py-3">
                <span class="text-[#2D5A4C] font-bold text-[13px]">
                    <svg class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                    Menampilkan posting dengan hashtag <strong>#{{ $activeHashtag->name }}</strong>
                    &nbsp;&middot;&nbsp; {{ $posts->total() }} hasil
                </span>
                <a href="{{ route('community.show', $community) }}"
                   class="ml-auto flex items-center gap-1.5 text-[12px] font-semibold text-gray-500 hover:text-red-500 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Hapus filter
                </a>
            </div>
            @endif

            @forelse($posts as $post)

            {{-- ─── POST CARD ─── --}}
            {{-- ─── POST ITEM (CARD + COMMENTS) ─── --}}
            <div class="flex gap-4 items-stretch" id="post-{{ $post->id }}" style="animation: fadeUp .3s ease both; animation-delay: {{ $loop->index * 0.05 }}s">
                
                {{-- Post Content --}}
                <div class="flex-1 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col min-w-0">

                {{-- Header --}}
                <div class="px-4 py-3 flex items-center gap-2.5 border-b border-gray-50">
                    <div class="w-8 h-8 rounded-full overflow-hidden shrink-0 border border-gray-100">
                        <img src="{{ $post->user->avatar ? asset('storage/'.$post->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($post->user->name).'&background=E2E8F0&color=2A5C4D&bold=true' }}" class="w-full h-full object-cover">
                    </div>
                    <span class="text-[13px] font-bold text-gray-800">{{ $post->user->username }}</span>
                </div>

                {{-- Image (if present) --}}
                @if($post->image)
                <div class="overflow-hidden bg-gray-50 flex items-center justify-center border-b border-gray-50" style="max-height:480px;">
                    <img src="{{ asset('storage/'.$post->image) }}" alt="Post image"
                        class="max-w-full h-auto object-contain block" style="max-height:480px;">
                </div>
                @endif

                {{-- Content (caption / text with rendered hashtags) --}}
                <div class="px-4 py-3.5">
                    <p class="text-[13.5px] text-gray-800 leading-relaxed break-words whitespace-pre-line">{!! $post->rendered_content !!}</p>

                    {{-- Hashtag badges --}}
                    @if($post->hashtags->count())
                    <div class="flex flex-wrap gap-1.5 mt-2.5">
                        @foreach($post->hashtags as $tag)
                        <a href="{{ route('community.show', $community) }}?hashtag={{ $tag->slug }}"
                           class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full bg-[#2D5A4C]/8 text-[#2D5A4C] text-[11px] font-bold hover:bg-[#2D5A4C]/15 transition">
                            #{{ $tag->name }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- File Attachments --}}
                @if($post->attachments->count())
                <div class="px-4 pb-3 space-y-1.5">
                    @foreach($post->attachments as $att)
                    @php $info = $att->typeInfo(); @endphp
                    <a href="{{ $att->url() }}" target="_blank" download
                       class="flex items-center gap-2.5 px-3 py-2 rounded-xl border border-gray-100 hover:border-[#2D5A4C]/30 hover:bg-[#2D5A4C]/5 transition group">
                        {{-- File type badge --}}
                        <span class="shrink-0 w-8 h-8 rounded-lg {{ $info['bg'] }} flex items-center justify-center">
                            <span class="text-[10px] font-black {{ $info['color'] }}">{{ $info['label'] }}</span>
                        </span>
                        <span class="flex-1 min-w-0">
                            <span class="block text-[12px] font-semibold text-gray-700 truncate group-hover:text-[#2D5A4C] transition">{{ $att->file_name }}</span>
                            <span class="text-[10px] text-gray-400">{{ $att->fileSizeForHuman() }}</span>
                        </span>
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-[#2D5A4C] transition shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </a>
                    @endforeach
                </div>
                @endif

                {{-- Actions --}}
                <div class="flex items-center gap-4 px-4 h-[52px] border-t border-gray-50 shrink-0">
                    {{-- Like --}}
                    <form method="POST" action="{{ route('posts.like', $post) }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 text-gray-400 hover:text-[#2D5A4C] transition-colors {{ in_array($post->id, $likedPostIds) ? '!text-[#2D5A4C]' : '' }}">
                            <svg class="w-5 h-5" fill="{{ in_array($post->id, $likedPostIds) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                            </svg>
                            @if($post->likes_count > 0)<span class="text-xs font-semibold">{{ $post->likes_count }}</span>@endif
                        </button>
                    </form>
                    {{-- Save --}}
                    <form method="POST" action="{{ route('posts.save', $post) }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-[#2D5A4C] transition-colors {{ in_array($post->id, $savedPostIds) ? '!text-[#2D5A4C]' : '' }}">
                            <svg class="w-5 h-5" fill="{{ in_array($post->id, $savedPostIds) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </button>
                    </form>
                    {{-- Share --}}
                    <div x-data="{ copied: false }" class="relative">
                        <button type="button"
                            @click="navigator.clipboard.writeText('{{ route('community.show', $community) }}#post-{{ $post->id }}'); copied = true; setTimeout(() => copied = false, 2000);"
                            class="text-gray-400 hover:text-[#2D5A4C] transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                            </svg>
                        </button>
                        <div x-show="copied" style="display:none;"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-90"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute bottom-7 left-1/2 -translate-x-1/2 px-2 py-0.5 text-[10px] font-bold text-white bg-gray-900 rounded-lg whitespace-nowrap z-20">
                            ✓ Disalin
                        </div>
                    </div>
                </div>

                </div>{{-- end post content --}}

                {{-- ─── COMMENTS SECTION (SIDE) — Threaded ─── --}}
                <div class="w-[280px] shrink-0 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col"
                     x-data="commentPanel({{ $post->id }}, '{{ route('posts.comments.store.ajax', $post) }}', '{{ csrf_token() }}')"
                     id="comment-panel-{{ $post->id }}">

                    {{-- Header --}}
                    <div class="px-4 h-[56px] flex items-center bg-gray-50/50 border-b border-gray-100 shrink-0">
                        <h4 class="text-[12px] font-bold text-gray-700">
                            Komentar (<span x-text="totalCount">{{ $post->comments_count }}</span>)
                        </h4>
                    </div>

                    {{-- Comment list --}}
                    <div class="flex-1 min-h-[120px] p-3 space-y-3 overflow-y-auto custom-scrollbar" id="comments-list-{{ $post->id }}">

                        @if($post->comments->count() > 0)
                            @foreach($post->comments as $comment)

                            {{-- ── Top-level comment ──────────────────── --}}
                            <div class="group/c" id="comment-{{ $comment->id }}">
                                <div class="flex gap-2 items-start">
                                    <div class="w-6 h-6 rounded-full overflow-hidden shrink-0 border border-gray-100 mt-0.5">
                                        <img src="{{ $comment->user->avatar ? asset('storage/'.$comment->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&background=E2E8F0&color=2A5C4D&bold=true' }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        {{-- Bubble --}}
                                        <div class="bg-gray-50 rounded-xl px-2.5 py-1.5">
                                            <span class="text-[11px] font-bold text-gray-800">{{ $comment->user->username }}</span>
                                            <p class="text-[11px] text-gray-600 break-words leading-relaxed mt-0.5">{{ $comment->content }}</p>
                                        </div>
                                        {{-- Actions row --}}
                                        <div class="flex items-center gap-2 mt-0.5 px-1">
                                            @if($isMember)
                                            <button type="button"
                                                    @click="openReply({{ $comment->id }}, '{{ addslashes($comment->user->username) }}')"
                                                    class="text-[10px] font-bold text-gray-400 hover:text-[#2D5A4C] transition">
                                                Balas
                                            </button>
                                            @endif
                                            @if($comment->user_id === Auth::id() || Auth::user()->isAdmin())
                                            <button type="button"
                                                    @click="deleteComment({{ $comment->id }}, '{{ route('comments.destroy', $comment) }}')"
                                                    class="text-[10px] font-bold text-gray-300 hover:text-red-400 transition opacity-0 group-hover/c:opacity-100">
                                                Hapus
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- ── Replies ─────────────────────────── --}}
                                @if($comment->replies->count() > 0)
                                <div class="mt-2 ml-8 space-y-2 border-l-2 border-[#2D5A4C]/15 pl-2.5"
                                     id="replies-{{ $comment->id }}">

                                    {{-- Show first 3 by default, rest collapsed --}}
                                    @foreach($comment->replies as $replyIdx => $reply)
                                    <div class="group/r {{ $replyIdx >= 3 ? 'reply-extra-'.$comment->id.' hidden' : '' }}"
                                         id="comment-{{ $reply->id }}">
                                        <div class="flex gap-2 items-start">
                                            <div class="w-5 h-5 rounded-full overflow-hidden shrink-0 border border-gray-100 mt-0.5">
                                                <img src="{{ $reply->user->avatar ? asset('storage/'.$reply->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($reply->user->name).'&background=E2E8F0&color=2A5C4D&bold=true' }}"
                                                     class="w-full h-full object-cover">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="bg-gray-50 rounded-xl px-2.5 py-1.5">
                                                    <span class="text-[11px] font-bold text-gray-800">{{ $reply->user->username }}</span>
                                                    <p class="text-[11px] text-gray-600 break-words leading-relaxed mt-0.5">{{ $reply->content }}</p>
                                                </div>
                                                <div class="flex items-center gap-2 mt-0.5 px-1">
                                                    @if($isMember)
                                                    <button type="button"
                                                            @click="openReply({{ $comment->id }}, '{{ addslashes($reply->user->username) }}')"
                                                            class="text-[10px] font-bold text-gray-400 hover:text-[#2D5A4C] transition">
                                                        Balas
                                                    </button>
                                                    @endif
                                                    @if($reply->user_id === Auth::id() || Auth::user()->isAdmin())
                                                    <button type="button"
                                                            @click="deleteComment({{ $reply->id }}, '{{ route('comments.destroy', $reply) }}')"
                                                            class="text-[10px] font-bold text-gray-300 hover:text-red-400 transition opacity-0 group-hover/r:opacity-100">
                                                        Hapus
                                                    </button>
                                                    @endif
                                                </div>

                                                {{-- Level-2 replies (reply-to-reply) --}}
                                                @if($reply->replies->count() > 0)
                                                <div class="mt-1.5 ml-5 space-y-1.5 border-l-2 border-[#2D5A4C]/10 pl-2">
                                                    @foreach($reply->replies as $lvl2)
                                                    <div class="group/r2" id="comment-{{ $lvl2->id }}">
                                                        <div class="flex gap-1.5 items-start">
                                                            <div class="w-4 h-4 rounded-full overflow-hidden shrink-0 border border-gray-100 mt-0.5">
                                                                <img src="{{ $lvl2->user->avatar ? asset('storage/'.$lvl2->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($lvl2->user->name).'&background=E2E8F0&color=2A5C4D&bold=true' }}"
                                                                     class="w-full h-full object-cover">
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <div class="bg-gray-50 rounded-xl px-2 py-1">
                                                                    <span class="text-[10px] font-bold text-gray-800">{{ $lvl2->user->username }}</span>
                                                                    <p class="text-[10px] text-gray-600 break-words leading-relaxed">{{ $lvl2->content }}</p>
                                                                </div>
                                                                <div class="flex items-center gap-2 mt-0.5 px-1">
                                                                    @if($isMember)
                                                                    <button type="button"
                                                                            @click="openReply({{ $comment->id }}, '{{ addslashes($lvl2->user->username) }}')"
                                                                            class="text-[10px] font-bold text-gray-400 hover:text-[#2D5A4C] transition">
                                                                        Balas
                                                                    </button>
                                                                    @endif
                                                                    @if($lvl2->user_id === Auth::id() || Auth::user()->isAdmin())
                                                                    <button type="button"
                                                                            @click="deleteComment({{ $lvl2->id }}, '{{ route('comments.destroy', $lvl2) }}')"
                                                                            class="text-[10px] font-bold text-gray-300 hover:text-red-400 transition opacity-0 group-hover/r2:opacity-100">
                                                                        Hapus
                                                                    </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

                                    {{-- Expand/collapse toggle when > 3 replies --}}
                                    @if($comment->replies->count() > 3)
                                    <button type="button"
                                            onclick="toggleReplies({{ $comment->id }}, {{ $comment->replies->count() - 3 }}, this)"
                                            class="text-[10px] font-bold text-[#2D5A4C] hover:underline ml-1">
                                        Lihat {{ $comment->replies->count() - 3 }} balasan lainnya ▾
                                    </button>
                                    @endif

                                </div>
                                @endif

                                {{-- ── Inline reply form (Alpine-driven) ─ --}}
                                @if($isMember)
                                <div x-show="activeReplyId === {{ $comment->id }}"
                                     style="display:none;"
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 -translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="mt-2 ml-8">
                                    <div class="flex gap-1.5 items-center">
                                        <div class="w-5 h-5 rounded-full overflow-hidden shrink-0 border border-gray-100">
                                            <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=2A5C4D&color=fff&bold=true' }}"
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <input type="text"
                                               x-ref="replyInput_{{ $comment->id }}"
                                               x-model="replyContent"
                                               @keydown.enter.prevent="submitReply({{ $comment->id }})"
                                               @keydown.escape="closeReply()"
                                               :placeholder="replyPlaceholder"
                                               maxlength="500"
                                               class="flex-1 min-w-0 bg-gray-50 border border-gray-100 rounded-full px-2.5 py-1 text-[11px] text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#2D5A4C]/30 focus:border-[#2D5A4C] transition">
                                        <button type="button"
                                                @click="submitReply({{ $comment->id }})"
                                                :disabled="sending"
                                                class="shrink-0 w-6 h-6 rounded-full bg-[#2D5A4C] hover:bg-[#1e4237] disabled:opacity-50 flex items-center justify-center transition active:scale-90">
                                            <template x-if="!sending">
                                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                                            </template>
                                            <template x-if="sending">
                                                <svg class="animate-spin w-3 h-3 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                            </template>
                                        </button>
                                        <button type="button" @click="closeReply()"
                                                class="shrink-0 text-[10px] font-bold text-gray-400 hover:text-gray-600 transition">
                                            ✕
                                        </button>
                                    </div>
                                </div>
                                @endif

                            </div>{{-- end top-level comment --}}
                            @endforeach

                        @else
                            <div class="text-center py-4 text-[11px] text-gray-400">Belum ada komentar</div>
                        @endif

                        {{-- New AJAX-rendered comments get appended here by Alpine --}}
                        <div id="new-comments-{{ $post->id }}"></div>

                    </div>

                    {{-- Top-level comment input --}}
                    @if($isMember)
                    <div class="mt-auto border-t border-gray-100 bg-white px-3 py-2 shrink-0">
                        <form method="POST" action="{{ route('posts.comments.store', $post) }}">
                            @csrf
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full overflow-hidden shrink-0 border border-gray-100">
                                    <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=2A5C4D&color=fff&bold=true' }}"
                                         class="w-full h-full object-cover">
                                </div>
                                <input type="text" name="content" required placeholder="Tambah komentar..."
                                       autocomplete="off"
                                       class="flex-1 min-w-0 bg-gray-50 border border-gray-100 rounded-full px-3 py-1.5 text-[11px] text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#2D5A4C]/30 focus:border-[#2D5A4C] transition">
                                <button type="submit"
                                        class="px-3 py-1.5 rounded-full bg-[#2D5A4C] hover:bg-[#1e4237] text-white text-[11px] font-bold transition shrink-0 active:scale-95">
                                    Kirim
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif

                </div>{{-- end comment panel --}}

            </div>{{-- end post item (flex gap-4) --}}

            @empty
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center py-20">
                <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p class="text-[14px] font-semibold text-gray-400">Belum ada postingan</p>
                @if($isMember)
                <p class="text-[12px] text-gray-300 mt-1">Jadilah yang pertama berdiskusi!</p>
                @else
                <p class="text-[12px] text-gray-300 mt-1">Bergabung untuk mulai berdiskusi.</p>
                @endif
            </div>
            @endforelse

            {{-- Pagination --}}
            <div>{{ $posts->links() }}</div>
        </div>

        {{-- ══════════════ RIGHT SIDEBAR ══════════════ --}}
        <div class="hidden lg:block w-[160px] shrink-0 sticky top-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">

                {{-- Online --}}
                <div class="flex items-center justify-between mb-2">
                    <h5 class="text-[11px] font-bold text-gray-500">Online</h5>
                    <span class="text-[10px] font-bold text-gray-400 bg-gray-100 rounded-full px-1.5 py-0.5">{{ $onlineUsers->count() }}</span>
                </div>
                <div class="mb-4">
                    @foreach($onlineUsers->take(4) as $u)
                    <div class="flex items-center gap-1.5 mb-1.5">
                        <div class="relative w-4 h-4 shrink-0">
                            <div class="w-4 h-4 rounded-full overflow-hidden"><img src="{{ $u->avatar ? asset('storage/'.$u->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($u->name).'&background=E2E8F0&color=2A5C4D&bold=true' }}" class="w-full h-full object-cover"></div>
                            <span class="absolute -bottom-px -right-px w-1.5 h-1.5 bg-emerald-400 rounded-full border border-white"></span>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-600 truncate">{{ $u->username }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- Member Community --}}
                <div class="border-t border-gray-100 pt-3 mb-3">
                    <h5 class="text-[11px] font-bold text-gray-500 mb-2">Member Community</h5>
                    <div class="space-y-1.5">
                        @forelse($memberCommunity as $u)
                        <p class="text-[11px] font-semibold text-gray-600 truncate">{{ $u->username }}</p>
                        @empty
                        <p class="text-[11px] text-gray-300 italic">Belum ada anggota</p>
                        @endforelse
                    </div>
                </div>

                {{-- Friends --}}
                @if(count($friends) > 0)
                <div class="border-t border-gray-100 pt-3">
                    <h5 class="text-[11px] font-bold text-gray-500 mb-2">Friends</h5>
                    <div class="space-y-1.5">
                        @foreach($friends as $u)
                        <p class="text-[11px] font-semibold text-gray-600 truncate">{{ $u->username }}</p>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            {{-- Join/Leave --}}
            @if(!$isMember)
            <form method="POST" action="{{ route('community.join', $community) }}" class="mt-3">
                @csrf
                <button class="w-full py-2 bg-[#2A5C4D] hover:bg-[#1e4237] text-white text-[12px] font-bold rounded-xl transition shadow-sm">Join</button>
            </form>
            @else
            <form method="POST" action="{{ route('community.leave', $community) }}" class="mt-3">
                @csrf
                <button class="w-full py-2 border border-red-200 text-red-500 hover:bg-red-50 text-[12px] font-bold rounded-xl transition">Leave</button>
            </form>
            @endif
        </div>

    </div>

    {{-- ════════════════════════════════════════
         BOTTOM POSTING BAR
    ════════════════════════════════════════ --}}
    @if($isMember)
    {{--
        BOTTOM POSTING BAR
        Strategy: The bar is fixed. Its left/width are synced to #feed-container via JS
        so it always perfectly underlays the post cards regardless of sidebar or viewport.
    --}}
    <div id="posting-bar"
         class="fixed bottom-0 z-40 pb-4 pt-3 transition-all"
         style="left:0;right:0;"
         x-data="postForm()">

        {{-- Single-column form card — same width as the post card column --}}
        <div class="relative bg-white rounded-2xl border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.08)] overflow-hidden w-full"
             @dragover.prevent="dragOver = true"
             @dragleave.prevent="dragOver = false"
             @drop.prevent="handleDrop($event)"
             :class="{'ring-2 ring-[#2D5A4C]/30': dragOver}">

            {{-- ── Image preview ──────────────────────────────── --}}
            <div x-show="imagePreview" style="display:none;"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="flex items-center gap-2.5 px-4 pt-3">
                <img :src="imagePreview" class="h-9 w-12 object-cover rounded-md shrink-0">
                <div class="flex-1 min-w-0">
                    <p class="text-[12px] font-semibold text-gray-700 truncate" x-text="imageName"></p>
                    <p class="text-[10px] text-emerald-600 font-semibold">Gambar siap diposting</p>
                </div>
                <button type="button" @click="removeImage()" class="w-6 h-6 rounded-full bg-gray-100 hover:bg-red-100 hover:text-red-500 flex items-center justify-center text-gray-400 transition shrink-0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- ── File attachment previews ────────────────────── --}}
            <div x-show="attachedFiles.length > 0" style="display:none;"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="px-4 pt-2.5 space-y-1">
                <template x-for="(f, idx) in attachedFiles" :key="idx">
                    <div class="flex items-center gap-2 px-2.5 py-1.5 bg-gray-50 rounded-xl border border-gray-100">
                        {{-- Icon badge --}}
                        <span class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0"
                              :class="fileIconBg(f.type)">
                            <span class="text-[9px] font-black" :class="fileIconColor(f.type)" x-text="fileLabel(f.type)"></span>
                        </span>
                        <span class="flex-1 min-w-0">
                            <span class="block text-[11px] font-semibold text-gray-700 truncate" x-text="f.name"></span>
                            <span class="text-[10px] text-gray-400" x-text="humanSize(f.size)"></span>
                        </span>
                        <button type="button" @click="removeFile(idx)"
                                class="w-5 h-5 rounded-full hover:bg-red-100 hover:text-red-500 flex items-center justify-center text-gray-300 transition shrink-0">
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
            </div>

            {{-- ── Validation errors ──────────────────────────── --}}
            <div x-show="imageError" style="display:none;" class="px-5 pt-2 text-[11px] text-red-500 font-semibold" x-text="imageError"></div>
            <div x-show="fileError" style="display:none;" class="px-5 pt-1 text-[11px] text-red-500 font-semibold" x-text="fileError"></div>

            {{-- ── Drag hint ───────────────────────────────────── --}}
            <div x-show="dragOver" style="display:none;"
                 class="absolute inset-0 flex items-center justify-center bg-white/95 border-2 border-dashed border-[#2D5A4C] rounded-2xl z-10 text-[#2D5A4C] font-bold text-[14px] gap-2">
                <svg class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Lepaskan gambar di sini
            </div>

            {{-- ── Hashtag autocomplete dropdown ──────────────── --}}
            <div x-show="hashtagSuggestions.length > 0" style="display:none;"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute bottom-full mb-1 left-4 right-4 bg-white border border-gray-100 rounded-xl shadow-[0_8px_24px_rgba(0,0,0,0.10)] z-50 overflow-hidden">
                <div class="px-3 py-1.5 border-b border-gray-50">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Saran Hashtag</p>
                </div>
                <template x-for="tag in hashtagSuggestions" :key="tag.slug">
                    <button type="button"
                            @click="insertHashtag(tag.name)"
                            class="w-full flex items-center gap-2 px-3 py-2 hover:bg-[#2D5A4C]/5 text-left transition">
                        <span class="w-6 h-6 rounded-full bg-[#2D5A4C]/10 flex items-center justify-center text-[#2D5A4C] text-[11px] font-black shrink-0">#</span>
                        <span class="flex-1 min-w-0">
                            <span class="text-[13px] font-semibold text-gray-700" x-text="'#' + tag.name"></span>
                        </span>
                        <span class="text-[10px] text-gray-400 shrink-0" x-text="tag.usage_count + ' posts'"></span>
                    </button>
                </template>
            </div>

            <form id="post-form"
                  method="POST"
                  action="{{ route('community.posts.store', $community) }}"
                  enctype="multipart/form-data"
                  @submit="submitting = true">
                @csrf

                {{-- Hidden file inputs (populated by Alpine) --}}
                <input type="file" id="post-image" name="image" class="hidden"
                       accept="image/jpeg,image/png,image/gif,image/webp"
                       @change="handleFileSelect($event)">
                <input type="file" id="post-files" name="files[]" class="hidden" multiple
                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt"
                       @change="handleAttachSelect($event)">

                {{-- Textarea --}}
                <div class="px-4 pt-4 pb-1">
                    <textarea
                        name="content"
                        id="post-content"
                        required
                        placeholder="Discuss here or Mention someone... (Ketik # untuk hashtag)"
                        maxlength="2000"
                        rows="1"
                        @input="autoResize($el); charCount = $el.value.length; detectHashtag($el)"
                        @keydown.escape="hashtagSuggestions = []"
                        @blur="setTimeout(() => hashtagSuggestions = [], 200)"
                        class="w-full bg-transparent border-0 text-[14px] text-gray-700 placeholder-gray-400 resize-none focus:ring-0 focus:outline-none leading-relaxed"
                        style="min-height:56px; max-height:140px;"
                    ></textarea>
                </div>

                {{-- Toolbar --}}
                <div class="flex items-center justify-between border-t border-gray-100 px-4 py-3">
                    <div class="flex items-center gap-0.5">

                        {{-- Image upload button --}}
                        <label class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center cursor-pointer transition"
                               :class="imagePreview ? 'text-[#2D5A4C]' : 'text-gray-400 hover:text-[#2D5A4C]'"
                               title="Tambah gambar"
                               @click="$refs.imgInput.click(); $event.preventDefault()">
                            <svg class="w-[17px] h-[17px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </label>
                        {{-- real input wired via x-ref --}}
                        <input type="file" x-ref="imgInput" name="image" class="hidden"
                               accept="image/jpeg,image/png,image/gif,image/webp"
                               @change="handleFileSelect($event)">

                        {{-- File upload button --}}
                        <button type="button" title="Upload file (PDF, DOC, XLS, PPT, TXT)"
                                :class="attachedFiles.length > 0 ? 'text-[#2D5A4C]' : 'text-gray-400 hover:text-[#2D5A4C]'"
                                class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center transition relative"
                                @click="$refs.fileInput.click()">
                            <svg class="w-[17px] h-[17px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            {{-- Badge showing count --}}
                            <span x-show="attachedFiles.length > 0" style="display:none;"
                                  class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-[#2D5A4C] text-white text-[9px] font-black flex items-center justify-center"
                                  x-text="attachedFiles.length"></span>
                        </button>
                        <input type="file" x-ref="fileInput" name="files[]" class="hidden" multiple
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt"
                               @change="handleAttachSelect($event)">

                        {{-- Hashtag button --}}
                        <button type="button" title="Tambah hashtag"
                                class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-[#2D5A4C] transition"
                                @click="insertHashtagSymbol()">
                            <svg class="w-[17px] h-[17px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                        </button>

                        {{-- Char counter --}}
                        <span x-show="charCount > 0" style="display:none;"
                              class="ml-1 text-[10px] font-mono"
                              :class="charCount > 1800 ? (charCount > 1950 ? 'text-red-500 font-bold' : 'text-amber-500') : 'text-gray-300'">
                            <span x-text="2000 - charCount"></span>
                        </span>
                    </div>

                    <button type="submit"
                            :disabled="submitting"
                            class="flex items-center gap-1.5 bg-[#2D5A4C] hover:bg-[#1a3d32] disabled:opacity-60 text-white px-5 py-1.5 rounded-full font-bold text-[13px] transition shadow-sm hover:shadow active:scale-[0.97]">
                        <template x-if="submitting">
                            <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                        </template>
                        <span x-text="submitting ? 'Posting...' : 'Posting'"></span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- JS: Sync posting bar width & position to match the feed-container column --}}
    <script>
    (function () {
        /**
         * The feed layout is:
         *   [forum-layout]  flex gap-5 justify-center
         *     [feed-container]  flex-1 max-w-[880px]   <-- post cards live here
         *     [right sidebar]  w-[160px]  (hidden below lg)
         *
         * The post card itself occupies the LEFT portion of the feed-container:
         *   feed-container = post-card (flex-1) + comment-section (w-[280px]) with gap-4
         *
         * We want the posting bar to align exactly with the post-card column.
         * Strategy: read feed-container's left/width from getBoundingClientRect(),
         * then compute the post-card column width (feed - 280px spacer - gap 16px).
         */
        function syncBar() {
            var feed = document.getElementById('feed-container');
            var bar  = document.getElementById('posting-bar');
            if (!feed || !bar) return;

            var rect = feed.getBoundingClientRect();
            // gap-4 = 16px, comment sidebar = 280px
            var postCardWidth = rect.width - 280 - 16;
            // Clamp: on small screens where there is no comment sidebar, use full feed width
            if (postCardWidth < rect.width * 0.5) postCardWidth = rect.width;

            bar.style.left  = rect.left + 'px';
            bar.style.right = 'auto';
            bar.style.width = postCardWidth + 'px';
        }

        // Run on load and on every resize
        window.addEventListener('DOMContentLoaded', syncBar);
        window.addEventListener('resize', syncBar);
        // Also run after a short delay to handle any late-rendering sidebars
        window.addEventListener('DOMContentLoaded', function() { setTimeout(syncBar, 100); });
    })();
    </script>

    {{-- ── Alpine: commentPanel — threaded reply logic ─────────────────── --}}
    <script>
    /**
     * Alpine.js component for each comment panel.
     *
     * @param {number} postId      - The post ID (used for DOM targeting)
     * @param {string} ajaxUrl     - Route: POST /posts/{post}/comments/ajax
     * @param {string} csrfToken   - Laravel CSRF token
     */
    function commentPanel(postId, ajaxUrl, csrfToken) {
        return {
            // ── State ──────────────────────────────────────────────
            postId,
            ajaxUrl,
            csrfToken,
            activeReplyId:   null,   // which comment's inline form is open
            replyContent:    '',
            replyPlaceholder:'Tulis balasan...',
            sending:         false,
            totalCount:      parseInt(
                document.querySelector(`#comment-panel-${postId} [x-text="totalCount"]`)
                    ?.textContent || 0
            ),

            // ── Open reply form under a specific comment ────────────
            openReply(commentId, username) {
                this.activeReplyId   = commentId;
                this.replyContent    = `@${username} `;
                this.replyPlaceholder = `Tulis balasan untuk @${username}...`;
                // Auto-focus after Alpine renders the input (next tick)
                this.$nextTick(() => {
                    const ref = this.$refs[`replyInput_${commentId}`];
                    if (ref) {
                        ref.focus();
                        // Move cursor to end
                        const len = ref.value.length;
                        ref.setSelectionRange(len, len);
                    }
                });
            },

            // ── Close reply form ────────────────────────────────────
            closeReply() {
                this.activeReplyId  = null;
                this.replyContent   = '';
                this.sending        = false;
            },

            // ── Submit reply via AJAX ───────────────────────────────
            async submitReply(parentCommentId) {
                const content = this.replyContent.trim();
                if (!content || this.sending) return;

                this.sending = true;
                try {
                    const res = await fetch(this.ajaxUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept':       'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                        },
                        body: JSON.stringify({
                            content,
                            parent_comment_id: parentCommentId,
                        }),
                    });

                    if (!res.ok) throw new Error('Server error ' + res.status);
                    const { comment } = await res.json();

                    // ── Inject the new reply into the DOM ──────────
                    this._appendReply(parentCommentId, comment);
                    this.totalCount++;
                    this.closeReply();
                } catch (err) {
                    console.error('Reply failed:', err);
                    alert('Gagal mengirim balasan. Coba lagi.');
                    this.sending = false;
                }
            },

            // ── Delete comment/reply via AJAX ───────────────────────
            async deleteComment(commentId, deleteUrl) {
                if (!confirm('Hapus komentar ini?')) return;
                try {
                    const res = await fetch(deleteUrl, {
                        method: 'POST',   // Laravel _method spoofing
                        headers: {
                            'Content-Type':  'application/x-www-form-urlencoded',
                            'Accept':        'application/json',
                            'X-CSRF-TOKEN':  this.csrfToken,
                        },
                        body: '_method=DELETE',
                    });
                    if (!res.ok) throw new Error('Delete failed');
                    const el = document.getElementById(`comment-${commentId}`);
                    if (el) {
                        el.style.transition = 'opacity .2s, transform .2s';
                        el.style.opacity    = '0';
                        el.style.transform  = 'translateX(-8px)';
                        setTimeout(() => el.remove(), 200);
                    }
                    this.totalCount = Math.max(0, this.totalCount - 1);
                } catch (err) {
                    console.error('Delete failed:', err);
                    alert('Gagal menghapus komentar.');
                }
            },

            // ── DOM builder: append reply into the replies container ─
            _appendReply(parentCommentId, comment) {
                // Find or create the replies container for this parent comment
                let repliesContainer = document.getElementById(`replies-${parentCommentId}`);

                if (!repliesContainer) {
                    // No replies yet — create the container
                    const parentEl = document.getElementById(`comment-${parentCommentId}`);
                    if (!parentEl) return;
                    repliesContainer = document.createElement('div');
                    repliesContainer.id = `replies-${parentCommentId}`;
                    repliesContainer.className = 'mt-2 ml-8 space-y-2 border-l-2 border-[#2D5A4C]/15 pl-2.5';
                    // Insert before the reply form (last child)
                    parentEl.appendChild(repliesContainer);
                }

                const avatarSrc = comment.user.avatar
                    || `https://ui-avatars.com/api/?name=${encodeURIComponent(comment.user.name)}&background=E2E8F0&color=2A5C4D&bold=true`;

                const html = `
                <div class="group/r" id="comment-${comment.id}"
                     style="animation: fadeUp .2s ease both;">
                    <div class="flex gap-2 items-start">
                        <div class="w-5 h-5 rounded-full overflow-hidden shrink-0 border border-gray-100 mt-0.5">
                            <img src="${avatarSrc}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="bg-gray-50 rounded-xl px-2.5 py-1.5">
                                <span class="text-[11px] font-bold text-gray-800">${this._esc(comment.user.username)}</span>
                                <p class="text-[11px] text-gray-600 break-words leading-relaxed mt-0.5">${this._esc(comment.content)}</p>
                            </div>
                            <div class="flex items-center gap-2 mt-0.5 px-1">
                                <button type="button"
                                        onclick="document.getElementById('comment-panel-${this.postId}').__x.$data.openReply(${parentCommentId}, '${this._esc(comment.user.username)}')"
                                        class="text-[10px] font-bold text-gray-400 hover:text-[#2D5A4C] transition">
                                    Balas
                                </button>
                                ${comment.can_delete ? `
                                <button type="button"
                                        onclick="document.getElementById('comment-panel-${this.postId}').__x.$data.deleteComment(${comment.id}, '${comment.delete_url}')"
                                        class="text-[10px] font-bold text-gray-300 hover:text-red-400 transition opacity-0 group-hover/r:opacity-100">
                                    Hapus
                                </button>` : ''}
                            </div>
                        </div>
                    </div>
                </div>`;

                repliesContainer.insertAdjacentHTML('beforeend', html);

                // Scroll the comment list so the new reply is visible
                const list = document.getElementById(`comments-list-${this.postId}`);
                if (list) list.scrollTop = list.scrollHeight;
            },

            // ── HTML escape helper ──────────────────────────────────
            _esc(str) {
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            },
        };
    }

    /**
     * Vanilla JS: expand / collapse extra replies beyond the first 3.
     * Called via onclick (not Alpine) so it works regardless of component scope.
     */
    function toggleReplies(commentId, extraCount, btn) {
        const extras = document.querySelectorAll(`.reply-extra-${commentId}`);
        const expanded = btn.dataset.expanded === '1';

        extras.forEach(el => {
            el.classList.toggle('hidden', expanded);
        });

        if (expanded) {
            btn.textContent = `Lihat ${extraCount} balasan lainnya ▾`;
            btn.dataset.expanded = '0';
        } else {
            btn.textContent = 'Sembunyikan balasan ▴';
            btn.dataset.expanded = '1';
        }
    }
    </script>
    <script>
    function postForm() {
        return {
            // ── State ─────────────────────────────────────────
            dragOver:           false,
            imagePreview:       null,
            imageName:          '',
            imageError:         '',
            fileError:          '',
            charCount:          0,
            submitting:         false,
            attachedFiles:      [],   // { name, size, type, file } objects for preview
            attachedFileList:   [],   // DataTransfer-backed FileList for the real <input>
            hashtagSuggestions: [],
            _hashtagTimer:      null,

            // ── Image handling ────────────────────────────────
            autoResize(el) {
                el.style.height = 'auto';
                el.style.height = Math.min(el.scrollHeight, 160) + 'px';
            },
            handleFileSelect(e) {
                const file = e.target.files[0];
                if (file) this.validateAndPreview(file);
            },
            handleDrop(e) {
                this.dragOver = false;
                const file = e.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    this.$refs.imgInput.files = dt.files;
                    this.validateAndPreview(file);
                }
            },
            validateAndPreview(file) {
                this.imageError = '';
                const allowed = ['image/jpeg','image/png','image/gif','image/webp'];
                if (!allowed.includes(file.type)) { this.imageError = 'Format gambar tidak didukung (JPG/PNG/GIF/WEBP).'; return; }
                if (file.size > 2 * 1024 * 1024)  { this.imageError = 'Gambar terlalu besar, maks 2MB.'; return; }
                const reader = new FileReader();
                reader.onload = e => {
                    this.imagePreview = e.target.result;
                    this.imageName    = file.name.length > 32 ? file.name.slice(0, 30) + '…' : file.name;
                };
                reader.readAsDataURL(file);
            },
            removeImage() {
                this.imagePreview = null;
                this.imageName    = '';
                this.imageError   = '';
                this.$refs.imgInput.value = '';
            },

            // ── File attachment handling ──────────────────────
            handleAttachSelect(e) {
                this.fileError = '';
                const MAX_SIZE    = 10 * 1024 * 1024; // 10 MB
                const MAX_FILES   = 5;
                const ALLOWED_EXT = ['pdf','doc','docx','xls','xlsx','ppt','pptx','txt'];

                const incoming = Array.from(e.target.files);
                for (const file of incoming) {
                    const ext = file.name.split('.').pop().toLowerCase();
                    if (!ALLOWED_EXT.includes(ext)) {
                        this.fileError = `Format "${ext}" tidak didukung. Gunakan PDF, DOC, XLS, PPT, atau TXT.`;
                        this.$refs.fileInput.value = '';
                        return;
                    }
                    if (file.size > MAX_SIZE) {
                        this.fileError = `"${file.name}" terlalu besar. Maks 10 MB per file.`;
                        this.$refs.fileInput.value = '';
                        return;
                    }
                    if (this.attachedFiles.length >= MAX_FILES) {
                        this.fileError = `Maks ${MAX_FILES} file per posting.`;
                        this.$refs.fileInput.value = '';
                        return;
                    }
                    this.attachedFiles.push({ name: file.name, size: file.size, type: file.type, file });
                }

                // Rebuild the actual <input files> from all accumulated files
                this._syncFileInput();
            },
            removeFile(idx) {
                this.attachedFiles.splice(idx, 1);
                this._syncFileInput();
            },
            _syncFileInput() {
                const dt = new DataTransfer();
                this.attachedFiles.forEach(f => dt.items.add(f.file));
                this.$refs.fileInput.files = dt.files;
            },

            // ── File type icon helpers ────────────────────────
            fileLabel(mime) {
                if (mime.includes('pdf'))          return 'PDF';
                if (mime.includes('word'))         return 'DOC';
                if (mime.includes('excel') || mime.includes('spreadsheet')) return 'XLS';
                if (mime.includes('presentation')) return 'PPT';
                if (mime.includes('text'))         return 'TXT';
                return 'FILE';
            },
            fileIconBg(mime) {
                if (mime.includes('pdf'))          return 'bg-red-50';
                if (mime.includes('word'))         return 'bg-blue-50';
                if (mime.includes('excel') || mime.includes('spreadsheet')) return 'bg-emerald-50';
                if (mime.includes('presentation')) return 'bg-orange-50';
                return 'bg-gray-100';
            },
            fileIconColor(mime) {
                if (mime.includes('pdf'))          return 'text-red-500';
                if (mime.includes('word'))         return 'text-blue-600';
                if (mime.includes('excel') || mime.includes('spreadsheet')) return 'text-emerald-600';
                if (mime.includes('presentation')) return 'text-orange-500';
                return 'text-gray-500';
            },
            humanSize(bytes) {
                if (bytes < 1024)       return bytes + ' B';
                if (bytes < 1048576)    return (bytes / 1024).toFixed(1) + ' KB';
                return (bytes / 1048576).toFixed(1) + ' MB';
            },

            // ── Hashtag autocomplete ──────────────────────────
            detectHashtag(el) {
                const val    = el.value;
                const cursor = el.selectionStart;
                // Find the word being typed up to cursor
                const before = val.slice(0, cursor);
                const match  = before.match(/#(\w*)$/);
                if (!match) { this.hashtagSuggestions = []; return; }

                const query = match[1];
                clearTimeout(this._hashtagTimer);
                this._hashtagTimer = setTimeout(async () => {
                    try {
                        const res  = await fetch(`/hashtags/suggest?q=${encodeURIComponent(query)}`);
                        const data = await res.json();
                        this.hashtagSuggestions = data;
                    } catch (_) {
                        this.hashtagSuggestions = [];
                    }
                }, 220); // debounce 220ms
            },
            insertHashtag(name) {
                const ta     = document.getElementById('post-content');
                const cursor = ta.selectionStart;
                const val    = ta.value;
                // Replace the trailing '#word' with '#name '
                const before = val.slice(0, cursor).replace(/#\w*$/, '#' + name + ' ');
                ta.value     = before + val.slice(cursor);
                ta.focus();
                ta.setSelectionRange(before.length, before.length);
                // Trigger Alpine updates
                this.charCount          = ta.value.length;
                this.hashtagSuggestions = [];
                this.autoResize(ta);
            },
            insertHashtagSymbol() {
                const ta     = document.getElementById('post-content');
                const start  = ta.selectionStart;
                const end    = ta.selectionEnd;
                ta.value     = ta.value.slice(0, start) + '#' + ta.value.slice(end);
                ta.focus();
                ta.setSelectionRange(start + 1, start + 1);
                this.charCount = ta.value.length;
                this.autoResize(ta);
                // Immediately trigger suggestion for empty #
                this.detectHashtag(ta);
            },
        }
    }
    </script>
    @endif

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 2px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 99px; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>

</x-app-layout>
