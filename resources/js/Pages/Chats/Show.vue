<template>
    <ChatIndex :chats="chats" :sendMessage="sendMessage" :form="form">
        <div class="space-y-4" ref="scrollContainer">
            <div ref="landmark"></div>
            <div v-for="message in messages.data" :key="message.id">
                <Message :message="message" />
            </div>
        </div>
    </ChatIndex>
</template>

<script setup>
import Message from '@/Components/Message.vue';
import {onMounted, ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import ChatIndex from '@/Pages/Chats/Index.vue';
import {useInfiniteScroll} from '@/Composables/useInfiniteScroll.js';

const form = useForm({
    body: '',
    attachment: null,
});

const props = defineProps({
    id: Number,
    chats: Object,
    messages: Object,
});

const landmark = ref(null);

const {items} = useInfiniteScroll('messages', landmark);

// const message = reactive({
//     body: '',
//     attachment: null,
// });

const scrollContainer = ref(null);

const scrollToTheBottom = async () => {
    scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight
};

const sendMessage = async () => {
    if (!form.body) {
        return;
    }

    form.post(route('messages.store', {id: props.id}), {
        onSuccess: () => {
            scrollToTheBottom();
        },
    });

    // router.post(route('messages.store', {id: usePage().props.id}), {
    //     body: message.body,
    //     attachment: message.attachment
    // }, {
    //     onSuccess: () => {
    //         scrollToTheBottom();
    //     },
    // })

    form.reset();
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
