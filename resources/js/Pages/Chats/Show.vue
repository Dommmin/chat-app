<template>
    <ChatIndex :chats="chats">
        <div class="p-6 space-y-4 overflow-y-auto flex-grow" ref="scrollContainer">
            <div ref="landmark"></div>
            <div v-for="message in messages.data" :key="message.id">
                <Message :message="message" />
            </div>
        </div>

        <div class="p-10 sticky bottom-0 border-t-2 border-gray-700">
            <div class="relative">
                <button @click="sendMessage" class="absolute top-1/2 -translate-y-1/2 right-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 hover:text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                    </svg>
                </button>
                <input
                    v-model="message.body"
                    type="text"
                    @keydown.enter="sendMessage"
                    class="w-full rounded-[10px] bg-gray-700 text-white p-4"
                    placeholder="Type a message..."
                />
            </div>
        </div>
    </ChatIndex>
</template>

<script setup>
import Message from '@/Components/Message.vue';
import {onMounted, reactive, ref} from 'vue';
import {router, usePage} from '@inertiajs/vue3';
import ChatIndex from '@/Pages/Chats/Index.vue';
import {useInfiniteScroll} from '@/Composables/useInfiniteScroll.js';

const props = defineProps({
    id: Number,
    chats: Object,
    messages: Object,
});

const landmark = ref(null);

const {items} = useInfiniteScroll('messages', landmark);

const message = reactive({
    body: '',
    attachment: null,
});

const scrollContainer = ref(null);

const scrollToTheBottom = async () => {
    scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight
};

const sendMessage = async () => {
    if (!message.body) {
        return;
    }

    router.post(route('messages.store', {id: usePage().props.id}), {
        body: message.body,
        attachment: message.attachment
    }, {
        onSuccess: () => {
            scrollToTheBottom();
        },
    })

    message.body = '';
    message.attachment = null;
};

window.Echo
    .channel('chat')
    .listen('.messages', async () => {
        console.log('new message');
    });

onMounted(async () => {
    await scrollToTheBottom();
});
</script>
