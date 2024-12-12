<template>
   <ChatIndex
      :chats="chats"
      :sendMessage="sendMessage"
      :form="form"
      :user="user"
      :attachmentPreview="attachmentPreview"
      :attachments="attachments"
   >
      <div
         ref="scrollContainer"
         class="overflow-auto p-4 h-[calc(100vh-134px)] overflow-y-auto space-y-3"
         style="scrollbar-width: thin"
         @scroll="handleScroll"
      >
         <div ref="loadingTrigger" v-if="messages.next_page_url" class="text-center py-2">
            <div v-if="isFetching">Loading...</div>
         </div>
         <div v-for="(message, index) in results" :key="message.id">
            <Message :message="message" :ref="index === results.length - 1 ? setLastMessageRef : undefined" />
         </div>
      </div>
   </ChatIndex>
</template>

<script setup>
import Message from '@/Components/Message.vue';
import { nextTick, onBeforeUnmount, onMounted, reactive, ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import ChatIndex from '@/Pages/Chats/Index.vue';
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

const results = ref([]);
const isFetching = ref(false);
const scrollContainer = ref(null);
const loadingTrigger = ref(null);
const lastMessageRef = ref(null);
const lastScrollHeight = ref(0);
const observer = ref(null);

const setLastMessageRef = (el) => {
   if (el) {
      lastMessageRef.value = el;
   }
};

const setupIntersectionObserver = () => {
   observer.value = new IntersectionObserver(
      (entries) => {
         if (entries[0].isIntersecting && !isFetching.value && props.messages.next_page_url) {
            loadMore();
         }
      },
      {
         root: scrollContainer.value,
         threshold: 0.1,
      },
   );

   if (loadingTrigger.value) {
      observer.value.observe(loadingTrigger.value);
   }
};

const loadMore = () => {
   if (isFetching.value) return;

   isFetching.value = true;
   lastScrollHeight.value = scrollContainer.value.scrollHeight;

   router.get(
      props.messages.next_page_url,
      {},
      {
         only: ['messages'],
         preserveState: true,
         preserveScroll: true,
         preserveUrl: true,
         onSuccess: () => {
            isFetching.value = false;
         },
      },
   );
};

// Zachowanie pozycji scrolla przy dodawaniu nowych wiadomości
watch(
   () => props.messages,
   () => {
      const previousHeight = lastScrollHeight.value;
      results.value.unshift(...props.messages.data);

      // Przywróć pozycję scrolla tylko gdy ładujemy starsze wiadomości
      if (previousHeight > 0) {
         nextTick(() => {
            const newScrollHeight = scrollContainer.value.scrollHeight;
             scrollContainer.value.scrollTop = newScrollHeight - previousHeight;
         });
      }
   },
   { immediate: true },
);

const scrollToBottom = () => {
   if (scrollContainer.value) {
      scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight;
   }
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
         // Po wysłaniu wiadomości przewijamy na dół
         nextTick(() => {
            scrollToBottom();
         });
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
   // Opcjonalnie możemy tu dodać automatyczne przewijanie na dół przy nowych wiadomościach
   // jeśli użytkownik jest już na dole
   const isAtBottom =
      scrollContainer.value.scrollHeight - scrollContainer.value.scrollTop === scrollContainer.value.clientHeight;

   if (isAtBottom) {
      nextTick(() => {
         scrollToBottom();
      });
   }
});

onMounted(() => {
   scrollToBottom();
   setupIntersectionObserver();
});

onBeforeUnmount(() => {
   if (observer.value && loadingTrigger.value) {
      observer.value.unobserve(loadingTrigger.value);
   }
});
</script>
