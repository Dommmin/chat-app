<template>
   <ChatIndex
      :chats="chats"
      :sendMessage="sendMessage"
      :form="form"
      :user="user"
      :attachmentPreview="attachmentPreview"
      :attachments="attachments"
   >
       <div class="overflow-auto p-4 h-[calc(100vh-134px)] overflow-y-auto space-y-3" style="scrollbar-width: thin" ref="scrollContainer">
           <div ref="landmark"></div>
           <div v-for="message in items" :key="message.id">
               <Message :message="message" />
           </div>
       </div>
   </ChatIndex>
</template>

<script setup>
import Message from '@/Components/Message.vue';
import { onMounted, reactive, ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import ChatIndex from '@/Pages/Chats/Index.vue';
import { useInfiniteScroll } from '@/Composables/useInfiniteScroll.js';
import { toast } from 'vue3-toastify';

const props = defineProps({
    id: Number,
    chats: Object,
    messages: Object,
    user: Object,
    attachments: Object,
});

const form = useForm({
   body: '',
   attachment: null,
});

const attachmentPreview = reactive({
   value: '',
});

const landmark = ref(null);

const { items } = useInfiniteScroll('messages', landmark);

const scrollContainer = ref(null);

const scrollToTheBottom = async () => {
   scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight;
};

const sendMessage = async () => {
   if (!(form.body || form.attachment)) {
      return;
   }

   form.post(route('messages.store', { id: props.id }), {
      onSuccess: () => {
         form.reset();
          attachmentPreview.value = '';
          router.get(route('chats.show', { id: props.id }));
          scrollToTheBottom();
      },
      onError: (errors) => {
         if (errors.attachment) {
            toast(errors.attachment, {
               type: 'error',
               position: 'bottom-right',
               autoClose: 2000,
               hideProgressBar: false,
               closeOnClick: true,
               pauseOnHover: true,
               draggable: true,
               progress: undefined,
            });
         }
      },
   });
};

window.Echo.channel('chat').listen('.messages', async () => {
   console.log('new message');
});

onMounted( () => {
   scrollToTheBottom();
});
</script>
