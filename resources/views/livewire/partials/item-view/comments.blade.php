<x-item-view.section key="comments">
    <div
        class="space-y-2"
        x-data
    >
        <form action="{{ route('comments.store') }}" method="post" x-ref="commentForm">
            @csrf

            <input type="hidden" name="commentable_type" value="{{ get_class($this->item) }}" />
            <input type="hidden" name="commentable_id" value="{{ $this->item->id }}" />

            <div class="space-y-1">
                <x-jet-label for="comment" class="sr-only"></x-jet-label>

                <p id="comment_helper" class="sr-only">Write a comment</p>

                <div>
                    <textarea
                        name="comment"
                        id="comment"
                        aria-describedby="comment_helper"
                        placeholder="Write a comment"
                        value="{{ old('comment') }}"
                        class="block w-full sm:text-sm border-gray-300 focus:ring-pink-500 focus:border-pink-500 rounded-md shadow-sm"
                        x-on:keydown.cmd.enter="$refs.commentForm.submit()"
                    ></textarea>

                    <div class="flex justify-end mt-2">
                        <x-jet-button>
                            <x-heroicon-s-chat-alt class="w-5 h-5 -ml-2 mr-1" />
                            <span>Post Comment</span>
                        </x-jet-button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="flow-root mt-4">
        <ul class="-mb-8">
            @foreach($this->item->comments as $comment)
                <li>
                    <div class="relative pb-8">
                        @unless($loop->last)
                            <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                        @endif

                        <div class="relative flex items-start space-x-3">
                            <div class="relative">
                                <img class="h-10 w-10 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white" src="{{ auth()->user()->getProfilePhotoUrlAttribute() }}" alt="">

                                <span class="absolute -bottom-0.5 -right-1 bg-white rounded-tl px-0.5 py-px">
                                    @svg('heroicon-s-chat-alt', 'h-5 w-5 text-gray-400')
                                </span>
                            </div>

                            <div class="min-w-0 flex-1">
                                <div>
                                    <div class="text-sm">
                                        <a
                                            href="{{ $comment->user ? route('users.show', [$comment->user]) : '#' }}"
                                            class="font-medium text-gray-900"
                                        >{{ optional($comment->user)->name ?? 'Deleted User' }}</a>
                                    </div>

                                    <p class="mt-0.5 text-sm text-gray-500">
                                        Commented {{ $comment->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                <div class="mt-2 text-sm text-gray-700">
                                    <p>
                                        {{ $comment->comment }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="absolute top-0 right-0">
                            <form action="{{ route('comments.destroy', [$comment]) }}" method="post">
                                @csrf
                                @method('delete')

                                <x-jet-button>
                                    @svg('heroicon-s-x', 'h-2 w-2')
                                </x-jet-delete>
                            </form>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</x-item-view.section>
