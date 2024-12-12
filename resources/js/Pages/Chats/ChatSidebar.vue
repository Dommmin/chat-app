<template>
    <div class="z-10 w-full bg-base-200">
        <div class="fixed bottom-0 left-0 top-0 flex h-full w-full flex-col border-r border-neutral-200 md:w-[300px] lg:w-[400px]">
            <div class="flex flex-col justify-center space-y-4">
                <!-- Header -->
                <Link
                    :href="route('chats.index')"
                    class="flex pl-2 justify-center items-center pt-2 hover:bg-secondary/90 bg-secondary text-white p-2 h-16"
                >
                    <ChatIcon class="w-6 h-6 mr-2" />
                    <p class="font-bold uppercase tracking-widest text-xl">Messenger</p>
                </Link>

                <!-- Search -->
                <div class="flex justify-center items-center px-2">
                    <input
                        v-model="searchInput"
                        type="text"
                        class="w-full input input-bordered rounded-full focus:outline-none"
                        placeholder="Search chats..."
                        @input="$emit('search', searchInput)"
                    />
                </div>

                <!-- Favorites Section -->
                <div class="space-y-3">
                    <SectionDivider title="Favorites" />
                    <div v-if="favoriteChats.length === 0" class="flex justify-center">
                        <p class="label-text">You don't have any favorites chats yet.</p>
                    </div>
                    <ChatList
                        v-else
                        :chats="favoriteChats"
                        :current-chat-id="$page.props.id"
                    />
                </div>

                <!-- All Messages Section -->
                <div class="space-y-3">
                    <SectionDivider title="All Messages" />
                    <div
                        class="flex-grow overflow-auto"
                        style="height: calc(100vh - 260px); scrollbar-width: thin"
                    >
                        <ChatList
                            :chats="items"
                            :current-chat-id="$page.props.id"
                        />
                        <div ref="landmark"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
