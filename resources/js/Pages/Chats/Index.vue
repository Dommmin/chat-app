<template>
    <AppLayout>
        <div class="h-[calc(100vh-4rem)] text-white flex justify-center items-center max-w-7xl mx-auto w-full bg-gray-700">
            <div class="hidden md:block h-full p-6 space-y-4 overflow-y-auto border-r border-gray-700">
                <button type="button" class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900 w-full">Group Chat
                </button>
                <Link :href="route('chats.show', chat.id)" v-for="chat in chats.data" class="grid grid-cols-6 gap-4 p-2 rounded-lg hover:bg-gray-800 border border-gray-800">
                    <div class="flex justify-center items-center">
                        <img :src="chat.user.profile_photo_url" class="w-10 h-10 rounded-full" alt=""/>
                    </div>
                    <div class="col-span-4">
                        <div class="text-sm">{{ chat.user.name }}</div>
                        <div class="text-xs text-gray-500 truncate">
                            <span v-if="chat.latest_message.from_id === $page.props.auth.user.id">You: </span>
                            {{chat.latest_message.body}}
                        </div>
                    </div>
                </Link>
            </div>
            <main class="w-full md:w-2/3 flex flex-col bg-gray-800 h-full">
                <slot />
            </main>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link} from '@inertiajs/vue3';

const props = defineProps({
    id: Number,
    chats: Object,
});
</script>
