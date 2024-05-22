<template>
   <div
       @mouseenter="(message.from_id === $page.props.auth.user.id) && !message.deleted_at ? isShow = true : isShow = false"
       @mouseleave="isShow = false"
       class="flex items-center space-x-2 bg"
      :class="message.from_id === $page.props.auth.user.id ? 'justify-end' : 'justify-start'"
   >
       <button
           v-if="isShow"
           @click="handleDelete(message.id)"
           class="hover:text-error transition duration-300 opacity-0"
           :class="isShow ? 'opacity-100 transition-all duration-300' : ''"
       >
           <FontAwesomeIcon icon="fa-solid fa-trash" size="lg" />
       </button>
      <div
          v-if="!message.deleted_at"
         class="text-white rounded-2xl px-4 py-2 max-w-[250px] lg:max-w-lg"
         :class="message.from_id === $page.props.auth.user.id ? 'bg-secondary' : 'bg-neutral/90'"
      >
         <img :src="message.attachment" alt="" class="w-full h-36 object-cover" v-if="message.attachment" />
         <p class="flex justify-end">{{ message.body }}</p>
      </div>
       <div v-else
            class="text-white rounded-2xl px-4 py-2 max-w-[250px] lg:max-w-lg"
            :class="message.from_id === $page.props.auth.user.id ? 'bg-secondary/30' : 'bg-neutral/30'"
       >
          <p class="flex justify-end italic">This message has been deleted</p>
       </div>
   </div>
</template>

<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
   message: Object,
});

const isShow = ref(false);

const handleDelete = (id) => {
   if (confirm('Are you sure you want to delete this message?')) {
      router.delete(route('messages.destroy', {chat: props.message.chat_id, message: id}));
   }
}
</script>
